<h2 id="toc_0">物理内存管理</h2>

<p>接下来将首先对实验的执行流程做个介绍，并进一步介绍如何探测物理内存的大小与布局，如何以页为单位来管理计算机系统中的物理内存，如何设计物理内存页的分配算法，最后比较详细地分析了在80386的段页式硬件机制下，ucore操作系统把段式内存管理的功能弱化，并实现以分页为主的页式内存管理的过程。</p>

<h3 id="toc_1">探测系统物理内存布局</h3>

<p>当 ucore
被启动之后，最重要的事情就是知道还有多少内存可用，一般来说，获取内存大小的方法由
BIOS 中断调用和直接探测两种。但BIOS
中断调用方法是一般只能在实模式下完成，而直接探测方法必须在保护模式下完成。通过
BIOS 中断获取内存布局有三种方式，都是基于INT 15h中断，分别为88h e801h
e820h。但是 并非在所有情况下这三种方式都能工作。在 Linux kernel
里，采用的方法是依次尝试这三
种方法。而在本实验中，我们通过e820h中断获取内存信息。因为e820h中断必须在实模式下使用，所以我们在
bootloader 进入保护模式之前调用这个 BIOS 中断，并且把 e820 映
射结构保存在物理地址0x8000处。具体实现详见boot/bootasm.S。有关探测系统物理内存方法和具体实现的
信息参见lab2试验指导的附录A“探测物理内存分布和大小的方法”和附录B“实现物理内存探测”。</p>

<h3 id="toc_2">以页为单位管理物理内存</h3>

<p>在获得可用物理内存范围后，系统需要建立相应的数据结构来管理以物理页（按4KB对齐，且大小为4KB的物理内存单元）为最小单位的整个物理内存，以配合后续涉及的分页管理机制。每个物理页可以用一个
Page数据结构来表示。由于一个物理页需要占用一个Page结构的空间，Page结构在设计时须尽可能小，以减少对内存的占用。Page的定义在kern/mm/memlayout.h中。以页为单位的物理内存分配管理的实现在kern/default_pmm.[ch]。</p>

<p>为了与以后的分页机制配合，我们首先需要建立对整个计算机的每一个物理页的属性用结构Page来表示，它包含了映射此物理页的虚拟页个数，描述物理页属性的flags和双向链接各个Page结构的page_link双向链表。</p>

<div><pre><code class="language-none">struct Page {
    int ref;        // page frame&#39;s reference counter
    uint32_t flags; // array of flags that describe the status of the page frame
    unsigned int property;// the num of free block, used in first fit pm manager
    list_entry_t page_link;// free list link
};</code></pre></div>

<p>这里看看Page数据结构的各个成员变量有何具体含义。ref表示这样页被页表的引用记数（在“实现分页机制”一节会讲到）。如果这个页被页表引用了，即在某页表中有一个页表项设置了一个虚拟页到这个Page管理的物理页的映射关系，就会把Page的ref加一；反之，若页表项取消，即映射关系解除，就会把Page的ref减一。flags表示此物理页的状态标记，进一步查看kern/mm/memlayout.h中的定义，可以看到：</p>

<div><pre><code class="language-none">/* Flags describing the status of a page frame */
#define PG_reserved                 0       // the page descriptor is reserved for kernel or unusable
#define PG_property                 1       // the member &#39;property&#39; is valid</code></pre></div>

<p>这表示flags目前用到了两个bit表示页目前具有的两种属性，bit
0表示此页是否被保留（reserved），如果是被保留的页，则bit
0会设置为1，且不能放到空闲页链表中，即这样的页不是空闲页，不能动态分配与释放。比如目前内核代码占用的空间就属于这样“被保留”的页。在本实验中，bit
1表示此页是否是free的，如果设置为1，表示这页是free的，可以被分配；如果设置为0，表示这页已经被分配出去了，不能被再二次分配。另外，本实验这里取的名字PG_property比较不直观
，主要是我们可以设计不同的页分配算法（best fit, buddy
system等），那么这个PG_property就有不同的含义了。</p>

<p>在本实验中，Page数据结构的成员变量property用来记录某连续内存空闲块的大小（即地址连续的空闲页的个数）。这里需要注意的是用到此成员变量的这个Page比较特殊，是这个连续内存空闲块地址最小的一页（即头一页，
Head
Page）。连续内存空闲块利用这个页的成员变量property来记录在此块内的空闲页的个数。这里去的名字property也不是很直观，原因与上面类似，在不同的页分配算法中，property有不同的含义。</p>

<p>Page数据结构的成员变量page_link是便于把多个连续内存空闲块链接在一起的双向链表指针（可回顾在lab0实验指导书中有关双向链表数据结构的介绍）。这里需要注意的是用到此成员变量的这个Page比较特殊，是这个连续内存空闲块地址最小的一页（即头一页，
Head
Page）。连续内存空闲块利用这个页的成员变量page_link来链接比它地址小和大的其他连续内存空闲块。</p>

<p>在初始情况下，也许这个物理内存的空闲物理页都是连续的，这样就形成了一个大的连续内存空闲块。但随着物理页的分配与释放，这个大的连续内存空闲块会分裂为一系列地址不连续的多个小连续内存空闲块，且每个连续内存空闲块内部的物理页是连续的。那么为了有效地管理这些小连续内存空闲块。所有的连续内存空闲块可用一个双向链表管理起来，便于分配和释放，为此定义了一个free_area_t数据结构，包含了一个list_entry结构的双向链表指针和记录当前空闲页的个数的无符号整型变量nr_free。其中的链表指针指向了空闲的物理页。</p>

<div><pre><code class="language-none">/* free_area_t - maintains a doubly linked list to record free (unused) pages */
typedef struct {
            list_entry_t free_list;                                // the list header
            unsigned int nr_free;                                 // # of free pages in this free list
} free_area_t;</code></pre></div>

<p>有了这两个数据结构，ucore就可以管理起来整个以页为单位的物理内存空间。接下来需要解决两个问题：</p>

<p>• 管理页级物理内存空间所需的Page结构的内存空间从哪里开始，占多大空间？
• 空闲内存空间的起始地址在哪里？</p>

<p>对于这两个问题，我们首先根据bootloader给出的内存布局信息找出最大的物理内存地址maxpa（定义在page_init函数中的局部变量），由于x86的起始物理内存地址为0，所以可以得知需要管理的物理页个数为</p>

<div><pre><code class="language-none">npage = maxpa / PGSIZE</code></pre></div>

<p>这样，我们就可以预估出管理页级物理内存空间所需的Page结构的内存空间所需的内存大小为：</p>

<div><pre><code class="language-none">sizeof(struct Page) * npage)</code></pre></div>

<p>由于bootloader加载ucore的结束地址（用全局指针变量end记录）以上的空间没有被使用，所以我们可以把end按页大小为边界去整后，作为管理页级物理内存空间所需的Page结构的内存空间，记为：</p>

<div><pre><code class="language-none">pages = (struct Page *)ROUNDUP((void *)end, PGSIZE);</code></pre></div>

<p>为了简化起见，从地址0到地址pages+ sizeof(struct Page) *
npage)结束的物理内存空间设定为已占用物理内存空间（起始0~640KB的空间是空闲的），地址pages+
sizeof(struct Page) *
npage)以上的空间为空闲物理内存空间，这时的空闲空间起始地址为</p>

<div><pre><code class="language-none">uintptr_t freemem = PADDR((uintptr_t)pages + sizeof(struct Page) * npage);</code></pre></div>

<p>为此我们需要把这两部分空间给标识出来。首先，对于所有物理空间，通过如下语句即可实现占用标记：</p>

<div><pre><code class="language-none">for (i = 0; i &lt; npage; i ++) {
SetPageReserved(pages + i);
}
````
然后，根据探测到的空闲物理空间，通过如下语句即可实现空闲标记：
</code></pre></div>

<p>//获得空闲空间的起始地址begin和结束地址end
……
init<em>memmap(pa2page(begin), (end - begin) / PGSIZE);
```
其实SetPageReserved只需把物理地址对应的Page结构中的flags标志设置为PG\</em>reserved
，表示这些页已经被使用了，将来不能被用于分配。而init_memmap函数则是把空闲物理页对应的Page结构中的flags和引用计数ref清零，并加到free_area.free_list指向的双向列表中，为将来的空闲页管理做好初始化准备工作。</p>

<p>关于内存分配的操作系统原理方面的知识有很多，但在本实验中只实现了最简单的内存页分配算法。相应的实现在default_pmm.c中的default_alloc_pages函数和default_free_pages函数，相关实现很简单，这里就不具体分析了，直接看源码，应该很好理解。</p>

<p>其实实验二在内存分配和释放方面最主要的作用是建立了一个物理内存页管理器框架，这实际上是一个函数指针列表，定义如下：</p>

<div><pre><code class="language-none">struct pmm_manager {
            const char *name; //物理内存页管理器的名字
            void (*init)(void); //初始化内存管理器
            void (*init_memmap)(struct Page *base, size_t n); //初始化管理空闲内存页的数据结构
            struct Page *(*alloc_pages)(size_t n); //分配n个物理内存页
            void (*free_pages)(struct Page *base, size_t n); //释放n个物理内存页
            size_t (*nr_free_pages)(void); //返回当前剩余的空闲页数
            void (*check)(void); //用于检测分配/释放实现是否正确的辅助函数
};</code></pre></div>

<p>重点是实现init_memmap/ alloc_pages/
free_pages这三个函数。当完成物理内存页管理初始化工作后，计算机系统的内存布局如下图所示：</p>

<p><img src="wenzhang/tupian/image003.png" alt="">
图3 计算机系统的内存布局</p>

<h3 id="toc_3">物理内存页分配算法实现</h3>

<p>如果要在ucore中实现连续物理内存分配算法，则需要考虑的事情比较多，相对课本上的物理内存分配算法描述要复杂不少。下面介绍一下如果要实现一个FirstFit内存分配算法的大致流程。</p>

<p>lab2的第一部分是完成first_fit的分配算法。原理FirstFit内存分配算法上很简单，但要在ucore中实现，需要充分了解和利用ucore已有的数据结构和相关操作、关键的一些全局变量等。</p>

<p><strong>关键数据结构和变量</strong></p>

<p>first_fit分配算法需要维护一个查找有序（地址按从小到大排列）空闲块（以页为最小单位的连续地址空间）的数据结构，而双向链表是一个很好的选择。</p>

<p>libs/list.h定义了可挂接任意元素的通用双向链表结构和对应的操作，所以需要了解如何使用这个文件提供的各种函数，从而可以完成对双向链表的初始化/插入/删除等。</p>

<p>kern/mm/memlayout.h中定义了一个 free_area_t 数据结构，包含成员结构</p>

<div><pre><code class="language-none">  list_entry_t free_list;         // the list header   空闲块双向链表的头
  unsigned int nr_free;           // # of free pages in this free list  空闲块的总数（以页为单位）</code></pre></div>

<p>显然，我们可以通过此数据结构来完成对空闲块的管理。而default_pmm.c中定义的free_area变量就是干这个事情的。</p>

<p>kern/mm/pmm.h中定义了一个通用的分配算法的函数列表，用pmm_manager
表示。其中init函数就是用来初始化free_area变量的,
first_fit分配算法可直接重用default_init函数的实现。init_memmap函数需要根据现有的内存情况构建空闲块列表的初始状态。何时应该执行这个函数呢？</p>

<p>通过分析代码，可以知道：</p>

<div><pre><code class="language-none">kern_init --&gt; pmm_init--&gt;page_init--&gt;init_memmap--&gt; pmm_manager-&gt;init_memmap</code></pre></div>

<p>所以，default_init_memmap需要根据page_init函数中传递过来的参数（某个连续地址的空闲块的起始页，页个数）来建立一个连续内存空闲块的双向链表。这里有一个假定page_init函数是按地址从小到大的顺序传来的连续内存空闲块的。链表头是free_area.free_list，链表项是Page数据结构的base-&gt;page_link。这样我们就依靠Page数据结构中的成员变量page_link形成了连续内存空闲块列表。</p>

<p><strong>设计实现</strong></p>

<p>default_init_memmap函数讲根据每个物理页帧的情况来建立空闲页链表，且空闲页块应该是根据地址高低形成一个有序链表。根据上述变量的定义，default_init_memmap可大致实现如下：</p>

<div><pre><code class="language-none">default_init_memmap(struct Page *base, size_t n) {
    struct Page *p = base;
    for (; p != base + n; p ++) {
        p-&gt;flags = p-&gt;property = 0;
        set_page_ref(p, 0);
    }
    base-&gt;property = n;
    SetPageProperty(base);
    nr_free += n;
    list_add(&amp;free_list, &amp;(base-&gt;page_link));
}</code></pre></div>

<p>如果要分配一个页，那要考虑哪些呢？这里就需要考虑实现default_alloc_pages函数，注意参数n表示要分配n个页。另外，需要注意实现时尽量多考虑一些边界情况，这样确保软件的鲁棒性。比如</p>

<div><pre><code class="language-none">if (n &gt; nr_free) {
return NULL;
}</code></pre></div>

<p>这样可以确保分配不会超出范围。也可加一些
assert函数，在有错误出现时，能够迅速发现。比如 n应该大于0，我们就可以加上</p>

<div><pre><code class="language-none">assert(n \&gt; 0);</code></pre></div>

<p>这样在n&lt;=0的情况下，ucore会迅速报错。firstfit需要从空闲链表头开始查找最小的地址，通过list_next找到下一个空闲块元素，通过le2page宏可以更加链表元素获得对应的Page指针p。通过p-&gt;property可以了解此空闲块的大小。如果&gt;=n，这就找到了！如果&lt;n，则list_next，继续查找。直到list_next==
&amp;free_list，这表示找完了一遍了。找到后，就要从新组织空闲块，然后把找到的page返回。所以default_alloc_pages可大致实现如下：</p>

<div><pre><code class="language-none">static struct Page *
default_alloc_pages(size_t n) {
    if (n &gt; nr_free) {
        return NULL;
    }
    struct Page *page = NULL;
    list_entry_t *le = &amp;free_list;
    while ((le = list_next(le)) != &amp;free_list) {
        struct Page *p = le2page(le, page_link);
        if (p-&gt;property &gt;= n) {
            page = p;
            break;
        }
    }
    if (page != NULL) {
        list_del(&amp;(page-&gt;page_link));
        if (page-&gt;property &gt; n) {
            struct Page *p = page + n;
            p-&gt;property = page-&gt;property - n;
            list_add(&amp;free_list, &amp;(p-&gt;page_link));
        }
        nr_free -= n;
        ClearPageProperty(page);
    }
    return page;
}</code></pre></div>

<p>default_free_pages函数的实现其实是default_alloc_pages的逆过程，不过需要考虑空闲块的合并问题。这里就不再细讲了。注意，上诉代码只是参考设计，不是完整的正确设计。更详细的说明位于lab2/kernel/mm/default_pmm.c的注释中。希望同学能够顺利完成本实验的第一部分。</p>

<h2 id="toc_4">实现分页机制</h2>

<p>在本实验中，需要重点了解和实现基于页表的页机制和以页为单位的物理内存管理方法和分配算法等。由于ucore OS是基于80386 CPU实现的，所以CPU在进入保护模式后，就直接使能了段机制，并使得ucore OS需要在段机制的基础上建立页机制。下面比较详细地介绍了实现分页机制的过程。</p>

<h3 id="toc_5">段页式管理基本概念</h3>

<p>如图4在保护模式中，x86
体系结构将内存地址分成三种：逻辑地址（也称虚地址）、线性地址和物理地址。逻辑地址即是程序指令中使用的地址，物理地址是实际访问内存的地址。逻
辑地址通过段式管理的地址映射可以得到线性地址，线性地址通过页式管理的地址映射得到物理地址。</p>

<p><img src="wenzhang/tupian/image004.png" alt=""></p>

<p>图 4 段页式管理总体框架图</p>

<p>段式管理前一个实验已经讨论过。在 ucore
中段式管理只起到了一个过渡作用，它将逻辑地址不加转换直接映射成线性地址，所以我们在下面的讨论中可以对这两个地址不加区分（目前的
OS 实现也是不加区分的）。对段式管理有兴趣的同学可以参照《Intel® 64 and
IA-32Architectures Software Developer ’s Manual – Volume 3A》3.2 节。</p>

<p>如图5所示，页式管理将线性地址分成三部分（图中的
Linear Address 的 Directory 部分、 Table 部分和 Offset 部分）。ucore
的页式管理通过一个二级的页表实现。一级页表的起始物理地址存放在 cr3
寄存器中，这个地址必须是一个页对齐的地址，也就是低 12 位必须为
0。目前，ucore 用boot_cr3（mm/pmm.c）记录这个值。</p>

<p><img src="wenzhang/tupian/image006.png" alt=""></p>

<p>图 5 分页机制管理</p>

<h3 id="toc_6">建立段页式管理中需要考虑的关键问题</h3>

<p>为了实现分页机制，需要建立好虚拟内存和物理内存的页映射关系，即正确建立二级页表。此过程涉及硬件细节，不同的地址映射关系组合，相对比较复杂。总体而言，我们需要思考如下问题：</p>

<ul>
<li>如何在建立页表的过程中维护全局段描述符表（GDT）和页表的关系，确保ucore能够在各个时间段上都能正常寻址？</li>
<li>对于哪些物理内存空间需要建立页映射关系？</li>
<li>具体的页映射关系是什么？</li>
<li>页目录表的起始地址设置在哪里？</li>
<li>页表的起始地址设置在哪里，需要多大空间？</li>
<li>如何设置页目录表项的内容？</li>
<li>如何设置页表项的内容？</li>
</ul>

<h3 id="toc_7">系统执行中地址映射的四个阶段</h3>

<p>原理课上讲到了页映射，段映射，以及段页式映射关系，但对如何建立段页式映射关系没有详说。其实，在lab1和lab2中都会涉及如何建立映射关系的操作。在lab1中，我们已经碰到到了简单的段映射，即对等映射关系，保证了物理地址和虚拟地址相等，也就是通过建立全局段描述符表，让每个段的基址为0，从而确定了对等映射关系。在lab2中，由于在段地址映射的基础上进一步引入了页地址映射，形成了组合式的段页式地址映射。这种方式虽然更加灵活了，但实现稍微复杂了一些。在lab2中，为了建立正确的地址映射关系，ld在链接阶段生成了ucore OS执行代码的虚拟地址，而bootloader与ucore OS协同工作，通过在运行时对地址映射的一系列“腾挪转移”，从计算机加电，启动段式管理机制，启动段页式管理机制，在段页式管理机制下运行这整个过程中，虚地址到物理地址的映射产生了多次变化，实现了最终的段页式映射关系：</p>

<div><pre><code class="language-none"> virt addr = linear addr = phy addr + 0xC0000000  </code></pre></div>

<p>下面，我们来看看这是如何一步一步实现的。观察一下链接脚本，即tools/kernel.ld文件在lab1和lab2中的区别。在lab1中：</p>

<div><pre><code class="language-none">ENTRY(kern_init)

SECTIONS {
            /* Load the kernel at this address: &quot;.&quot; means the current address */
            . = 0x100000;

            .text : {
                       *(.text .stub .text.* .gnu.linkonce.t.*)
            }</code></pre></div>

<p>这意味着在lab1中通过ld工具形成的ucore的起始虚拟地址从0x100000开始，注意：这个地址是虚拟地址。但由于lab1中建立的段地址映射关系为对等关系，所以ucore的物理地址也是0x100000，而ucore的入口函数kern_init的起始地址。所以在lab1中虚拟地址，线性地址以及物理地址之间的映射关系如下：</p>

<div><pre><code class="language-none"> lab1： virt addr = linear addr = phy addr</code></pre></div>

<p>在lab2中：</p>

<div><pre><code class="language-none">ENTRY(kern_entry)

SECTIONS {
            /* Load the kernel at this address: &quot;.&quot; means the current address */
            . = 0xC0100000;

            .text : {
                        *(.text .stub .text.* .gnu.linkonce.t.*)
            }</code></pre></div>

<p>这意味着lab2中通过ld工具形成的ucore的起始虚拟地址从0xC0100000开始，注意：这个地址也是虚拟地址。入口函数为kern_entry函数（在kern/init/entry.S中）。这与lab1有很大差别。但其实在lab1和lab2中，bootloader把ucore都放在了起始物理地址为0x100000的物理内存空间。这实际上说明了ucore在lab1和lab2中采用的地址映射不同。lab2在不同阶段有不同的虚拟地址，线性地址以及物理地址之间的映射关系。</p>

<p><strong>第一个阶段</strong>是bootloader阶段，即从bootloader的start函数（在boot/bootasm.S中）到执行ucore kernel的kern_\entry函数之前，其虚拟地址，线性地址以及物理地址之间的映射关系与lab1的一样，即：</p>

<div><pre><code class="language-none"> lab2 stage 1： virt addr = linear addr = phy addr</code></pre></div>

<p><strong>第二个阶段</strong>从从kern<em>\entry函数开始，到执行enable</em>page函数（在kern/mm/pmm.c中）之前再次更新了段映射，还没有启动页映射机制。由于gcc编译出的虚拟起始地址从0xC0100000开始，ucore被bootloader放置在从物理地址0x100000处开始的物理内存中。所以当kern_entry函数完成新的段映射关系后，且ucore在没有建立好页映射机制前，CPU按照ucore中的虚拟地址执行，能够被分段机制映射到正确的物理地址上，确保ucore运行正确。这时的虚拟地址，线性地址以及物理地址之间的映射关系为： </p>

<div><pre><code class="language-none"> lab2 stage 2： virt addr - 0xC0000000 = linear addr = phy addr </code></pre></div>

<p>注意此时CPU在寻址时还是只采用了分段机制。最后后并使能分页映射机制（请查看lab2/kern/mm/pmm.c中的enable_paging函数），一旦执行完enable_paging函数中的加载cr0指令（即让CPU使能分页机制），则接下来的访问是基于段页式的映射关系了。</p>

<p><strong>第三个阶段</strong>从enable<em>page函数开始，到执行gdt</em>init函数（在kern/mm/pmm.c中）之前，启动了页映射机制，但没有第三次更新段映射。这时的虚拟地址，线性地址以及物理地址之间的映射关系比较微妙： </p>

<div><pre><code class="language-none"> lab2 stage 3:  virt addr - 0xC0000000 = linear addr  = phy addr + 0xC0000000 # 物理地址在0~4MB之外的三者映射关系
                virt addr - 0xC0000000 = linear addr  = phy addr # 物理地址在0~4MB之内的三者映射关系
</code></pre></div>

<p>请注意<code>pmm_init</code>函数中的一条语句：</p>

<div><pre><code class="language-none"> boot_pgdir[0] = boot_pgdir[PDX(KERNBASE)];</code></pre></div>

<p>就是用来建立物理地址在0~4MB之内的三个地址间的临时映射关系<code>virt addr - 0xC0000000 = linear addr = phy addr</code>。</p>

<p><strong>第四个阶段</strong>从gdt_init函数开始，第三次更新了段映射，形成了新的段页式映射机制，并且取消了临时映射关系，即执行语句“boot_pgdir<strong>[</strong>0<strong>]</strong> <strong>=</strong>
0<strong>;</strong>”把boot_pgdir[0]的第一个页目录表项（0~4MB）清零来取消临时的页映射关系。这时形成了我们期望的虚拟地址，线性地址以及物理地址之间的映射关系： </p>

<div><pre><code class="language-none"> lab2 stage 4： virt addr = linear addr = phy addr + 0xC0000000  </code></pre></div>

<h3 id="toc_8">建立虚拟页和物理页帧的地址映射关系</h3>

<p><strong>建立二级页表</strong></p>

<p>80368的采用了二级页表来建立线性地址与物理地址之间的映射关系。由于我们已经具有了一个物理内存页管理器default_pmm_manager，支持动态分配和释放内存页的功能，我们就可以用它来获得所需的空闲物理页。在二级页表结构中，页目录表占4KB空间，可通过alloc_page函数获得一个空闲物理页作为页目录表（Page Directory Table，PDT）。同理，ucore也通过这种类似方式获得一个页表(Page Table,PT)所需的4KB空间。</p>

<p>整个页目录表和页表所占空间大小取决与二级页表要管理和映射的物理页数。假定当前物理内存0~16MB，每物理页（也称Page Frame）大小为4KB，则有4096个物理页，也就意味这有4个页目录项和4096个页表项需要设置。一个页目录项(Page Directory Entry，PDE)和一个页表项(Page Table Entry，PTE)占4B。即使是4个页目录项也需要一个完整的页目录表（占4KB）。而4096个页表项需要16KB（即4096*4B）的空间，也就是4个物理页，16KB的空间。所以对16MB物理页建立一一映射的16MB虚拟页，需要5个物理页，即20KB的空间来形成二级页表。</p>

<p>为把0~KERNSIZE（明确ucore设定实际物理内存不能超过KERNSIZE值，即0x38000000字节，896MB，3670016个物理页）的物理地址一一映射到页目录项和页表项的内容，其大致流程如下：</p>

<ol>
<li>先通过alloc_page获得一个空闲物理页，用于页目录表；</li>
<li>调用boot_map_segment函数建立一一映射关系，具体处理过程以页为单位进行设置，即</li>
</ol>

<div><pre><code class="language-none">virt addr = phy addr + 0xC0000000</code></pre></div>

<p>设一个32bit线性地址la有一个对应的32bit物理地址pa，如果在以la的高10位为索引值的页目录项中的存在位（PTE_P）为0，表示缺少对应的页表空间，则可通过alloc_page获得一个空闲物理页给页表，页表起始物理地址是按4096字节对齐的，这样填写页目录项的内容为</p>

<div><pre><code class="language-none">  页目录项内容 = (页表起始物理地址 &amp;0x0FFF) | PTE_U | PTE_W | PTE_P</code></pre></div>

<p>进一步对于页表中以线性地址la的中10位为索引值对应页表项的内容为</p>

<div><pre><code class="language-none">  页表项内容 = (pa &amp; ~0x0FFF) | PTE_P | PTE_W</code></pre></div>

<p>其中：</p>

<ul>
<li>PTE_U：位3，表示用户态的软件可以读取对应地址的物理内存页内容</li>
<li>PTE_W：位2，表示物理内存页内容可写</li>
<li>PTE_P：位1，表示物理内存页存在</li>
</ul>

<p>ucore
的内存管理经常需要查找页表：给定一个虚拟地址，找出这个虚拟地址在二级页表中对应的项。通过更改此项的值可以方便地将虚拟地址映射到另外的页上。可完成此功能的这个函数是get_pte函数。它的原型为</p>

<div><pre><code class="language-none">pte_t  *get_pte (pde_t *pgdir,  uintptr_t la, bool  create)</code></pre></div>

<p>下面的调用关系图可以比较好地看出get_pte在实现上诉流程中的位置：</p>

<p><img src="wenzhang/tupian/image007.png" alt=""></p>

<p>图6 get_pte调用关系图</p>

<p>这里涉及到三个类型pte t、pde t和uintptr
t。通过参见mm/mmlayout.h和libs/types.h，可知它们其实都是unsigned
int类型。在此做区分，是为了分清概念。</p>

<p>pde_t全称为 page directory
entry，也就是一级页表的表项（注意：pgdir实际不是表
项，而是一级页表本身。实际上应该新定义一个类型pgd_t来表示一级页表本身）。pte
t全 称为 page table entry，表示二级页表的表项。uintptr
t表示为线性地址，由于段式管理只做直接映射，所以它也是逻辑地址。</p>

<p>pgdir给出页表起始地址。通过查找这个页表，我们需要给出二级页表中对应项的地址。
虽然目前我们只有boot_pgdir一个页表，但是引入进程的概念之后每个进程都会有自己的页
表。</p>

<p>有可能根本就没有对应的二级页表的情况，所以二级页表不必要一开始就分配，而是等到需要的时候再添加对应的二级页表。如果在查找二级页表项时，发现对应的二级页表不存在，则需要根据create参数的值来处理是否创建新的二级页表。如果create参数为0，则get_pte返回NULL；如果create参数不为0，则get_pte需要申请一个新的物理页（通过alloc_page来实现，可在mm/pmm.h中找到它的定义），再在一级页表中添加页目录项指向表示二级页表的新物理页。注意，新申请的页必须全部设定为零，因为这个页所代表的虚拟地址都没有被映射。</p>

<p>当建立从一级页表到二级页表的映射时，需要注意设置控制位。这里应该设置同时设置
上PTE_U、PTE_W和PTE_P（定义可在mm/mmu.h）。如果原来就有二级页表，或者新建立了页表，则只需返回对应项的地址即可。</p>

<p>虚拟地址只有映射上了物理页才可以正常的读写。在完成映射物理页的过程中，除了要象上面那样在页表的对应表项上填上相应的物理地址外，还要设置正确的控制位。有关
x86 中页表控制位的详细信息，请参照《Intel® 64 and IA-32 Architectures
Software Developer ’s Manual – Volume 3A》4.11 节。</p>

<p>只有当一级二级页表的项都设置了用户写权限后，用户才能对对应的物理地址进行读写。
所以我们可以在一级页表先给用户写权限，再在二级页表上面根据需要限制用户的权限，对物理页进行保护。由于一个物理页可能被映射到不同的虚拟地址上去（譬如一块内存在不同进程
间共享），当这个页需要在一个地址上解除映射时，操作系统不能直接把这个页回收，而是要先看看它还有没有映射到别的虚拟地址上。这是通过查找管理该物理页的Page数据结构的成员变量ref（用来表示虚拟页到物理页的映射关系的个数）来实现的，如果ref为0了，表示没有虚拟页到物理页的映射关系了，就可以把这个物理页给回收了，从而这个物理页是free的了，可以再被分配。page_insert函数将物理页映射在了页表上。可参看page_insert函数的实现来了解ucore内核是如何维护这个变量的。当不需要再访问这块虚拟地址时，可以把这块物理页回收并在将来用在其他地方。取消映射由page_remove来做，这其实是page
insert的逆操作。</p>

<p>建立好一一映射的二级页表结构后，接下来就要使能分页机制了，这主要是通过enable_paging函数实现的，这个函数主要做了两件事：</p>

<ol>
<li><p>通过lcr3指令把页目录表的起始地址存入CR3寄存器中；</p></li>
<li><p>通过lcr0指令把cr0中的CR0_PG标志位设置上。</p></li>
</ol>

<p>执行完enable_paging函数后，计算机系统进入了分页模式！但到这一步还没建立好完整的段页式映射。还记得ucore在最开始通过kern_entry函数设置了临时的新段映射机制吗？这个临时的新段映射不是最简单的对等映射，导致虚拟地址和线性地址不相等。这里需要注意：刚进入分页模式的时刻是一个过渡过程。在这个过渡过程中，虚拟地址，线性地址以及物理地址之间的映射关系为：</p>

<div><pre><code class="language-none">virt addr = linear addr + 0xC0000000 = phy addr + 2 * 0xC0000000</code></pre></div>

<p>而我们希望的段页式映射的最终映射关系为：</p>

<div><pre><code class="language-none"> virt addr = linear addr = phy addr + 0xC0000000</code></pre></div>

<p>这里最终的段映射是简单的段对等映射（virt addr = linear addr）。所以我们需要进一步调整段映射关系，即重新设置新的GDT，建立对等段映射。在这个特殊的阶段，如果不把段映射关系改为virt addr = linear addr，则通过段页式两次地址转换后，无法得到正确的物理地址。为此我们需要进一步调用gdt_init函数，根据新的gdt全局段描述符表内容（gdt定义位于pmm.c中），恢复简单的段对等映射关系，即使得virt addr = linear addr。这样在执行完gdt_init后，通过的段机制和页机制实现的地址映射关系为：</p>

<div><pre><code class="language-none">virt addr=linear addr = phy addr +0xC0000000</code></pre></div>

<p>这里存在的一个问题是，在调用enable_page函数到执行gdt_init函数之前，内核使用的还是旧的段表映射，即：</p>

<div><pre><code class="language-none">virt addr = linear addr + 0xC0000000 = phy addr + 2 * 0xC0000000</code></pre></div>

<p>如何保证此时内核依然能够正常工作呢？其实只需让index为0的页目录项的内容等于以索引值为(KERNBASE&gt;&gt;22)的目录表项的内容即可。目前内核大小不超过
4M （实际上是3M，因为内核从 0x100000开始编址），这样就只需要让页表在0~4MB的线性地址与KERNBASE ~ KERNBASE+4MB的线性地址获得相同的映射即可，都映射到 0~4MB的物理地址空间，具体实现在pmm.c中pmm_init函数的语句：</p>

<div><pre><code class="language-none">boot_pgdir[0] = boot_pgdir[PDX(KERNBASE)];</code></pre></div>

<p>实际上这种映射也限制了内核的大小。当内核大小超过预期的3MB
就可能导致打开分页之后内核crash，在后面的试验中，也的确出现了这种情况。解决方法同样简单，就是拷贝更多的高地址对应的页目录项内容到低地址对应的页目录项中即可。</p>

<p>当执行完毕gdt_init函数后，新的段页式映射已经建立好了，上面的0~4MB的线性地址与0~4MB的物理地址一一映射关系已经没有用了。
所以可以通过如下语句解除这个老的映射关系。</p>

<div><pre><code class="language-none">boot_pgdir[0] = 0;</code></pre></div>

<p>在page_init函数建立完实现物理内存一一映射和页目录表自映射的页目录表和页表后，一旦使能分页机制，则ucore看到的内核虚拟地址空间如下图所示：</p>

<p><img src="wenzhang/tupian/image008.png" alt=""></p>

<p>图7 使能分页机制后的虚拟地址空间图</p>
<h4 id="toc_0">gcc汇编指令</h4>

<ol>
<li><p>串操作指令</p>

<ul>
<li><p>cld/std</p>

<ul>
<li>cld/std指令控制方向标志DF。</li>
<li>cld：DF=0，内存地址增大（向高地址增加）；</li>
<li>std：DF=1，内存地址减小（向地地址减小）。</li>
</ul></li>
<li><p>rep/repe/repne</p>

<ul>
<li>一般和scasb,scasw,scasd一起用。</li>
<li>修改寄存器ecx的值：--ecx；？？（没有该操作，在find_first_zero中可以解释？？）</li>
<li>判断循环：

<ul>
<li>rep：若ecx&gt;0，则循环（一般用来给串赋值）；</li>
<li>repne：若ZF=0且ecx&gt;0，则循环（一般用来扫描串）；</li>
<li>repe：若ZF=1且ecx&gt;0，则循环。</li>
</ul></li>
</ul></li>
<li><p>scasb/scasw/scasl</p>

<ul>
<li>al/ax/eax - [es:edi] , 设置相应的标志寄存器的值；</li>
<li>修改寄存器edi的值：如果DF=0，++edi；如果DF=1，--edi；（加减多少字节由b/w/l决定）</li>
<li>修改寄存器ecx的值：--ecx；</li>
</ul></li>
<li><p>stosb/stosw/stosl</p>

<ul>
<li>[es:edi] = al/ax/eax；</li>
<li>修改寄存器edi的值：如果DF=0，++edi；如果DF=1，--edi；</li>
<li>修改寄存器ecx的值：--ecx；</li>
</ul></li>
<li><p>lodsb/lodsw/lodsl</p>

<ul>
<li>al/ax/eax = [ds:esi]；</li>
<li>修改寄存器esi的值：如果DF=0，++edi；如果DF=1，--edi；</li>
<li>修改寄存器ecx的值：--ecx；</li>
</ul></li>
</ul></li>
<li><p>比较指令？？？</p>

<ul>
<li>cmp a,b：首先计算a-b得到结果c，然后根据c和运算结果设置符号位的值：</li>
</ul>

<blockquote>
<p>符号位：SF，ZF，CF，OF，AF，PF</p>

<blockquote>
<p>SF：保存c中符号位的值。</p>

<p>ZF：若c为0，则将ZF设置为1；否则设置为0</p>

<p>CF：（减法）若计算过程中产生了借位，则将CF设置为1；否则设置为0</p>

<p>OF：（加法）若计算过程中产生了溢出，则将OF设置为1；否则设置为0</p>

<p>AF：</p>

<p>PF：</p>
</blockquote>
</blockquote></li>
<li><p>跳转指令（<strong><em>j指令与cmp指令配合使用：cmp指令修改符号位，j指令根据符号位跳转</em></strong>）？？？</p>

<ul>
<li>jmp：无条件跳转。</li>
<li>jne/jnz：若ZF=0，则跳转。</li>
<li>je/jz：若ZF=1，则跳转。</li>
<li>ja：</li>
<li><p>jb：</p>

<div><pre><code class="language-none">nr\_system\_calls = 72
mov $2,%eax
cmpl $nr\_system\_calls-1,%eax
ja bad\_sys\_call</code></pre></div></li>
<li><p>jl/jg：有符号，</p></li>
<li><p>js：若SF=1，则跳转。</p></li>
</ul></li>
<li><p>位移指令</p>

<ul>
<li>  左移指令：sall/shll，空出来的位用0填充</li>
<li>  右移指令：sarl执行算术移位（填上符号位），而shrl执行逻辑移位（填上0）</li>
</ul></li>
<li><p>运算指令</p>

<ul>
<li>add</li>
<li>inc：自加1</li>
<li>sub</li>
<li>cmp</li>
</ul></li>
<li><p>传送指令</p>

<ul>
<li>mov：传送数据</li>
<li>leal：传送地址偏移量</li>
</ul></li>
<li><p>位指令</p>

<ul>
<li><p>位运算指令</p>

<ul>
<li>and：与运算</li>
<li>or：或运算</li>
<li>not：取反运算</li>
</ul></li>
<li><p>位测试指令（结果影响CF位，从0开始）</p>

<ul>
<li>bt：把第几位复制到CF位中</li>
<li>bts：把第几位复制到CF位中，然后置1</li>
<li>btr：把第几位复制到CF位中，然后置0</li>
<li><p>btc：把第几位复制到CF位中，然后取反</p>

<div><pre><code class="language-none">btsl %2,%3      //把%3中的第%2位A的值保存到CF中，然后将A赋值为1</code></pre></div></li>
</ul></li>
<li><p>位扫描指令（结果影响ZF位；从0开始）</p>

<ul>
<li>bsf a,b：从低到高，扫描a, 若找到是1的位, 则将该位的偏移（从0开始还是从1开始？？）赋值给b，并将ZF设置为0；否则将ZF设置为1</li>
<li>bsr：从高到低，同上。</li>
</ul></li>
<li><p>符号位测试指令</p>

<ul>
<li>setb/setc al：若CF=1，则al=1；否则不作操作</li>
<li>sete al：若ZF=1，则al=1；否则不作操作</li>
<li>seta al：若CF=0且ZF=0，则al=1；否则不作操作</li>
<li>setnb/setnc al：若CF=0，则al=1；否则不作操作</li>
</ul></li>
</ul></li>
</ol>
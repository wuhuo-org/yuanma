## 部署系统
### 创建数据库
1. 用于创建数据库的SQL语句的位置：
	- sql/database.sql
2. 数据库中的表包括：
	- zhang\_hao：登录用户的账号信息
	- han\_shu：函数、数据结构、汇编文件
	- dai\_ma：函数的代码、数据结构的字段、汇编文件的代码
	- wen\_ti：问题
	- hui\_da：问题的回答
	- bu\_chong\_hd：问题回答的补充
	- fan\_dui\_hd：问题回答的反对
	- zhu\_shi：注释
	- bu\_chong：注释的补充
	- fan\_dui：注释的反对

### 往数据库导入数据
1. 需要导入数据的表包括：
	- han\_shu
	- dai\_ma
2. 用于往数据库导入数据的SQL语句，由下面的脚本自动生成：
	- sql/create\_sql.sh
3. 脚本create\_sql.sh的用法：
	- create\_sql.sh 目录1 目录2
		- 目录1：代码、数据结构、汇编文件所在的目录
		- 目录2：目录1中需要过滤掉的目录，可以是多个目录，用“/”分割
		- 例如：create\_sql.sh lab1 tools/user
			- 遍历lab1目录下所有的.c、.h、.s、.S文件（除了tools和user目录）
			- 将所有的函数、数据结构、汇编文件的内容生成SQL语句
	- create\_sql.sh生成两个SQL语句文件，和一个log文件：
		- hanshu.sql：导入han\_shu表的数据
		- daima.sql：导入dai\_ma表的数据
		- log\_warning：保存运行过程中个一些警告

### 运行系统
1. 将daima目录下的所有文件拷贝到服务器的相关目录中
2. 用户可通过网址访问网站
	- 测试版本地址：wuhuo.org/ucore
	- 该测试版本基于ucore lab8中的代码，分为以下模块：
		- boot
		- debug
		- driver
		- fs
		- init
		- libs
		- mm
		- process
		- schedule
		- sync
		- syscall
		- trap
	- 每个模块中包含该模块涉及到的所有函数和数据结构的链接

### 网站使用说明
1. 注册登陆后进入网站首页
2. 首页中包含一些文章，点击文章进入文章页面
3. 有些文章会涉及到内核中的函数，在文章页面的右侧会列出函数的名字及链接，点击链接进入函数页面
4. 在函数页面可以进行代码的注释和对代码进行提问等等操作

### 系统中的不足
1. 文章中函数链接的实现：
	- 首先在数据库中修改han\_shu表中的mo\_kuai字段
	- 然后将首页index.php中，文章链接中的type的赋值为前面修改的mo\_kuai字段的值即可
	- 例如：写了一篇关于内存的文章，里面涉及到了get\_page函数，怎么在该文章的页面中显示该函数的链接：
		- 首先，将函数get\_page的mo\_kuai字段修改为‘mm’

				UPDATE han_shu SET mo_kuai = 'mm' WHERE ming\_zi = 'get\_page'
		- 然后，在首页index.php中，添加该文章的条目、设置type的值为‘mm’

				<li><a href=wenzhang.php?name=neicun_guanli&type=mm>内存管理</a></li>；
		> 其中name必须和该文章的文件名相同——文件保存在daima/wenzhang/neicun_guanli.php
	- 不足：
	 	- 一篇文章只能有属于一个“模块”（type），如果写了一篇涉及到两个不同“模块”的文章，则无法将两个“模块”中的函数加载到该文章
	- 解决思路：
		- 放弃“模块”思路，在wenzhang.js中，遍历文章，获取文章中涉及到的函数，并将其加载到文章页面中。
	

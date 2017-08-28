## 部署系统
### 创建数据库
1. 用于创建数据库的SQL语句的位置：
	- doc/database.sql
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
	- doc/create_sql.sh
3. 脚本create_sql.sh的用法：
	- create_sql.sh 目录1 目录2
		- 目录1：代码、数据结构、汇编文件所在的目录
		- 目录2：目录1中需要过滤掉的目录，可以是多个目录，用“/”分割
		- 例如：create_sql.sh lab1 tools/user
			- 遍历lab1目录下所有的.c、.h、.s、.S文件（除了tools和user目录）
			- 将所有的函数、数据结构、汇编文件的内容生成SQL语句
	- create_sql.sh生成两个SQL语句文件，和一个log文件：
		- hanshu.sql：导入han\_shu表的数据
		- daima.sql：导入dai\_ma表的数据
		- log_warning：保存运行过程中个一些警告

### 


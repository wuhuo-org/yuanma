<?php
	session_start();
  	if(isset($_SESSION['linux011-user'])){
		$str = split(":)", $_SESSION['linux011-user']);
    		echo "<div></div>";
    	}
    	else{
		require_once('login.php');
  		exit;
    	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>无惑开源之linux0.11</title>
	<link href="css/css.css" type="text/css" rel="stylesheet"/>
</head>
<body>
<div id="tou">
	<div>
		<h2>linux0.11</h2>
	</div>
	<div id="daohang">
		<ul>
			<li><a href=index.php>返回主页</a></li>
			<li><a href=tongji.php>贡献统计</a></li>
		</ul>
	</div>
	<div id="denglu">
		<?php
  			echo "登录用户：  ".$str[0]."<br />";
		?>
	</div>
	<div id="end">
		兰州大学 Dslab Linux内核组<br/>
		QQ群号：631559816<br/>
	</div>
</div>
<div id="fengexian"><hr/></div>
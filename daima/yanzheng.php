<?php
	session_start();
	unset($_SESSION['linux011-user']);
	
	$username = $_GET['username'];
	$passwd = $_GET['passwd'];
	
	if ($username && $passwd) {
		$db = new mysqli('127.0.0.1', 'linux011', 'ikm-098', 'linux011');
		$db->query("set character set 'utf8'");//读库

		$sql = "SELECT id, zhang_hao FROM zhang_hao WHERE zhang_hao='".$username."' AND mi_ma='".$passwd."'";
		$result = $db->query($sql);

  		if($result->num_rows>0){
  			$row=$result->fetch_assoc();
 			$str = $row['zhang_hao'] . ":)" . $row['id'];
   			$_SESSION['linux011-user'] = $str;
			require_once("index.php");
			$result->free;
			$db->close;	
  		}
		else{
			require_once('login.php');			
			echo '<br/><hr/><p align="center" style="color:red">账号或密码错误！</p>';
 			$result->free;
			$db->close;
  		}
	}
	else{
		require_once('login.php');			
		echo '<br/><hr/><p align="center" style="color:red">账号或密码错误！</p>';
	}
?>

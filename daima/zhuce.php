<?php	
	$username = $_POST['username'];
	$passwd = $_POST['passwd'];
	$youxiang = $_POST['youxiang'];

	if ($username && $passwd && $youxiang) {
		$db = new mysqli('127.0.0.1', 'linux011', 'ikm-098', 'linux011');
		$db->query("set character set 'utf8'");//读库

		$sql = "SELECT * FROM zhang_hao WHERE zhang_hao = '" . $username. "'";
		$result = $db->query($sql);
		if($result->num_rows){
			require_once('zhuce.html');	
			echo '<br/><hr/><p align="center" style="color:red">账号已存在！</p>';
			exit();
		}
		$result->free;

		$db->query("set names 'utf8'");//写库 
		$sql = "INSERT INTO zhang_hao SET zhang_hao='" . $username. "', mi_ma='" . $passwd . "', you_xiang ='" . $youxiang . "'";
		$result = $db->query($sql);

  		if($db->affected_rows == 0){
 			require_once('zhuce.html');
			echo '<br/><hr/><p align="center" style="color:red">注册失败！</p>';
		}
		else{
			require_once('login.php');			
			echo '<br/><hr/><p align="center" style="color:red">注册成功！</p>';		}
		$result->free;
		$db->close;
	}
	else{
		require_once('zhuce.html');			
		echo '<br/><hr/><p align="center" style="color:red">注册失败！</p>';
	}
?>

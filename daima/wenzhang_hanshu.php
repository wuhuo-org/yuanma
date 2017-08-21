<?php
	session_start();
  	if(isset($_SESSION['linux011-user'])){
 		$str = split(":)", $_SESSION['linux011-user']);
    	}
    	else{
		require_once('login.php');
  		exit;
    	}
	$type = $_GET['type'];
	$auth = $str[0];
	$auth_id = $str[1] - 1;

	$db = new mysqli('127.0.0.1', 'linux011', 'ikm-098', 'linux011');
	$db->query("set character set 'utf8'");
	
	$query = "SELECT id,ming_zi FROM `han_shu` WHERE mo_kuai='" . $type . "' ORDER BY ming_zi";
	$result = $db->query($query);
	$len = $result->num_rows;
	$json = array();

	for($i=0; $i<$len; $i++){
		$json_hanshu = array();
		$row = $result->fetch_assoc();
		$json_hanshu["zhu_shi"] = chazhao($row['id'], "zhu_shi");     //在文章涉及的某函数中，当前登录者未处理的注释的总数
		$json_hanshu["wen_ti"] = chazhao($row['id'], "wen_ti");
		$json_hanshu["hui_da"] = chazhao($row['id'], "hui_da");
		$json_hanshu["bu_chong"] = chazhao($row['id'], "bu_chong");
		$json_hanshu["fan_dui"] = chazhao($row['id'], "fan_dui");
		$json_hanshu["bu_chong_hd"] = chazhao($row['id'], "bu_chong_hd");
		$json_hanshu["fan_dui_hd"] = chazhao($row['id'], "fan_dui_hd");
		$json[$row['ming_zi']] = $json_hanshu;
	}
	$result->free;
	$db->close;

	$json_obj = json_encode($json);
	echo $json_obj;

function chazhao($hanshu, $biao){
	$auth_id = $GLOBALS["auth_id"];
	$db = $GLOBALS["db"];
	$query = "SELECT SUM((chl_1 & (1<<".$auth_id."))) as sum FROM ".$biao." WHERE han_shu = " . $hanshu;
	$result = $db->query($query);
	$row = $result->fetch_assoc();
	$result->free;
	return $row['sum']/(1<<$auth_id);
}
?>
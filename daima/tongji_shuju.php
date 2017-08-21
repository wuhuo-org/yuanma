<?php
	session_start();
  	if(isset($_SESSION['linux011-user'])){
		$str = split(":)", $_SESSION['linux011-user']);
    	}
    	else{
		require_once('login.php');
  		exit;
    	}
	$db = new mysqli('127.0.0.1', 'linux011', 'ikm-098', 'linux011');
	$db->query("set character set 'utf8'");
	$type = $_POST['type'];
	$json = array();

	if($type == '日贡献量')
		cha(0);
	else if($type == '周贡献量')
		cha(6);
	else if($type == '月贡献量')
		cha(29);
	else if($type == '总贡献量')
		cha(10000);

	$json_obj = json_encode($json);
	echo $json_obj;

	$db->close;

function cha($tian){
	cha_biao("zhsh", "zhu_shi", $tian);
	cha_biao("wt", "wen_ti", $tian);
	cha_biao("hd", "hui_da", $tian);
	cha_biao("bch", "bu_chong", $tian);
	cha_biao("fd", "fan_dui", $tian);	
	cha_biao("bch_hd", "bu_chong_hd", $tian);
	cha_biao("fd_hd", "fan_dui_hd", $tian);
}

function cha_biao($str, $form, $tian){
	$db = $GLOBALS["db"];
	$query = "SELECT zuo_zhe, COUNT(*) as count FROM (SELECT * FROM  ".$form."  WHERE date_sub(curdate(), INTERVAL ".$tian." day) <= date(shi_jian)) as TB GROUP BY zuo_zhe ORDER BY count DESC";
	$result = $db->query($query);
	$len = $result->num_rows;
	for($i=0; $i<$len; $i++){
		$row = $result->fetch_assoc();
		$json_arr[$i] = array("zuo_zhe"=>$row['zuo_zhe'], "count"=>$row['count']);
	}
	$GLOBALS["json"][$str] = $json_arr;
	$result->free;
}
?>
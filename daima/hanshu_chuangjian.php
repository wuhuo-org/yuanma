<?php
	session_start();
  	if(isset($_SESSION['linux011-user'])){
 		$str = split(":)", $_SESSION['linux011-user']);   //str[0]：作者；str[1]：作者id
    	}
    	else{
		require_once('login.php');
  		exit;
    	}
	$name = $_POST['name'];
	$json = array();
	$auth_id = $str[1] - 1;

//读数据库：
	$db = new mysqli('127.0.0.1', 'linux011', 'ikm-098', 'linux011');
	$db->query("set character set 'utf8'");//读库

	$query = "SELECT * FROM han_shu WHERE ming_zi = '" . $name . "'";
	$result = $db->query($query);
	$row = $result->fetch_assoc();
	$id_hanshu = $row['id'];
	$num_daima = $row['dai_ma'];
	$result->free;	

	$json_tmp = array();
	$json_tmp[0]=array("id_hanshu"=>$id_hanshu,"num_daima"=>$num_daima,"shijian_chuo"=>time());
	$json["han_shu"]=$json_tmp;

	du('dai_ma');	//获取函数所有代码的注释和问题数
	du('zhu_shi');		//获取函数的所有注释
	du('wen_ti');			//获取函数的所有问题
	du('fan_dui');			//获取函数的所有反对
	du('bu_chong');		//获取函数的所有补充
	du('hui_da');			//获取函数的所有回答
	du('bu_chong_hd');	//获取函数的所有回答的补充
	du('fan_dui_hd');	//获取函数的所有回答的反对

	$db->close;
	$json_obj = json_encode($json);
	echo $json_obj;


function du($biao){
	$db = $GLOBALS["db"];
	$id_hanshu = $GLOBALS["id_hanshu"];
	$auth_id = $GLOBALS["auth_id"];
	$json = array();

	if($biao=='dai_ma')
		$query = "SELECT * FROM dai_ma WHERE han_shu = " . $id_hanshu;
	else
		$query = "SELECT *, (chl_1 & 1<<".$auth_id.") as chl FROM ".$biao." WHERE han_shu = " . $id_hanshu;
	$result = $db->query($query);
	$len = $result->num_rows;
	if(!$len){
		$result->free;
		return;
	}
	for($i=0; $i<$len; $i++){
		$row = $result->fetch_assoc();
		if($biao=='dai_ma')
			$json_arr = array("id"=>$row['id'],"xu_hao"=>$row['xu_hao'],"nei_rong"=>$row['nei_rong'],"zhu_shi"=>$row['zhu_shi'],"wen_ti"=>$row['wen_ti']);
		else{
			$chl = (($row['chl'] == '0') ? 0 : 1);
			$json_arr = array("id"=>$row['id'],"dai_ma"=>$row['dai_ma'],"zuo_zhe"=>$row['zuo_zhe'],"shi_jian"=>$row['shi_jian'],"xu_hao"=>$row['xu_hao'],"nei_rong"=>$row['nei_rong'],"chl"=>$chl);
			if($biao=='wen_ti'){
				$json_arr["zhi_chi"]=$row['zhi_chi'];
				$json_arr["hui_da"]=$row['hui_da'];
			}
			else{
				$json_arr["zan_cheng"]=$row['zan_cheng'];
				$json_arr["fan_dui"]=$row['fan_dui'];
				if($biao=='zhu_shi'||$biao=='hui_da'){
					$json_arr["bu_chong"]=$row['bu_chong'];
					if($biao=='hui_da')
						$json_arr["wen_ti"]=$row['wen_ti'];
				}
				else if($biao=='fan_dui'||$biao=='bu_chong')
					$json_arr["zhu_shi"]=$row['zhu_shi'];
				else if($biao=='bu_chong_hd'||$biao=='fan_dui_hd'){
					$json_arr["wen_ti"]=$row['wen_ti'];
					$json_arr["hui_da"]=$row['hui_da'];
				}
			}
		}
		$json[$i] = $json_arr;
	}
	$result->free;
	
	$GLOBALS["json"][$biao] = $json;
}
?>
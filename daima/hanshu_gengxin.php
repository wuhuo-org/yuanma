<?php
	session_start();
  	if(isset($_SESSION['linux011-user'])){
 		$str = split(":)", $_SESSION['linux011-user']);
    	}
    	else{
		require_once('login.php');
  		exit;
    	}

	$hanshu = $_POST['hanshu'];
	$jb_dm = $_POST['jb_dm'];
	$qj_dm = $_POST['qj_dm'];
	$qj_zhsh = $_POST['qj_zhsh'];
	$jb_zhsh = $_POST['jb_zhsh'];
	$qj_hd = $_POST['qj_hd'];
	$jb_hd = $_POST['jb_hd'];
	$qj_wt = $_POST['qj_wt'];
	$jb_wt = $_POST['jb_wt'];
	$qj_fd = $_POST['qj_fd'];
	$qj_bch = $_POST['qj_bch'];
	$qj_fd_hd = $_POST['qj_fd_hd'];
	$qj_bch_hd = $_POST['qj_bch_hd'];
	$txt = mysql_escape_string($_POST['txt']);     //转义mysql数据库字符

	$auth = $str[0];
	$auth_id = $str[1] - 1;
	$shijianchuo = $_POST['shijianchuo'];
	$type = $_POST['type'];

	$db = new mysqli('127.0.0.1', 'linux011', 'ikm-098', 'linux011');
	$db->query("set names 'utf8'");

//更新数据库
	if($type == 'chj_zhsh'){
		$query = sql_yuju_type($type,'dai_ma','zhu_shi',$qj_dm,'zhu_shi');
	}
	else if($type == 'chj_wt'){
		$query = sql_yuju_type($type,'dai_ma','wen_ti',$qj_dm,'wen_ti');
	}
//注释相关
	else if($type == 'zch_zhsh'){
		$query = sql_yuju('zhu_shi','zan_cheng',$qj_zhsh);
	}
	else if($type == 'bch_zhsh'){
		$query = sql_yuju_type($type,'zhu_shi','bu_chong',$qj_zhsh,'bu_chong');
	}
	else if($type == 'fd_zhsh'){
		$query = sql_yuju_type($type,'zhu_shi','fan_dui',$qj_zhsh,'fan_dui');
	}
//问题相关
	else if($type == 'zhch_wt'){
		$query = sql_yuju('wen_ti','zhi_chi',$qj_wt);
	}
	else if($type == 'hd_wt'){
		$query = sql_yuju_type($type,'wen_ti','hui_da',$qj_wt,'hui_da');
	}
//回答相关
	else if($type == 'zch_hd'){
		$query = sql_yuju('hui_da','zan_cheng',$qj_hd);
	}
	else if($type == 'bch_hd'){
		$query = sql_yuju_type($type,'hui_da','bu_chong',$qj_hd,'bu_chong_hd');
	}
	else if($type == 'fd_hd'){
		$query = sql_yuju_type($type,'hui_da','fan_dui',$qj_hd,'fan_dui_hd');
	}
//注释的反对/补充的赞成/反对
	else if($type == 'zch_fd_zhsh'){
		$query = sql_yuju('fan_dui','zan_cheng',$qj_fd);
	}
	else if($type == 'fd_fd_zhsh'){
		$query = sql_yuju('fan_dui','fan_dui',$qj_fd);
	}
	else if($type == 'zch_bch_zhsh'){
		$query = sql_yuju('bu_chong','zan_cheng',$qj_bch);
	}
	else if($type == 'fd_bch_zhsh'){
		$query = sql_yuju('bu_chong','fan_dui',$qj_bch);
	}
//回答的反对/补充的赞成/反对
	else if($type == 'zch_fd_hd'){
		$query = sql_yuju('fan_dui_hd','zan_cheng',$qj_fd_hd);
	}
	else if($type == 'fd_fd_hd'){
		$query = sql_yuju('fan_dui_hd','fan_dui',$qj_fd_hd);
	}
	else if($type == 'zch_bch_hd'){
		$query = sql_yuju('bu_chong_hd','zan_cheng',$qj_bch_hd);
	}
	else if($type == 'fd_bch_hd'){
		$query = sql_yuju('bu_chong_hd','fan_dui',$qj_bch_hd);
	}
	$result = $db->multi_query($query);
	$result->free;
	$db->close;

//读数据库
	$db = new mysqli('127.0.0.1', 'linux011', 'ikm-098', 'linux011');
	$db->query("set character set 'utf8'");
	$json = array();
	du('zhsh_wt','dai_ma');		//获取函数所有代码的注释和问题数
	du('zhsh','zhu_shi');			//获取函数的所有注释
	du('wt','wen_ti');				//获取函数的所有问题
	du('fd','fan_dui');				//获取函数的所有反对
	du('bch','bu_chong');			//获取函数的所有补充
	du('hd','hui_da');				//获取函数的所有回答
	du('bch_hd','bu_chong_hd');	//获取函数的所有回答的补充
	du('fd_hd','fan_dui_hd');		//获取函数的所有回答的反对
	$db->close;
	$json_obj = json_encode($json);
	echo $json_obj;


function sql_yuju($biao,$ziduan,$id){
	$tmp = 'tmp'.time();
	$auth_id = $GLOBALS["auth_id"];
	$query = "UPDATE ".$biao." SET ".$ziduan." = ".$ziduan."+1, shj_ch = now(), chl_1 = chl_1 & ~(1<<".$auth_id.") WHERE id =" . $id;
	return $query;
}
function sql_yuju_type($type,$biao,$ziduan,$id,$biao1){
	$tmp = 'tmp'.time();
	$auth_id = $GLOBALS["auth_id"];
	$hanshu = $GLOBALS["hanshu"];
	$txt = $GLOBALS["txt"];
	$auth = $GLOBALS["auth"];
	$jb_dm = $GLOBALS["jb_dm"];
	$jb_zhsh = $GLOBALS["jb_zhsh"];
	$jb_wt = $GLOBALS["jb_wt"];
	$jb_hd = $GLOBALS["jb_hd"];

//UPDATE
	$query = "UPDATE ".$biao." SET ".$ziduan." = @".$tmp.":=".$ziduan."+1, shj_ch = now()";
	if(!($type=='chj_zhsh' || $type == 'chj_wt'))
		$query .= ", chl_1 = chl_1 & ~(1<<".$auth_id.")";
 	$query .= " WHERE id =" . $id.";";

//INSERT
	$query .= "INSERT INTO ".$biao1." SET xu_hao = @" . $tmp . "-1, dai_ma = " . $jb_dm;
//zan_cheng...
	if($type == 'chj_zhsh'||$type == 'hd_wt')
		$query .= ", zan_cheng = 0, bu_chong = 0, fan_dui = 0";
	else if($type == 'bch_zhsh'||$type == 'fd_zhsh'||$type == 'fd_hd'||$type == 'bch_hd')
		$query .= ", zan_cheng = 0, fan_dui = 0";
	else
		$query .= ", zhi_chi = 0, hui_da = 0";
//jb_*
	if($type == 'bch_zhsh'||$type == 'fd_zhsh')
		$query .= ",zhu_shi = " . $jb_zhsh;

	if($type == 'hd_wt'||$type == 'bch_hd'||$type == 'fd_hd'){
		$query .= ",wen_ti = " . $jb_wt;
		if($type != 'hd_wt')
			$query .= ",hui_da = " . $jb_hd;
	}
//...
	$query .= ", han_shu = " . $hanshu . ", nei_rong = '" . $txt . "', zuo_zhe = '" . $auth  . "', shi_jian = now(), shj_ch = now(), chl_1=~(1<<".$auth_id.")";
	return $query;
}

function du($type,$biao){
	$db = $GLOBALS["db"];
	$hanshu = $GLOBALS["hanshu"];
	$shijianchuo = $GLOBALS["shijianchuo"];
	$auth_id = $GLOBALS["auth_id"];
	$json = array();

	if($type=='zhsh_wt')
		$query = "SELECT * FROM ".$biao." WHERE han_shu = " . $hanshu . " AND shj_ch > from_unixtime('" . $shijianchuo . "') ORDER BY xu_hao";
	else
		$query = "SELECT *, (chl_1 & (1<<".$auth_id.")) as chl FROM ".$biao." WHERE han_shu = " . $hanshu . " AND shj_ch > from_unixtime('" . $shijianchuo . "')";
	$result = $db->query($query);
	$len = $result->num_rows;
	if(!$len){
		$result->free;
		return;
	}
	for($i=0; $i<$len; $i++){
		$row = $result->fetch_assoc();
		if($type=='zhsh_wt')
			$json_arr = array("xu_hao"=>$row['xu_hao'],"zhu_shi"=>$row['zhu_shi'],"wen_ti"=>$row['wen_ti'],"shijian_chuo"=>time());
		else{
			$chl = (($row['chl'] == '0') ? 0 : 1);
			$json_arr = array("id"=>$row['id'],"dai_ma"=>$row['dai_ma'],"zuo_zhe"=>$row['zuo_zhe'],"shi_jian"=>$row['shi_jian'],"xu_hao"=>$row['xu_hao'],"nei_rong"=>$row['nei_rong'],"chl"=>$chl);
			if($type=='wt'){
				$json_arr["zhi_chi"]=$row['zhi_chi'];
				$json_arr["hui_da"]=$row['hui_da'];
			}
			else{
				$json_arr["zan_cheng"]=$row['zan_cheng'];
				$json_arr["fan_dui"]=$row['fan_dui'];
				if($type=='zhsh'||$type=='hd'){
					$json_arr["bu_chong"]=$row['bu_chong'];
					if($type=='hd')
						$json_arr["wen_ti"]=$row['wen_ti'];
				}
				else if($type=='fd'||$type=='bch')
					$json_arr["zhu_shi"]=$row['zhu_shi'];
				else if($type=='bch_hd'||$type=='fd_hd'){
					$json_arr["wen_ti"]=$row['wen_ti'];
					$json_arr["hui_da"]=$row['hui_da'];
				}
			}
		}
		$json[$i] = $json_arr;
	}
	$result->free;
	$GLOBALS["json"][$type] = $json;
}
?>
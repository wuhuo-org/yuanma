<?php
		require_once('head.php');
		$name = $_GET['name'];

//提交对话框
		echo 	'<div id="tj_div" class="bu_xianshi">';
		echo 		'<textarea id="tj_nr"></textarea><br/>';
		echo		'<button id="tj_but">提交</button>';
		echo		'<button id="tj_but_qx">取消</button>';
		echo	'</div>';		

//左：注释&问题
		echo '<div id="left_div">';

		echo   	'<div id="left_div_tou">';
		echo		'<div>';		
		echo 			'<h3 id="mingzi">' . $name . '</h3>';
		echo		'</div>';		
		echo		'<div id="xinxi" class="bu_xianshi">';
		echo			'<span></span>';
		echo			'<span></span>';
		echo			'<span></span><br/>';
		echo			'<span></span>';
		echo		'</div>';		
		echo		'<div id="chuangjian" class="bu_xianshi">';
		echo			'<button id="chj_zhsh">创建注释</button>';
		echo			'<button id="chj_wt">创建问题</button>';
		echo		'</div>';		
		echo 	'</div>';
		echo 	'<div id="fengexian"><hr/></div>';

//注释列表
		echo 	'<div id="left_left_div" class="bu_xianshi">';
		echo 		'<div id="txt">注释：</div>';
		echo 		'<div id="zhushi_div"></div>';
		echo	'</div>';		

//创建注释、问题
		echo 	'<div id="left_mid_div" class="bu_xianshi"></div>';

//问题列表		
		echo 	'<div id="left_right_div" class="bu_xianshi">';
		echo 		'<div id="txt">问题：</div>';
		echo 		'<div id="wenti_div"></div>';
		echo	 '</div>';

//注释
		echo 	'<div id="zhushi_lsh" class="bu_xianshi">';
		echo 		'<div id="txt">注释：</div>';
		echo 		'<div id="zhushi_nr"></div>';
		echo 		'<div id="zhushi_xx">';
		echo			'<button id="zch_zhsh">赞成</button>';
		echo			'<span></span>';
		echo			'<button id="bch_zhsh">补充</button>';
		echo			'<span></span>';
		echo			'<button id="fd_zhsh">反对</button>';
		echo			'<span></span>';
		echo			'<span></span>';
		echo	 	'</div>';
		echo	 '</div>';

//问题
		echo 	'<div id="wenti_lsh" class="bu_xianshi">';
		echo 		'<div id="txt">问题：</div>';
		echo 		'<div id="wenti_nr"></div>';
		echo 		'<div id="wenti_xx">';
		echo			'<button id="zhch_wt">支持</button>';
		echo			'<span></span>';
		echo			'<button id="hd_wt">回答</button>';
		echo			'<span></span>';
		echo			'<span></span>';
		echo	 	'</div>';
		echo	 '</div>';

//问题回答
		echo 	'<div id="huida_lsh" class="bu_xianshi">';
		echo 		'<div id="txt">回答：</div>';
		echo 		'<div id="huida_nr"></div>';
		echo 		'<div id="huida_xx">';
		echo			'<button id="zch_hd">赞成</button>';
		echo			'<span></span>';
		echo			'<button id="bch_hd">补充</button>';
		echo			'<span></span>';
		echo			'<button id="fd_hd">反对</button>';
		echo			'<span></span>';
		echo			'<span></span>';
		echo	 	'</div>';
		echo	 '</div>';

//回答列表
		echo 	'<div id="huida_left_div" class="bu_xianshi">';
		echo 		'<div id="txt">回答：</div>';
		echo 		'<div id="huida_div"></div>';
		echo	'</div>';

//创建回答		
		echo 	'<div id="huida_right_div" class="bu_xianshi"></div>';
	
//注释、回答的补充列表
		echo 	'<div id="xia_left_left_div" class="bu_xianshi">';
		echo 		'<div id="txt">补充：</div>';
		echo 		'<div id="buchong_div"></div>';
		echo	'</div>';		

//创建注释、回答的补充、反对
		echo 	'<div id="xia_left_mid_div" class="bu_xianshi"></div>';

//注释、回答的反对列表
		echo 	'<div id="xia_left_right_div" class="bu_xianshi">';
		echo 		'<div id="txt">反对：</div>';
		echo 		'<div id="fandui_div"></div>';
		echo	 '</div>';

//注释反对
		echo 	'<div id="fandui_lsh" class="bu_xianshi">';
		echo 		'<div id="txt">反对：</div>';
		echo 		'<div id="fandui_nr"></div>';
		echo 		'<div id="fandui_xx">';
		echo			'<button id="zch_fd_zhsh">赞成</button>';
		echo			'<span></span>';
		echo			'<button id="fd_fd_zhsh">反对</button>';
		echo			'<span></span>';
		echo			'<span></span>';
		echo	 	'</div>';
		echo	 '</div>';

//注释补充
		echo 	'<div id="buchong_lsh" class="bu_xianshi">';
		echo 		'<div id="txt">补充：</div>';
		echo 		'<div id="buchong_nr"></div>';
		echo 		'<div id="buchong_xx">';
		echo			'<button id="zch_bch_zhsh">赞成</button>';
		echo			'<span></span>';
		echo			'<button id="fd_bch_zhsh">反对</button>';
		echo			'<span></span>';
		echo			'<span></span>';
		echo	 	'</div>';
		echo	 '</div>';

//回答反对
		echo 	'<div id="fandui_hd_lsh" class="bu_xianshi">';
		echo 		'<div id="txt">反对：</div>';
		echo 		'<div id="fandui_hd_nr"></div>';
		echo 		'<div id="fandui_hd_xx">';
		echo			'<button id="zch_fd_hd">赞成</button>';
		echo			'<span></span>';
		echo			'<button id="fd_fd_hd">反对</button>';
		echo			'<span></span>';
		echo			'<span></span>';
		echo	 	'</div>';
		echo	 '</div>';

//回答补充
		echo 	'<div id="buchong_hd_lsh" class="bu_xianshi">';
		echo 		'<div id="txt">补充：</div>';
		echo 		'<div id="buchong_hd_nr"></div>';
		echo 		'<div id="buchong_hd_xx">';
		echo			'<button id="zch_bch_hd">赞成</button>';
		echo			'<span></span>';
		echo			'<button id="fd_bch_hd">反对</button>';
		echo			'<span></span>';
		echo			'<span></span>';
		echo	 	'</div>';
		echo	 '</div>';

		echo '</div>';
//右：代码
		echo '<div id="right_div"></div>';
?>
	<script src="jquery/jquery-3.2.1.min.js"></script>
	<script src="hanshu.js"></script>
<?php
	require_once('end.php');
?>
<?php
	require_once('head.php');
	echo '<br/>';

	$db = new mysqli('127.0.0.1', 'linux011', 'ikm-098', 'linux011');
	$db->query("set character set 'utf8'");
?>

<div id="left_div_tj">
	<ol>
		<li><a href="#">日贡献量</a></li>
		<li><a href="#">周贡献量</a></li>
		<li><a href="#">月贡献量</a></li>
		<li><a href="#">总贡献量</a></li>
	</ol>
</div>
<div id="right_div_tj">
	<div><h4>注释</h4><div id='zhsh'></div></div>
	<div><h4>问题</h4><div id='wt'></div></div>
	<div><h4>回答问题</h4><div id='hd'></div></div>
	<div><h4>补充注释</h4><div id='bch'></div></div>
	<div><h4>反对注释</h4><div id='fd'></div></div>
	<div><h4>补充问题回答</h4><div id='bch_hd'></div></div>
	<div><h4>反对问题回答</h4><div id='fd_hd'></div></div>
</div>
	<script src="jquery/jquery-3.2.1.min.js"></script>
	<script src="tongji.js"></script>

<?php
	require_once('end.php');
?>
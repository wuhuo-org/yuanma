<?php
	$name = $_GET['name'];
	$type = $_GET['type'];

	require_once('head.php');
	echo '<div id="left_div_nr">';
	require_once('wenzhang/'.$name . '.php');
	echo '</div>';
	echo '<div id="right_div_nr" class="'.$type.'">';
?>
	<table id="xianshi_tb">
		<tr>
			<td>注释</td>
			<td>问题</td>
			<td>回答问题</td>
			<td>补充注释</td>
			<td>反对注释</td>			
			<td>补充问题回答</td>
			<td>反对问题回答</td>
			<td>函数</td>
		</tr>
	</table>
	</div>
	<script src="jquery/jquery-3.2.1.min.js"></script>
	<script src="wenzhang.js"></script>
<?php
	require_once('end.php');
?>
<?php
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	require ("../../../../conn.php");
	error_reporting(0);
	date_default_timezone_set('PRC');
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag) {
		//获取任务单
		case 'getTaskNum':
			$userId = isset($_POST["userId"]) ? $_POST["userId"] : '';
			
			$sql = "SELECT 	a.* FROM tb_quality_inspect_task AS a, tb_quality_group_member AS b, tb_quality_inspect_task_assign AS c WHERE a.groupId = b.groupID AND b.userID = '$userId' AND c.userId = '$userId' AND a.defineState = '已完成' AND a.scoreState = '未完成' AND a.`timeStamp` = c.taskTimeStamp GROUP BY a.`timeStamp`";
			$result = $conn -> query($sql);
			$listNum = $result -> num_rows;
			
			$sql = "SELECT 	a.* FROM tb_quality_inspect_task AS a, tb_quality_group_member AS b, tb_quality_inspect_task_assign AS c WHERE a.groupId = b.groupID AND b.userID = '$userId' AND c.userId = '$userId' AND a.defineState = '已完成' AND a.scoreState = '已完成' AND a.signState = '' AND c.completeState = '已完成' AND a.`timeStamp` = c.taskTimeStamp GROUP BY a.`timeStamp`";
			$result = $conn -> query($sql);
			$scoreNum = $result -> num_rows;
			
			$ret_data['listNum'] = $listNum;
			$ret_data['scoreNum'] = $scoreNum;
		break;
	}
	$conn -> close();
	$json = json_encode($ret_data);
	echo $json;
?>
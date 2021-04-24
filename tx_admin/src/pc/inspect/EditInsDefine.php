<?php
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	require ("../../../conn.php");
	error_reporting(0);
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag) {
		//修改巡查任务定义
		case 'changeTask':
			$formData = isset($_POST["formData"]) ? $_POST["formData"] : '';
			$formData = json_decode($formData);
			$inspectName = $formData->inspectName;
			$inspectLevel = $formData->inspectLevel;
			$inspectCate = $formData->inspectCate;
			$inspectGroup = $formData->inspectGroup;
			$taskId = $formData->taskId;
			
			$sql = "SELECT * FROM tb_quality_task WHERE id = '$taskId' ";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$sql = "UPDATE tb_quality_task SET inspectName = '$inspectName',inspectLevel = '$inspectLevel',inspectCate = '$inspectCate',inspectGroup = '$inspectGroup' WHERE id = '$taskId' ";
				$result = $conn -> query($sql);
			}else{
				$ret_data['data'] = [];
				$ret_data["states"] = 'error';
			}
		break;
	}
	$conn -> close();
	$json = json_encode($ret_data);
	echo $json;
?>
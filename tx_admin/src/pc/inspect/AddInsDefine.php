<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$ret_data = array();
	switch($flag){
		//新建巡查任务
		case 'addTask':
			$formDate = isset($_POST["formDate"])?$_POST["formDate"]:'';
			$timeStamp = isset($_POST["timeStamp"])?$_POST["timeStamp"]:'';
			
			$formDate = json_decode($formDate);
			$inspectName = $formDate->inspectName;
			$inspectLevel = $formDate->inspectLevel;
			$inspectCate = $formDate->inspectCate;
			$inspectGroup = $formDate->inspectGroup;
			$creatorId = $formDate->creatorId;
			
			$sql="SELECT * FROM tb_quality_task WHERE inspectName = '$inspectName' "; 
			$result = $conn->query($sql);
			if ($result->num_rows == 0) {
				$sql="INSERT INTO tb_quality_task SET timeStamp='$timeStamp',inspectName = '$inspectName',inspectLevel = '$inspectLevel',inspectCate='$inspectCate',inspectGroup='$inspectGroup',creatorId='$creatorId',isEnable='0'"; 
				$result = $conn->query($sql);
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
				
			}
		break;
	}
	
	$json = json_encode($ret_data);
	echo $json;
	$conn->close();	
?>
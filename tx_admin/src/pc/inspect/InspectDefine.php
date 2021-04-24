<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$ret_data = array();
	switch($flag){
		//获取巡查任务
		case 'getTask':

			$sql="SELECT * FROM tb_quality_task AS a ORDER BY a.id ASC"; 
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				$i = 0;
				while ($row = $result -> fetch_assoc()) {
					$ret_data["data"][$i]["taskId"] = $row["id"];
					$ret_data["data"][$i]["timeStamp"] = $row["timeStamp"];
					$ret_data["data"][$i]["creatorId"] = $row["creatorId"];
					$ret_data["data"][$i]["inspectName"] = $row["inspectName"];
					$ret_data["data"][$i]["inspectLevel"] = $row["inspectLevel"];
					$ret_data["data"][$i]["inspectCate"] = $row["inspectCate"];
					$ret_data["data"][$i]["inspectGroup"] = $row["inspectGroup"];
					$ret_data["data"][$i]["isEnable"] = $row["isEnable"];
					
					$i++;
					$ret_data["states"] = 'success';
				}
			}else{
				$ret_data["states"] = 'error';
			}
		break;
		//改变巡查任务状态
		case 'changeTaskState':
			$timeStamp = isset($_POST["timeStamp"])?$_POST["timeStamp"]:'';
			$isEnable = isset($_POST["isEnable"])?$_POST["isEnable"]:'';
			
			$sql="SELECT * FROM tb_quality_task WHERE `timeStamp` = '$timeStamp'"; 
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				$sql="UPDATE  tb_quality_task SET isEnable = '$isEnable' WHERE `timeStamp` = '$timeStamp' "; 
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
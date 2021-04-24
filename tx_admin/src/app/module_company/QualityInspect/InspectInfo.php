<?php
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	require ("../../../../conn.php");
	error_reporting(0);
	date_default_timezone_set('PRC');
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag) {
		//获取任务单
		case 'getTask':
			
			$sql = "SELECT * FROM tb_quality_inspect_task as a ORDER BY a.id DESC";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					if($row['defineState'] == '未完成'){
						$listState = 'taskList';
					}else{
						$listState = 'finishList';
					}
					$ret_data[$listState][$i]['insTaskId'] = $row["id"];
					$ret_data[$listState][$i]['inspectObj'] = $row["inspectObj"];
					$ret_data[$listState][$i]['companyRank'] = $row["companyRank"];
					$ret_data[$listState][$i]['inspectName'] = $row["inspectName"];
					$ret_data[$listState][$i]['groupLeader'] = $row["groupLeader"];
					$ret_data[$listState][$i]['deputyLeader'] = $row["deputyLeader"];
					$ret_data[$listState][$i]['inspectTime'] = $row["inspectTime"];
					$ret_data[$listState][$i]['taskTimeStamp'] = $row["timeStamp"];
					$i++;
					$ret_data["states"] = 'success';
				}
			}else{
				$ret_data['data'] = [];
				$ret_data["states"] = 'error';
			}
		break;
		//完成任务单定义
		case 'completeDefine':
			$selectcard = isset($_POST["selectcard"]) ? $_POST["selectcard"] : '';
			$time=date("Y-m-d H:i:s");
		
			$selectcard = json_decode($selectcard);
			foreach($selectcard as $taskTimeStamp){
				$sql1 = "SELECT a.*, b.phone FROM tb_quality_inspect_task_assign AS a , tb_system_user AS b WHERE a.taskTimeStamp = '$taskTimeStamp' AND a.userId = b.id ";
				$result1 = $conn -> query($sql1);
				if ($result1 -> num_rows > 0) {
					$i=0;
					while ($row1 = $result1 -> fetch_assoc()) {
						$inspectObj = $row1["inspectObj"];
						$inspectName = $row1["inspectName"];
						$projectId = $row1["projectId"];
						$userId = $row1["userId"];
						$phone = $row1["phone"];
						$content = '关于'.$inspectObj.$inspectName.'的情况通报';
						$timeStamp=time().mt_rand(111, 999);
						
//						require('../../message_push/messageToperson.php');
						
						//推送任务单消息
						$sql2="INSERT INTO tb_msg_notice SET timeStamp = '$timeStamp', title = '检查信息' , `content` = '$content', cardTimeStamp = '$taskTimeStamp', `time` = '$time', `read` = '0', `delete` = '0', `receiver` = '$phone',`userId` = '$userId', `projectId` = '$projectId' , `messagetype` = '质量检查' ";
						$result2 = $conn->query($sql2);
						$i++;
						$ret_data["states"] = 'success';
					}
				}else{
					$ret_data['data'] = [];
					$ret_data["states"] = 'error';
				}
				//改变任务单状态
				$sql3 = "UPDATE tb_quality_inspect_task SET defineState = '已完成',issueTime = '$time' WHERE timeStamp = '$taskTimeStamp' ";
				$result3 = $conn -> query($sql3);
				
			}
			
			if ($result -> num_rows > 0) {
				
			}else{
				$ret_data["states"] = 'error';
			}
		break;
		//删除任务单
		case 'deleteTask':
			$selectcard = isset($_POST["selectcard"]) ? $_POST["selectcard"] : '';
			$selectcard = json_decode($selectcard);
			
			foreach($selectcard as $taskTimeStamp){
				$sql = "SELECT * FROM tb_quality_inspect_task WHERE timeStamp = '$taskTimeStamp'";
				$result = $conn -> query($sql);
				if ($result -> num_rows > 0) {
					$sql = "DELETE FROM tb_quality_inspect_task WHERE timeStamp = '$taskTimeStamp' ";
					$result = $conn -> query($sql);
					$sql = "DELETE FROM tb_quality_inspect_task_assign WHERE taskTimeStamp = '$taskTimeStamp' ";
					$result = $conn -> query($sql);
					$ret_data["states"] = 'success';
					
				}else{
					$ret_data["states"] = 'error';
				}
			}
		break;
	}
	$conn -> close();
	$json = json_encode($ret_data);
	echo $json;
?>
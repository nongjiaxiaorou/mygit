<?php
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	require ("../../../../conn.php");
	error_reporting(0);
	date_default_timezone_set('PRC');
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag) {
		//获取定义完成的任务单
		case 'getDefinedTask':
			$userId = isset($_POST["userId"]) ? $_POST["userId"] : '';
			
			$sql = "SELECT a.*, b.userID, b.`name`, c.completeState FROM tb_quality_inspect_task AS a , tb_quality_group_member AS b , tb_quality_inspect_task_assign AS c WHERE a.groupId = b.groupID AND b.userID = '$userId' AND c.userId = '$userId' AND a.defineState = '已完成' AND a.`timeStamp` = c.taskTimeStamp GROUP BY a.`timeStamp` ORDER BY a.id DESC";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					if($row['scoreState'] == '未完成'){
						$listState = 'taskList';
					}else{
						$listState = 'finishList';
					}
					$ret_data[$listState][$i]['insTaskId'] = $row["id"];
					$ret_data[$listState][$i]['projectId'] = $row["projectId"];
					$ret_data[$listState][$i]['groupId'] = $row["groupId"];
					$ret_data[$listState][$i]['inspectObj'] = $row["inspectObj"];
					$ret_data[$listState][$i]['companyRank'] = $row["companyRank"];
					$ret_data[$listState][$i]['inspectName'] = $row["inspectName"];
					$ret_data[$listState][$i]['groupLeader'] = $row["groupLeader"];
					$ret_data[$listState][$i]['deputyLeader'] = $row["deputyLeader"];
					$ret_data[$listState][$i]['inspectTime'] = $row["inspectTime"];
					$ret_data[$listState][$i]['userName'] = $row["name"];
					$ret_data[$listState][$i]['userId'] = $row["userID"];
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
				$sql = "SELECT a.* FROM tb_quality_inspect_task_assign a WHERE a.taskTimeStamp = '$taskTimeStamp' AND completeState = '未完成' GROUP BY a.userId ";
				$result = $conn -> query($sql);
				if($result->num_rows >0){//有完成的扣分大类 不计算分数
					$i=0;
					while ($row = $result -> fetch_assoc()) {
						$ret_data['data'][$i]['userName'] = $row["userName"];
						$ret_data['data'][$i]['inspectName'] = $row["inspectName"];
						$ret_data['data'][$i]['inspectObj'] = $row["inspectObj"];
						$ret_data['data'][$i]['userId'] = $row["userId"];
						$ret_data["states"] = 'success';
						$ret_data["msg"] = '未完成';
						$i++;
					}
				}else{//全部扣分大类评分完成 开始计算总分
					$sql = "SELECT Sum(a.fullMarks) fullMarks, Sum(a.deductMarks) deductMarks FROM tb_quality_inspect_task_assign_marks a WHERE a.taskTimeStamp = '$taskTimeStamp' GROUP BY a.taskTimeStamp ";
					$result = $conn -> query($sql);
					if($result->num_rows >0){//有完成的扣分大类 不计算分数
						$i=0;
						while ($row = $result -> fetch_assoc()) {
							$actualMarks = ($row['fullMarks']-$row['deductMarks'])/$row['fullMarks']*100;
							$actualMarks =  number_format($actualMarks,1);
							$i++;
						}
						$sql = "UPDATE tb_quality_inspect_task SET scoreState = '已完成',actualMarks = '$actualMarks' WHERE timeStamp = '$taskTimeStamp' ";
						$result = $conn -> query($sql);
						$ret_data["msg"] = '已完成';
						$ret_data["states"] = 'success';
					}
				}
			}
		break;
	}
	$conn -> close();
	$json = json_encode($ret_data);
	echo $json;
?>
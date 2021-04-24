<?php
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	require ("../../../../conn.php");
	error_reporting(0);
	date_default_timezone_set('PRC');
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag) {
		//获取定义完成的任务单
		case 'getScoredTask':
			$userId = isset($_POST["userId"]) ? $_POST["userId"] : '';
			
			$sql = "SELECT a.*, b.userID, b.`name`, c.completeState FROM tb_quality_inspect_task AS a , tb_quality_group_member AS b , tb_quality_inspect_task_assign AS c WHERE a.groupId = b.groupID AND b.userID = '$userId' AND c.userId = '$userId' AND a.defineState = '已完成' AND a.scoreState = '已完成' AND c.completeState = '已完成'  AND a.`timeStamp` = c.taskTimeStamp GROUP BY a.`timeStamp` ORDER BY a.id DESC";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					if($row['signState'] == ''){
						$listState = 'taskList';
					}else{
						$listState = 'finishList';
					}
					$ret_data[$listState][$i]['insTaskId'] = $row["id"];
					$ret_data[$listState][$i]['projectId'] = $row["projectId"];
					$ret_data[$listState][$i]['inspectObj'] = $row["inspectObj"];
					$ret_data[$listState][$i]['companyRank'] = $row["companyRank"];
					$ret_data[$listState][$i]['inspectName'] = $row["inspectName"];
					$ret_data[$listState][$i]['groupLeader'] = $row["groupLeader"];
					$ret_data[$listState][$i]['deputyLeader'] = $row["deputyLeader"];
					$ret_data[$listState][$i]['inspectTime'] = $row["inspectTime"];
					$ret_data[$listState][$i]['userName'] = $row["name"];
					$ret_data[$listState][$i]['userId'] = $row["userID"];
					$ret_data[$listState][$i]['taskTimeStamp'] = $row["timeStamp"];
					$ret_data[$listState][$i]['actualMarks'] = $row["actualMarks"];
					$ret_data[$listState][$i]['correctMarks'] = $row["correctMarks"];
					$i++;
					$ret_data["states"] = 'success';
				}
			}else{
				$ret_data['data'] = [];
				$ret_data["states"] = 'error';
			}
		break;
		//修正任务单评分
		case 'correctInsTask':
			$selectcard = isset($_POST["selectcard"]) ? $_POST["selectcard"] : '';
			$A = isset($_POST["A"]) ? $_POST["A"] : '';
			$time=date("Y-m-d H:i:s");
			
			$sum = '';
			$selectcard = json_decode($selectcard);
			foreach($selectcard as $taskTimeStamp){
				$sql = "SELECT a.weight, a.deductTableCate FROM tb_quality_summary_table AS a , tb_quality_inspect_task_assign_marks AS b WHERE a.deductTableCate = b.deductTableCate AND b.taskTimeStamp = '$taskTimeStamp' GROUP BY a.deductTableCate ";
				$result = $conn -> query($sql);
				if ($result -> num_rows > 0) {
					$i=0;
					while ($row = $result -> fetch_assoc()) {
						$sql1 = "SELECT Sum(a.fullMarks) fullMarks, Sum(a.deductMarks) deductMarks FROM tb_quality_inspect_task_assign_marks AS a WHERE a.taskTimeStamp = '$taskTimeStamp' AND a.deductTableCate = '".$row['deductTableCate']."'"; 
						$result1 = $conn -> query($sql1);
						if ($result1 -> num_rows > 0) {
							$row1 = $result1 -> fetch_assoc();
							$correctMarks = ($row1['fullMarks']-$row1['deductMarks'])/$row1['fullMarks']*100*$row['weight']*0.01;
							$correctMarks =  number_format($correctMarks,1);
							$sum = $sum+$correctMarks;
						}
						$i++;
					}
//					echo $sum;
				}
				$correctMarks = $sum + $A;
				$sql = "SELECT * FROM tb_quality_inspect_task AS a WHERE a.`timeStamp` = '$taskTimeStamp' ";
				$result = $conn -> query($sql);
				if ($result -> num_rows > 0) {
					$sql = "UPDATE tb_quality_inspect_task SET correctMarks = '$correctMarks' WHERE `timeStamp` = '$taskTimeStamp' ";
					$result = $conn -> query($sql);
					$ret_data["states"] = 'success';
				}
			}
		break;
		//完成检查评分任务单
		case 'completeScoreList':
			$selectcard = isset($_POST["selectcard"]) ? $_POST["selectcard"] : '';
			$signInfo = isset($_POST["signInfo"]) ? $_POST["signInfo"] : '';
			$signInfo = json_decode($signInfo);
			$idea = $signInfo ->idea;
			$signImage = $signInfo ->signImage;
			
			$time=date("Y-m-d H:i:s");
			
			$selectcard = json_decode($selectcard);
			foreach($selectcard as $taskTimeStamp){
				$sql = "SELECT * FROM tb_quality_inspect_task AS a WHERE a.`timeStamp` = '$taskTimeStamp' ";
				$result = $conn -> query($sql);
				if ($result -> num_rows > 0) {//下发推送 等待项目经理（默认）签名确认回复
					$sql = "UPDATE tb_quality_inspect_task SET signState = '待确认' WHERE `timeStamp` = '$taskTimeStamp' ";
					$result = $conn -> query($sql);
					$ret_data["states"] = 'success';
				}
			}
		break;
	}
	$conn -> close();
	$json = json_encode($ret_data);
	echo $json;
?>
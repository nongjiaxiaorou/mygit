<?php
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	require ("../../../../conn.php");
	error_reporting(0);
	date_default_timezone_set('PRC');
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag) {
		//获取定义完成的任务单
		case 'getInspectQues':
			$formData = isset($_POST["formData"]) ? $_POST["formData"] : '';
			$formData = json_decode($formData);
			$taskTimeStamp = $formData->taskTimeStamp;
			
			$sql = "SELECT a.id, a.inspectQues, a.fullMarks, a.deductMarks FROM tb_quality_inspect_task_assign_marks AS a WHERE taskTimeStamp = '$taskTimeStamp'";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					if($row["deductMarks"] == null){
						$row["deductMarks"] = '0';
					}
					$ret_data['data'][$i]['quesId'] = $row["id"];
					$ret_data['data'][$i]['inspectQues'] = $row["inspectQues"];
					$ret_data['data'][$i]['fullMarks'] = $row["fullMarks"];
					$ret_data['data'][$i]['deductMarks'] = $row["deductMarks"];
					$i++;
					$ret_data["states"] = 'success';
				}
			}else{
				$ret_data['data'] = [];
				$ret_data["states"] = 'error';
			}
		break;
		//获取定义完成的任务单
		case 'changeMarks':
			$quesId = isset($_POST["quesId"]) ? $_POST["quesId"] : '';
			$deductMarks = isset($_POST["deductMarks"]) ? $_POST["deductMarks"] : '';
			
			$sql = "SELECT * FROM tb_quality_inspect_task_assign_marks AS a WHERE id = '$quesId'";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$sql = "UPDATE tb_quality_inspect_task_assign_marks SET deductMarks = '$deductMarks' WHERE id = '$quesId'";
				$result = $conn -> query($sql);
				$ret_data["states"] = 'success';
				
			}else{
				$ret_data["states"] = 'error';
			}
		break;
		//获取资料照片
		case 'getScorePic' :
			$taskTimeStamp = isset($_POST["taskTimeStamp"]) ? $_POST["taskTimeStamp"] : '';
	
			$sql1 = "SELECT  * FROM `tb_quality_inspect_question_pic` WHERE taskTimeStamp = '$taskTimeStamp' ORDER BY `id` ASC";
			$result1 = $conn -> query($sql1);
			if ($result1 -> num_rows > 0) {
				$ret_data = array();
				$i = 0;
				while ($row1 = $result1 -> fetch_assoc()) {
					$ret_data["id"][$i] = $row1["id"];
					$ret_data["qualityPic"][$i] = $row1["qualityPic"];
					$ret_data["qualityReplyPic"][$i] = $row1["qualityReplyPic"];
					$ret_data["constructPic"][$i] = $row1["constructPic"];
					$ret_data["constructReplyPic"][$i] = $row1["constructReplyPic"];
					$ret_data["filePic"][$i] = $row1["filePic"];
					$ret_data["fileReplyPic"][$i] = $row1["fileReplyPic"];
					
					$ret_data["code"] = 1;
					$i++;
				}
			} else {
				$ret_data["code"] = 0;
			}
	
		break;
	}
	$conn -> close();
	$json = json_encode($ret_data);
	echo $json;
?>
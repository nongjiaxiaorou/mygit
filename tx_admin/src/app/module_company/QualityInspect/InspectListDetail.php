<?php
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	require ("../../../../conn.php");
	error_reporting(0);
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag) {
		//获取定义完成的任务单
		case 'getUserTaskAssign':
			$userId = isset($_POST["userId"]) ? $_POST["userId"] : '';
			$formData = isset($_POST["formData"]) ? $_POST["formData"] : '';
			$formData = json_decode($formData);
			$projectId = $formData-> projectId;
			$userId = $formData-> userId;
			$inspectName = $formData-> inspectName;
			
			$sql = "SELECT a.inspectItems, GROUP_CONCAT(b.inspectQues SEPARATOR '||') as inspectQues, GROUP_CONCAT(b.fullMarks SEPARATOR '||') as fullMarks,b.deductTableCate FROM tb_quality_inspect_task_assign AS a , tb_quality_inspect_items AS b WHERE a.inspectName = '$inspectName' AND a.userId = '$userId' AND a.projectId = '$projectId' AND a.inspectItems = b.inspectCate GROUP BY a.inspectItems ORDER BY a.inspectItems ASC";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$ret_data['data'][$i]['inspectItems'] = $row["inspectItems"];
					$ret_data['data'][$i]['inspectQues'] = $row["inspectQues"];
					$ret_data['data'][$i]['fullMarks'] = $row["fullMarks"];
					$ret_data['data'][$i]['deductTableCate'] = $row["deductTableCate"];
					$i++;
					$ret_data["states"] = 'success';
				}
			}else{
				$ret_data['data'] = [];
				$ret_data["states"] = 'error';
			}
		break;
		//根据活动名称获取检查小组
		case 'getInsGroupAssign':
			
			$formData = isset($_POST["formData"]) ? $_POST["formData"] : '';
			$formData = json_decode($formData);
			$projectId = $formData-> projectId;
			$userId = $formData-> userId;
			$insTaskId = $formData-> insTaskId;
			$taskTimeStamp = $formData-> taskTimeStamp;
			
			//获取本测试活动相关的小组
			$sql = "SELECT a.id, a.`timeStamp`, a.inspectName, b.userID, b.`name`,b.groupPost FROM tb_quality_inspect_task AS a , tb_quality_group_member AS b WHERE a.id = '$insTaskId' AND a.groupId = b.groupID ";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$ret_data['member'][$i]['userId'] = $row["userID"];
					$ret_data['member'][$i]['userName'] = $row["name"];
					$ret_data['member'][$i]['groupPost'] = $row["groupPost"];
					$i++;
					$ret_data["states"] = 'success';
				}
			}else{
				$ret_data['member'] = [];
				$ret_data["states"] = 'error';
			}
			
			//获取有分配到任务的人及检查项目
			$sql = "SELECT a.userId, a.userName, a.inspectItems, a.completeState FROM tb_quality_inspect_task_assign AS a WHERE taskTimeStamp = '$taskTimeStamp'";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$ret_data['items'][$i]['userId'] = $row["userId"];
					$ret_data['items'][$i]['userName'] = $row["userName"];
					$ret_data['items'][$i]['inspectItems'] = $row["inspectItems"];
					$ret_data['items'][$i]['completeState'] = $row["completeState"];
					$i++;
					$ret_data["states"] = 'success';
				}
			}else{
				$ret_data['items'] = [];
				$ret_data["states"] = 'error';
			}
		break;
		//保存检查问题对应评分情况
		case 'saveItemsMarks':
			$formData = isset($_POST["formData"]) ? $_POST["formData"] : '';
			$formData = json_decode($formData);
			$userId = $formData ->userId;
			$taskTimeStamp = $formData ->taskTimeStamp;

			$accordion = isset($_POST["accordion"]) ? $_POST["accordion"] : '';
			$accordion = json_decode($accordion);
			
			foreach($accordion as $obj){
				$inspectCate = $obj->title;
				$itemsList = $obj->itemsList;
				$deductTableCate = $obj->deductTableCate;
//				print_r($itemsList);
				//循环修改该用户此巡查任务的扣分大类状态
				$sql = "SELECT * FROM tb_quality_inspect_task_assign AS a WHERE taskTimeStamp = '$taskTimeStamp' AND userId = '$userId' AND inspectItems = '$inspectCate' ";
				$result = $conn->query($sql);
				if($result -> num_rows > 0){
					$sql = "UPDATE tb_quality_inspect_task_assign SET completeState = '已完成' WHERE taskTimeStamp = '$taskTimeStamp' AND userId = '$userId' AND inspectItems = '$inspectCate' ";
					$result = $conn->query($sql);
				}
				//循环插入评分细节
				foreach($itemsList as $obj1){
					$inspectQues = $obj1 ->value;
					$radioValue = $obj1 ->radioValue;
					
					$fullMarks = $obj1 ->fullMarks;
					if($radioValue == '缺项'){
						$fullMarks = '0';
					}
					$deductMarks = $obj1 ->deductMarks;
					$deductReason = $obj1 ->deductReason;
//					print_r($items);
					$sql = "INSERT INTO tb_quality_inspect_task_assign_marks SET `taskTimeStamp` = '$taskTimeStamp',userId = '$userId',inspectCate = '$inspectCate',inspectQues = '$inspectQues',fullMarks = '$fullMarks',radioValue = '$radioValue',deductMarks = '$deductMarks',deductReason = '$deductReason',deductTableCate = '$deductTableCate' ";
					$result = $conn->query($sql);
				}
			}
		break;
	}
	$conn -> close();
	$json = json_encode($ret_data);
	echo $json;
?>
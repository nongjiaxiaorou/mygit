<?php
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	require ("../../../../conn.php");
	error_reporting(0);
	date_default_timezone_set('PRC');
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag) {
		//获取区段栋号
		case 'getComProject':
			
			$sql = "SELECT a.company, GROUP_CONCAT(a.projectName) AS projectName, GROUP_CONCAT(a.id) AS projectId, GROUP_CONCAT(a.`timeStamp`) AS proTimeStamp, b.companyRank FROM tb_project AS a , tb_system_company AS b WHERE a.companyId = b.id GROUP BY a.company ORDER BY a.company ASC";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$ret_data["company"][$i] = $row["company"];
					$ret_data["companyRank"][$i] = $row["companyRank"];
					$ret_data["projectName"][$i] = $row["projectName"];
					$ret_data["projectId"][$i] = $row["projectId"];
					$ret_data["proTimeStamp"][$i] = $row["proTimeStamp"];
					$i++;
					$ret_data["states"] = 'success';
				}
			}else{
				$ret_data['data'] = [];
				$ret_data["states"] = 'error';
			}
		break;
		//获取区段栋号
		case 'getInspectName':
			
			$sql = "SELECT * FROM tb_quality_task WHERE isEnable = '1' ORDER BY tb_quality_task.id DESC";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["inspectName"] = $row["inspectName"];
					$i++;	
					$ret_data["states"] = 'success';
				}
			}else{
				$ret_data['data'] = [];
				$ret_data["states"] = 'error';
			}
		break;
		//根据活动名称获取检查小组
		case 'getInsGroup':
			$inspectName = isset($_POST["inspectName"])?$_POST["inspectName"]:'';

			$sql = "SELECT a.inspectName, b.`name`, b.userID, b.phnoeNumber, b.company,b.groupID,b.groupPost FROM tb_quality_task AS a , tb_quality_group_member AS b WHERE a.inspectGroup = b.groupName AND a.inspectName = '$inspectName'";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$ret_data["data"][$i]["name"] = $row["name"];
					$ret_data["data"][$i]["userID"] = $row["userID"];
					$ret_data["data"][$i]["groupID"] = $row["groupID"];
					$ret_data["data"][$i]["groupPost"] = $row["groupPost"];
					$i++;	
					$ret_data["states"] = 'success';
				}
			}else{
				$ret_data['data'] = [];
				$ret_data["states"] = 'error';
			}
		break;
		//根据活动名称获取扣分大类
		case 'getInsCate':
			$inspectName = isset($_POST["inspectName"])?$_POST["inspectName"]:'';

			$sql = "SELECT b.inspectCate FROM tb_quality_task AS a , tb_quality_inspect_items AS b WHERE a.id = b.taskId AND a.inspectName = '$inspectName' GROUP BY b.inspectCate";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$ret_data["data"][$i]["inspectCate"] = $row["inspectCate"];
					$i++;	
					$ret_data["states"] = 'success';
				}
			}else{
				$ret_data['data'] = [];
				$ret_data["states"] = 'error';
			}
		break;
		//保存小组巡查任务
		case 'addInsTask':
			$timeStamp = isset($_POST["timeStamp"])?$_POST["timeStamp"]:'';
			
			$formData = isset($_POST["formData"])?$_POST["formData"]:'';
			$formData = json_decode($formData);
			$inspectName = $formData->inspectName;
			$taskId = $formData->taskId;
			$groupId = $formData->groupId;
			$projectId = $formData->projectId;
			$inspectObj = $formData->inspectObj;
			$inspectObj = $inspectObj[0].'-'.$inspectObj[1];
			$company = $formData->company;
			$companyRank = $formData->companyRank;
			
			$groupAssign = isset($_POST["groupAssign"])?$_POST["groupAssign"]:'';
			$groupAssign = json_decode($groupAssign);

			$groupLeader = '';
			$deputyLeader = '';
			
			$time=date("Y-m-d H:i:s");
			
			$sql = "SELECT * FROM tb_quality_inspect_task AS a WHERE a.projectId = '$projectId' AND a.taskId = '$taskId' ";
			$result = $conn->query($sql);
			if ($result -> num_rows == 0) {
				foreach($groupAssign as $obj){
					$name = $obj->name;
					$userId = $obj->userId;
					$groupPost = $obj->groupPost;
					$items = $obj->items;
					foreach($items as $inspectItems){
						$inspectItems = $inspectItems->value;
						$sql = "INSERT INTO tb_quality_inspect_task_assign SET `taskTimeStamp` = '$timeStamp',inspectName = '$inspectName',inspectItems = '$inspectItems',inspectObj = '$inspectObj',taskId = '$taskId',groupId = '$groupId',userName = '$name',userId = '$userId',assignTime = '$time',projectId = '$projectId',completeState = '未完成' ";
						$result = $conn->query($sql);
					}
					if($groupPost==2){
						$groupLeader = $name;
					}else if($groupPost==1){
						$deputyLeader .= $name.'|';
					}
				}
				
				$sql = "INSERT INTO tb_quality_inspect_task SET `timeStamp` = '$timeStamp',inspectName = '$inspectName',taskId = '$taskId',groupId = '$groupId',inspectObj = '$inspectObj',inspectTime = '$time',defineState = '未完成',groupLeader = '$groupLeader',deputyLeader = '$deputyLeader',company = '$company',companyRank = '$companyRank',scoreState = '未完成',projectId = '$projectId' ";
				$result = $conn->query($sql);
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
			}
		break;
	}
	$conn -> close();
	$json = json_encode($ret_data);
	echo $json;
?>
<?php
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	require ("../../../conn.php");
	error_reporting(0);
	date_default_timezone_set('PRC');
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag) {
		//获取质量预警（目前：问题隐患/整改闭合率/每周巡检次数）
		case 'getWarningMsg':
			$initWarningCate = isset($_POST["initWarningCate"]) ? $_POST["initWarningCate"] : '';
			$startTime = isset($_POST["startTime"]) ? $_POST["startTime"] : '';
			$endTime = isset($_POST["endTime"]) ? $_POST["endTime"] : '';
			if($startTime == '' && $endTime == ''){
				$sqlStr = '';
			}else{
				$sqlStr = "AND warningTime >= '$startTime' AND warningTime <= '$endTime' ";
			}
			
			$sql = "SELECT * FROM tb_msg_warning where warningCate like '%$initWarningCate%' ".$sqlStr." ORDER BY tb_msg_warning.warningTime DESC";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$ret_data["data"][$i]['id'] = $row["id"];
					$ret_data["data"][$i]['noticeNumber'] = $row["noticeNumber"];
					$ret_data["data"][$i]['company'] = $row["company"];
					$ret_data["data"][$i]['inspectPosition'] = $row["inspectPosition"];
					$ret_data["data"][$i]['projectName'] = $row["projectName"];
					$ret_data["data"][$i]['rectifyRate'] = $row["rectifyRate"];
					$ret_data["data"][$i]['violationContent'] = $row["violationContent"];
					$ret_data["data"][$i]['endDate'] = $row["endDate"];
					$ret_data["data"][$i]['warningTime'] = $row["warningTime"];
					$ret_data["data"][$i]['warningCate'] = $row["warningCate"];
					$ret_data["data"][$i]['warningPost'] = $row["warningPost"];
					$ret_data["data"][$i]['isRead'] = $row["isRead"];
					$ret_data["data"][$i]['isDelete'] = $row["isDelete"];
					$i++;
					$ret_data["states"] = 'success';
				}
			}else{
				$ret_data['data'] = [];
				$ret_data["states"] = 'error';
			}
		break;
		//改变预警消息的读状态（存入对应用户id表示已读）
		case 'changeRead':
			$id = isset($_POST["id"]) ? $_POST["id"] : '';
			$userId = isset($_POST["userId"]) ? $_POST["userId"] : '';
			
			$sql = "SELECT * FROM tb_msg_warning WHERE id = '$id' ";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$sql = "UPDATE `tb_msg_warning` SET `isRead` = CONCAT(`isRead`,'$userId','/') WHERE id = '$id' ";
				$result = $conn -> query($sql);
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
			}
		break;
		//删除预警消息（仅对该用户不可见）
		case 'deleteWarning':
			$userId = isset($_POST["userId"]) ? $_POST["userId"] : '';
			$selectcard = isset($_POST["selectcard"]) ? $_POST["selectcard"] : '';
			$selectcard = json_decode($selectcard);
			
			foreach($selectcard as $id){
				$sql = "SELECT * FROM tb_msg_warning WHERE id = '$id' ";
				$result = $conn -> query($sql);
				if ($result -> num_rows > 0) {
					$sql = "UPDATE `tb_msg_warning` SET `isDelete` = CONCAT(`isDelete`,'$userId','/') WHERE id = '$id' ";
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
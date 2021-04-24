<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../../conn.php");
	date_default_timezone_set('PRC');
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	$ret_data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	
	switch($flag){
		//获取通知单
		case 'getNotice':
			$projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
			$buildSelData = isset($_POST["buildSelData"]) ? $_POST["buildSelData"] : '';
			$buildSelData = json_decode($buildSelData);
			$section = $buildSelData->section;
			$build = $buildSelData->build;
			
			$sql = "SELECT * FROM tb_weekly_scene_notice WHERE projectId = '$projectId' AND section = '$section' AND build = '$build' ORDER BY tb_weekly_scene_notice.id DESC";
			$result = $conn -> query($sql);
			$ret_data = array();
			
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					if($row["noticeState"]=='草稿'){
						$cate = 'draft';
					}else if($row["noticeState"]=='整改中'){
						$cate = 'rectify';
					}else if($row["noticeState"]=='延期'){
						$cate = 'delay';
					}else if($row["noticeState"]=='逾期'){
						$cate = 'overdue';
					}else if($row["noticeState"]=='未完成'){
						$cate = 'unfinish';
					}else if($row["noticeState"]=='已完成'){
						$cate = 'finish';
					}
					$ret_data[$cate][$i]["id"] = $row["id"];
					$ret_data[$cate][$i]["subItem"] = $row["subItem"];
					$ret_data[$cate][$i]["inspectCode"] = $row["inspectCode"];
					$ret_data[$cate][$i]["timeStamp"] = $row["timeStamp"];
					$ret_data[$cate][$i]["projectId"] = $row["projectId"];
					$ret_data[$cate][$i]["projectName"] = $row["projectName"];
	//				$ret_data[$cate][$i]["inspectLevel"] = $row["inspectLevel"];
	//				$ret_data[$cate][$i]["inspectCate"] = $row["inspectCate"];
	//				$ret_data[$cate][$i]["inspectUnit"] = $row["inspectUnit"];
	//				$ret_data[$cate][$i]["inspectObj"] = $row["inspectObj"];
//					$ret_data[$cate][$i]["subItem"] = $row["subItem"];
					$ret_data[$cate][$i]["majorCate"] = $row["majorCate"];
					$ret_data[$cate][$i]["database"] = $row["database"];
								
					$ret_data[$cate][$i]["violationItem"] = $row["violationItem"];
	//				$ret_data[$cate][$i]["inspectDate"] = $row["inspectDate"];
	//				$ret_data[$cate][$i]["violationState"] = $row["violationState"];
					$ret_data[$cate][$i]["endTime"] = $row["endTime"];
					$ret_data[$cate][$i]["noticeState"] = $row["noticeState"];
	//				$ret_data[$data][$i]["createTime"] = $row["createTime"];

					$i++;	
				}
			}
			$json = json_encode($ret_data);
			echo $json;
			$conn->close();	
		break;
		//获取楼栋信息
		case 'getBuildInfo':
			$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';
			$sql = "SELECT * FROM tb_project_floor_information WHERE `timeStamp`='$proTimeStamp'";
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
            $i=0;
            if($result->num_rows>0)	{
        	 	while($row = $result->fetch_assoc()){
        	 		$resData['id'] = $row['id'];
					$resData['section'] = $row['section'];
					$resData['build'] = $row['build'];
					$resData['unitName'] = $row['unitName'];
					$resData['undergroundNumber'] = $row['undergroundNumber'];
					$resData['abovegroundNumber'] = $row['abovegroundNumber'];
					$res[$i] = $resData;
	                $i++;
				}
				$data['data'] = $res;
            }else {
				$data['code'] = 0;
			}
			$json = json_encode($data);
			echo $json;
			$conn->close();	
		break;
		//下发通知单进入整改中   违章状态变为整改中  通知单状态变为整改中
		case 'completeInspect':
			$selectcard = isset($_POST["selectcard"]) ? $_POST["selectcard"] : '';
			$selectcard = json_decode($selectcard);
//			print_r($selectcard);
			$time=getdate();
			$timeNow=$time['year']."-".$time['mon']."-".$time['mday']."/".$time['hours'].":".$time['minutes'].":".$time['seconds'];
		
			foreach($selectcard as $id){
				$sql = "SELECT * FROM tb_weekly_scene_notice WHERE `id`='$id' ";
				$result = $conn -> query($sql);
				if($result->num_rows>0){
					$sql = "SELECT * FROM tb_weekly_scene_notice WHERE `id`='$id' AND NOW() < endTime";
					$result = $conn -> query($sql);
					if($result->num_rows>0){//正常 未逾期
						$sql = "UPDATE tb_weekly_scene_notice SET violationState = '整改中',noticeState = '整改中',issueTime='$timeNow' WHERE `id`='$id'";
						$result = $conn -> query($sql);
						$ret_data['msg'] = '整改中';
					}else{//逾期
						$sql = "UPDATE tb_weekly_scene_notice SET violationState = '整改中',noticeState = '逾期',issueTime='$timeNow' WHERE `id`='$id'";
						$result = $conn -> query($sql);
						$ret_data['msg'] = '逾期';
					}
				}else{
					$ret_data['code'] = 0;
				}
			}
			$json = json_encode($ret_data);
			echo $json;
			$conn->close();	
		break;
		//删除通知单
		case 'deleteNotice':
			$selectcard = isset($_POST["selectcard"]) ? $_POST["selectcard"] : '';
			$selectcard = json_decode($selectcard);

			foreach($selectcard as $id){
				$sql = "SELECT * FROM tb_weekly_scene_notice WHERE `id`='$id' ";
				$result = $conn -> query($sql);
				if($result->num_rows>0){
					$row = $result->fetch_assoc();
					//在通知单表处删除
					$sql = "DELETE FROM tb_weekly_scene_notice WHERE id = '$id' ";
					$result = $conn -> query($sql);
					//在通知代办表处删除
					$sql1 = "DELETE FROM tb_msg_notice WHERE cardTimeStamp = '".$row['timeStamp']."' ";
					$result = $conn -> query($sql1);
				}else{
					$data['code'] = 0;
				}
			}
			$json = json_encode($ret_data);
			echo $json;
			$conn->close();	
		break;
		//申请延期
		case 'applyDelay':
			$selectcard = isset($_POST["selectcard"]) ? $_POST["selectcard"] : '';
			$delayTime = isset($_POST["delayTime"]) ? $_POST["delayTime"] : '';
			$selectcard = json_decode($selectcard);

			foreach($selectcard as $id){
				$sql = "SELECT * FROM tb_weekly_scene_notice WHERE `id`='$id' ";
				$result = $conn -> query($sql);
				if($result->num_rows>0){
					$sql = "UPDATE tb_weekly_scene_notice SET noticeState = '延期',endTime = '$delayTime',isDelay = '1' WHERE `id`='$id'";
					$result = $conn -> query($sql);
				}else{
					$data['code'] = 0;
				}
			}
			$json = json_encode($ret_data);
			echo $json;
			$conn->close();	
		break;
		//撤销延期
		case 'cancelDelay':
			$selectcard = isset($_POST["selectcard"]) ? $_POST["selectcard"] : '';
			$selectcard = json_decode($selectcard);

			foreach($selectcard as $id){
				$sql = "SELECT * FROM tb_weekly_scene_notice WHERE `id`='$id' ";
				$result = $conn -> query($sql);
				if($result->num_rows>0){
					$sql = "UPDATE tb_weekly_scene_notice SET noticeState = '未完成',isDelay = '0' WHERE `id`='$id'";
					$result = $conn -> query($sql);
				}else{
					$data['code'] = 0;
				}
			}
			$json = json_encode($ret_data);
			echo $json;
			$conn->close();	
		break;
	}
?>
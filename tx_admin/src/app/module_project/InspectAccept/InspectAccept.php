<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	date_default_timezone_set('PRC'); //东八时区 
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	
	switch($flag){
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
		break;
		//获取检查项目
		case 'getInspectItemInfo':
			$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';
			$proCategory = isset($_POST["proCategory"]) ? $_POST["proCategory"] : '';
			$sql1 = "SELECT standard FROM tb_project WHERE `timeStamp`='$proTimeStamp'";
			$result1 = $conn -> query($sql1);
			$row1 = $result1->fetch_assoc();
			$table_type = $row1["standard"];
			$sql = "SELECT * FROM tb_system_standard1 WHERE `projectType`='$proCategory' AND measurementType='检查验收' GROUP BY inspectionItem";
			$result = $conn -> query($sql);
			$row1 = $result1->fetch_assoc();
			$res = array();
			$resData = array();
            $i=0;
            if($result->num_rows>0)	{
        	 	while($row = $result->fetch_assoc()){
        	 		$resData['id'] = $row['id'];
					$resData['inspectionItem'] = $row['inspectionItem'];
					$res[$i] = $resData;
	                $i++;
				}
				$data['data'] = $res;
            }else {
				$data['code'] = 0;
			}
		break;
		//获取分包单位
		case 'getSubcontractor':
			$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';
			$sql = "SELECT * FROM tb_project_subcontractor WHERE `projectTimestamp`='$proTimeStamp'";
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
            $i=0;
            if($result->num_rows>0)	{
        	 	while($row = $result->fetch_assoc()){
        	 		$resData['id'] = $row['id'];
					$resData['subcontractor'] = $row['subcontractor'];
					$res[$i] = $resData;
	                $i++;
				}
				$data['data'] = $res;
            }else {
				$data['code'] = 0;
			}
		break;
		//新增任务卡片
		case 'addTaskCard':
			$proTimeStamp = isset($_POST["proTimeStamp"])?$_POST["proTimeStamp"]:'';
			$projectType = isset($_POST["projectType"])?$_POST["projectType"]:'';
			$inspectItem = isset($_POST["inspectItem"])?$_POST["inspectItem"]:'';
			$buildInfo = isset($_POST["buildInfo"])?$_POST["buildInfo"]:'';
			$buildInfo = json_decode($buildInfo,true);
			$inputValue = isset($_POST["inputValue"])?$_POST["inputValue"]:'';
			$inputValue = json_decode($inputValue,true);
			$projectName = isset($_POST["projectName"])?$_POST["projectName"]:'';
			$build = $buildInfo["build"];
			$floor = $buildInfo["floor"];
			$unit = $buildInfo["unit"];
			$inspectPosition = $inputValue["inspectPosition"];
			$inspectPerson = $inputValue["inspectPerson"];
			$constructionTeam = $inputValue["constructionTeam"];
			$leaderName = $inputValue["leaderName"];
			$contactMode = $inputValue["contactMode"];
			$construnctionDate = $inputValue["construnctionDate"];
			$inspectDate = $inputValue["inspectDate"];
			$now_time= time();
			$createTime = date('Y-m-d H:i:s',$now_time);
			$createUserName = isset($_POST["createUserName"])?$_POST["createUserName"]:'';
			$createUserId = isset($_POST["createUserId"])?$_POST["createUserId"]:'';
			$substractor = isset($_POST["substractor"])?$_POST["substractor"]:'';
			$sql = "INSERT INTO tb_inspectaccept_measure SET `proTimeStamp` = '$proTimeStamp',`projectName` = '$projectName',`build` = '$build',`floor` = '$floor',`unitName` = '$unit',`inspectPosition` = '$inspectPosition',`inspectPerson` = '$inspectPerson',`constructionTeam` = '$constructionTeam',`teamLeaderName` = '$leaderName',`contactMode` = '$contactMode',`constructionDate` = '$construnctionDate',`inspectDate` = '$inspectDate',`projectCategory` = '',`inspectItem` = '$inspectItem',`measureState` = '未完成',`createTime` = '$createTime',`createUserName` = '$createUserName',`createUserId` = '$createUserId',`measureType` = '检查验收',`subcontract` = '$substractor',`isRead` = '0',`isDelete` = '0'";
			$result = $conn -> query($sql);
			if(!$result){
				$data['code'] = 0;
			}
		break;
		//获取检查验收未完成卡片
		case 'getInspectCard':
			$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';
			$isSelBuild = isset($_POST["isSelBuild"]) ? $_POST["isSelBuild"] : '';
			$buildSelData = isset($_POST["buildSelData"]) ? $_POST["buildSelData"] : '';
			if($isSelBuild){
				$buildSelData = json_decode($buildSelData,true);
				$build = $buildSelData["build"];
				$floor = $buildSelData["floor"];
				$unit = $buildSelData["unit"];
				$sql = "SELECT * FROM tb_inspectaccept_measure WHERE proTimeStamp='$proTimeStamp' AND build='$build' AND floor='$floor' AND unitName='$unit' AND measureType='检查验收' AND measureState='未完成' ORDER BY id DESC";
			}else{
				$sql = "SELECT * FROM tb_inspectaccept_measure WHERE proTimeStamp='$proTimeStamp' AND measureType='检查验收' AND measureState='未完成' ORDER BY id DESC";
			}
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
            $i=0;
            if($result->num_rows>0)	{
        	 	while($row = $result->fetch_assoc()){
        	 		$resData['id'] = $row['id'];
					$resData['projectName'] = $row['projectName'];
					$resData['inspectPosition'] = $row['inspectPosition'];
					$resData['inspectDate'] = $row['inspectDate'];
					$resData['subcontract'] = $row['subcontract'];
					$resData['createUserName'] = $row['createUserName'];
					$resData['inspectItem'] = $row['inspectItem'];
					$res[$i] = $resData;
	                $i++;
				}
				$data['data'] = $res;
            }else {
				$data['code'] = 0;
			}
		break;
		//获取检查验收已完成卡片
		case 'getcompleteCard':
			$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';
			$isSelBuild = isset($_POST["isSelBuild"]) ? $_POST["isSelBuild"] : '';
			$buildSelData = isset($_POST["buildSelData"]) ? $_POST["buildSelData"] : '';
			if($isSelBuild){
				$buildSelData = json_decode($buildSelData,true);
				$build = $buildSelData["build"];
				$floor = $buildSelData["floor"];
				$unit = $buildSelData["unit"];
				$sql = "SELECT * FROM tb_inspectaccept_measure WHERE proTimeStamp='$proTimeStamp' AND build='$build' AND floor='$floor' AND unitName='$unit' AND measureType='检查验收' AND measureState='已完成' ORDER BY id DESC";
			}else{
				$sql = "SELECT * FROM tb_inspectaccept_measure WHERE proTimeStamp='$proTimeStamp' AND measureType='检查验收' AND measureState='已完成' ORDER BY id DESC";
			}
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
            $i=0;
            if($result->num_rows>0)	{
        	 	while($row = $result->fetch_assoc()){
        	 		$resData['id'] = $row['id'];
					$resData['projectName'] = $row['projectName'];
					$resData['inspectPosition'] = $row['inspectPosition'];
					$resData['inspectDate'] = $row['inspectDate'];
					$resData['subcontract'] = $row['subcontract'];
					$resData['createUserName'] = $row['createUserName'];
					$resData['inspectItem'] = $row['inspectItem'];
					$res[$i] = $resData;
	                $i++;
				}
				$data['data'] = $res;
            }else {
				$data['code'] = 0;
			}
		break;
		//获取项目检查草稿卡片
		case 'getDraftCard':
			$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$sql = "SELECT * FROM tb_inspectaccept_notice WHERE measureId='$measureId' AND state='草稿' ORDER BY id DESC";
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
            $i=0;
            if($result->num_rows>0)	{
        	 	while($row = $result->fetch_assoc()){
        	 		$resData['id'] = $row['id'];
        	 		$resData['noticeTimeStamp'] = $row['noticeTimeStamp'];
					$resData['noticeNumber'] = $row['noticeNumber'];
					$resData['inspectPosition'] = $row['inspectPosition'];
					$resData['inspectDate'] = $row['inspectDate'];
					$resData['inspectProcedure'] = $row['inspectProcedure'];
					$resData['inspectItemHead'] = $row['inspectItemHead'];
					$resData['inspectPersonName'] = $row['inspectPersonName'];
					$res[$i] = $resData;
	                $i++;
				}
				$data['data'] = $res;
            }else {
				$data['code'] = 0;
			}
		break;
		//获取项目检查整改中卡片
		case 'getRectificationCard':
			$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$sql = "SELECT * FROM tb_inspectaccept_notice WHERE measureId='$measureId' AND state='整改中' ORDER BY id DESC";
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
            $i=0;
            if($result->num_rows>0)	{
        	 	while($row = $result->fetch_assoc()){
        	 		$resData['id'] = $row['id'];
        	 		$resData['noticeTimeStamp'] = $row['noticeTimeStamp'];
					$resData['noticeNumber'] = $row['noticeNumber'];
					$resData['inspectPosition'] = $row['inspectPosition'];
					$resData['inspectDate'] = $row['inspectDate'];
					$resData['inspectProcedure'] = $row['inspectProcedure'];
					$resData['inspectItemHead'] = $row['inspectItemHead'];
					$resData['inspectPersonName'] = $row['inspectPersonName'];
					$res[$i] = $resData;
	                $i++;
				}
				$data['data'] = $res;
            }else {
				$data['code'] = 0;
			}
		break;
		//获取项目检查已关闭卡片
		case 'getClosedCard':
			$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$sql = "SELECT * FROM tb_inspectaccept_notice WHERE measureId='$measureId' AND state='已关闭' ORDER BY id DESC";
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
            $i=0;
            if($result->num_rows>0)	{
        	 	while($row = $result->fetch_assoc()){
        	 		$resData['id'] = $row['id'];
        	 		$resData['noticeTimeStamp'] = $row['noticeTimeStamp'];
					$resData['noticeNumber'] = $row['noticeNumber'];
					$resData['inspectPosition'] = $row['inspectPosition'];
					$resData['inspectDate'] = $row['inspectDate'];
					$resData['inspectProcedure'] = $row['inspectProcedure'];
					$resData['inspectItemHead'] = $row['inspectItemHead'];
					$resData['inspectPersonName'] = $row['inspectPersonName'];
					$res[$i] = $resData;
	                $i++;
				}
				$data['data'] = $res;
            }else {
				$data['code'] = 0;
			}
		break;
		//删除卡片
		case 'deleteCard':
			$cardTimeStampArr = isset($_POST["cardTimeStamp"]) ? $_POST["cardTimeStamp"] : '';
			$cardTimeStampArr = json_decode($cardTimeStampArr);
			for($i=0;$i<count($cardTimeStampArr);$i++){
				$sql = "DELETE FROM tb_inspectaccept_notice WHERE `noticeTimeStamp`='$cardTimeStampArr[$i]'";
				$result = $conn -> query($sql);
			}
			if($result)	{
				
			}else{
				$data['code'] = 0;
			}
		break;
		//删除检查验收卡片
		case 'deleteInspectAcceptCard':
			$cardId = isset($_POST["cardId"]) ? $_POST["cardId"] : '';
			$cardIdArr = json_decode($cardId);
			for($i=0;$i<count($cardIdArr);$i++){
				$sql = "DELETE FROM tb_inspectaccept_measure WHERE `id`='$cardIdArr[$i]'";
				$result = $conn -> query($sql);
			}
			if($result)	{
				
			}else{
				$data['code'] = 0;
			}
		break;
		//新增任务卡片
		case 'addMeasureTaskCard':
			$proTimeStamp = isset($_POST["proTimeStamp"])?$_POST["proTimeStamp"]:'';
			$projectType = isset($_POST["projectType"])?$_POST["projectType"]:'';
			$inspectItem = isset($_POST["inspectItem"])?$_POST["inspectItem"]:'';
			$buildInfo = isset($_POST["buildInfo"])?$_POST["buildInfo"]:'';
			$buildInfo = json_decode($buildInfo,true);
			$inputValue = isset($_POST["inputValue"])?$_POST["inputValue"]:'';
			$inputValue = json_decode($inputValue,true);
			$projectName = isset($_POST["projectName"])?$_POST["projectName"]:'';
			$build = $buildInfo["build"];
			$floor = $buildInfo["floor"];
			$unit = $buildInfo["unit"];
			$inspectPosition = $inputValue["inspectPosition"];
			$inspectPerson = $inputValue["inspectPerson"];
			$constructionTeam = $inputValue["constructionTeam"];
			$leaderName = $inputValue["leaderName"];
			$contactMode = $inputValue["contactMode"];
			$construnctionDate = $inputValue["construnctionDate"];
			$inspectDate = $inputValue["inspectDate"];
			$now_time= time();
			$createTime = date('Y-m-d H:i:s',$now_time);
			$createUserName = isset($_POST["createUserName"])?$_POST["createUserName"]:'';
			$createUserId = isset($_POST["createUserId"])?$_POST["createUserId"]:'';
			$substractor = isset($_POST["substractor"])?$_POST["substractor"]:'';
			$sql = "INSERT INTO tb_inspectaccept_measure SET `proTimeStamp` = '$proTimeStamp',`projectName` = '$projectName',`build` = '$build',`floor` = '$floor',`unitName` = '$unit',`inspectPosition` = '$inspectPosition',`inspectPerson` = '$inspectPerson',`constructionTeam` = '$constructionTeam',`teamLeaderName` = '$leaderName',`contactMode` = '$contactMode',`constructionDate` = '$construnctionDate',`inspectDate` = '$inspectDate',`projectCategory` = '',`inspectItem` = '$inspectItem',`measureState` = '未完成',`createTime` = '$createTime',`createUserName` = '$createUserName',`createUserId` = '$createUserId',`measureType` = '实测实量',`subcontract` = '$substractor',`isRead` = '0',`isDelete` = '0'";
			$result = $conn -> query($sql);
			if(!$result){
				$data['code'] = 0;
			}
		break;
		case 'getMeasureCard':
			$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';
			$isSelBuild = isset($_POST["isSelBuild"]) ? $_POST["isSelBuild"] : '';
			$buildSelData = isset($_POST["buildSelData"]) ? $_POST["buildSelData"] : '';
			if($isSelBuild){
				$buildSelData = json_decode($buildSelData,true);
				$build = $buildSelData["build"];
				$floor = $buildSelData["floor"];
				$unit = $buildSelData["unit"];
				$sql = "SELECT * FROM tb_inspectaccept_measure WHERE proTimeStamp='$proTimeStamp' AND build='$build' AND floor='$floor' AND unitName='$unit' AND measureType='实测实量' AND measureState='未完成' ORDER BY id DESC";
			}else{
				$sql = "SELECT * FROM tb_inspectaccept_measure WHERE proTimeStamp='$proTimeStamp' AND measureType='实测实量' AND measureState='未完成' ORDER BY id DESC";
			}
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
		    $i=0;
		    if($result->num_rows>0)	{
			 	while($row = $result->fetch_assoc()){
			 		$resData['id'] = $row['id'];
					$resData['projectName'] = $row['projectName'];
					$resData['inspectPosition'] = $row['inspectPosition'];
					$resData['inspectDate'] = $row['inspectDate'];
					$resData['subcontract'] = $row['subcontract'];
					$resData['createUserName'] = $row['createUserName'];
					$resData['inspectItem'] = $row['inspectItem'];
					$res[$i] = $resData;
		            $i++;
				}
				$data['data'] = $res;
		    }else {
				$data['code'] = 0;
			}
		break;
		case 'getMeasurecompleteCard':
			$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';
			$isSelBuild = isset($_POST["isSelBuild"]) ? $_POST["isSelBuild"] : '';
			$buildSelData = isset($_POST["buildSelData"]) ? $_POST["buildSelData"] : '';
			if($isSelBuild){
				$buildSelData = json_decode($buildSelData,true);
				$build = $buildSelData["build"];
				$floor = $buildSelData["floor"];
				$unit = $buildSelData["unit"];
				$sql = "SELECT * FROM tb_inspectaccept_measure WHERE proTimeStamp='$proTimeStamp' AND build='$build' AND floor='$floor' AND unitName='$unit' AND measureType='实测实量' AND measureState='已完成' ORDER BY id DESC";
			}else{
				$sql = "SELECT * FROM tb_inspectaccept_measure WHERE proTimeStamp='$proTimeStamp' AND measureType='实测实量' AND measureState='已完成' ORDER BY id DESC";
			}
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
		    $i=0;
		    if($result->num_rows>0)	{
			 	while($row = $result->fetch_assoc()){
			 		$resData['id'] = $row['id'];
					$resData['projectName'] = $row['projectName'];
					$resData['inspectPosition'] = $row['inspectPosition'];
					$resData['inspectDate'] = $row['inspectDate'];
					$resData['subcontract'] = $row['subcontract'];
					$resData['createUserName'] = $row['createUserName'];
					$resData['inspectItem'] = $row['inspectItem'];
					$res[$i] = $resData;
		            $i++;
				}
				$data['data'] = $res;
		    }else {
				$data['code'] = 0;
			}
		break;
		case 'deleteMeasureCard':
			$cardId = isset($_POST["cardId"]) ? $_POST["cardId"] : '';
			$cardIdArr = json_decode($cardId);
			for($i=0;$i<count($cardIdArr);$i++){
				$sql = "DELETE FROM tb_inspectaccept_measure WHERE `id`='$cardIdArr[$i]'";
				$result = $conn -> query($sql);
			}
			if($result)	{
				
			}else{
				$data['code'] = 0;
			}
		break;
		case 'completeMeasureCard':
			$cardId = isset($_POST["cardId"]) ? $_POST["cardId"] : '';
			$cardIdArr = json_decode($cardId);
			for($i=0;$i<count($cardIdArr);$i++){
				$sql = "UPDATE tb_inspectaccept_measure SET measureState='已完成' WHERE `id`='$cardIdArr[$i]'";
				$result = $conn -> query($sql);
			}
			if($result)	{
				
			}else{
				$data['code'] = 0;
			}
		break;
		case 'completeInspectCard':
			$cardId = isset($_POST["cardId"]) ? $_POST["cardId"] : '';
			$cardIdArr = json_decode($cardId);
			for($i=0;$i<count($cardIdArr);$i++){
				$sql = "UPDATE tb_inspectaccept_measure SET measureState='已完成' WHERE `id`='$cardIdArr[$i]'";
				$result = $conn -> query($sql);
			}
			if($result)	{
				
			}else{
				$data['code'] = 0;
			}
		break;
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	

?>
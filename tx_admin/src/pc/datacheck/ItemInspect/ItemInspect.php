<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	
	switch($flag){
		//获取通知单数据
		case 'getNoticeData':
			$company = isset($_POST["company"])?$_POST["company"]:'';
			$userId = isset($_POST["userId"])?$_POST["userId"]:'';
			$level = isset($_POST["level"])?$_POST["level"]:'';
			if($level=="总公司"||$level=="分公司"){
				if($level=="总公司"){
					$sql = "SELECT `tb_inspectaccept_notice`.*,`tb_project`.`company`, `tb_project`.`projectName`,`tb_inspectaccept_measure`.build,`tb_inspectaccept_measure`.floor,`tb_inspectaccept_measure`.unitName,`tb_inspectaccept_measure`.`inspectItem` FROM `tb_inspectaccept_notice`, `tb_project`,`tb_inspectaccept_measure` WHERE `tb_inspectaccept_notice`.`projectId` = `tb_project`.id AND `tb_inspectaccept_measure`.`id` = `tb_inspectaccept_notice`.`measureId` AND `tb_inspectaccept_measure`.`measureType` = '检查验收' ORDER BY id DESC";
				}else{
					$sql = "SELECT `tb_inspectaccept_notice`.*,`tb_project`.`company`, `tb_project`.`projectName`,`tb_inspectaccept_measure`.build,`tb_inspectaccept_measure`.floor,`tb_inspectaccept_measure`.unitName,`tb_inspectaccept_measure`.`inspectItem` FROM `tb_inspectaccept_notice`, `tb_project`,`tb_inspectaccept_measure` WHERE `tb_inspectaccept_notice`.`projectId` = `tb_project`.id AND `tb_inspectaccept_measure`.`id` = `tb_inspectaccept_notice`.`measureId` AND `tb_inspectaccept_measure`.`measureType` = '检查验收' AND `tb_project`.`company`='$company' ORDER BY id DESC";
				}
			}else{
				$sql = "SELECT 	a.`proTimeStamp`, a.`department`, a.`post`, a.`username`, a.`phone`, b.id AS `projectId`, d.*, b.`projectName`, c.build, c.floor, c.unitName, c.inspectItem FROM `tb_project_administrator` AS a, `tb_project` AS b, `tb_inspectaccept_measure` AS c, `tb_inspectaccept_notice` AS d WHERE 	a.`department` = '项目部' AND a.`userId` = '$userId' AND a.`proTimeStamp` = b.`timeStamp` AND b.`timeStamp` = c.`proTimeStamp` AND `c`.`id` = `d`.`measureId` AND c.`measureType` = '检查验收' ORDER BY c.id DESC";	
			}
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
	            $i=0;
	            while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['noticeTimeStamp'] = $row['noticeTimeStamp'];
	                $resData['noticeNumber'] = $row['noticeNumber'];
	                $resData['inspectLevel'] = $row['inspectLevel'];
	                $resData['inspectPosition'] = $row['inspectPosition'];
	                $resData['inspectItem'] = $row['inspectItemHead'];
	                $resData['projectName'] = $row['projectName'];
	                $resData['inspectDate'] = $row['inspectDate'];
					$resData['state'] = $row['state'];
					$resData['inspectPersonName'] = $row['inspectPersonName'];
					$resData['inspectTeam'] = $row['inspectTeam'];
					$resData['leaderName'] = $row['leaderName'];
					$resData['inspectSendPerson'] = $row['inspectSendPerson'];
					$resData['rectificationDate'] = $row['rectificationDate'];
					$resData['inspectDate'] = $row['inspectDate'];
					$resData['endDate'] = $row['endDate'];
					$res[$i] = $resData;
	                $i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
		break;
		//获取通知单附件信息
		case 'getNoticeDetail':
			$noticeTimeStamp = isset($_POST["noticeTimeStamp"])?$_POST["noticeTimeStamp"]:'';
			$sql = "SELECT * FROM tb_inspectaccept_violationitem WHERE noticeTimeStamp='$noticeTimeStamp'";
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
				$i=0;
				while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['itemType'] = $row['itemType'];
					$resData['violationContent'] = $row['violationContent'];
					$resData['picFile'] = $row['picFile'];
					$resData['replyPicFile'] = $row['replyPicFile'];
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
		break;
		//获取公司
		case 'getCompany':
			$sql = "SELECT * FROM tb_system_company";
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
				$i=0;
				while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['companyName'] = $row['companyName'];
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
		break;
		//获取工程
		case 'getProject':
			$companyId = isset($_POST["companyId"])?$_POST["companyId"]:'';
			$sql = "SELECT * FROM tb_project WHERE companyId='$companyId'";
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
				$i=0;
				while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['projectName'] = $row['projectName'];
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
		break;
		//获取分部工程
		case 'getItem':
			$sql = "SELECT * FROM tb_system_standard1 WHERE measurementType='检查验收' GROUP BY inspectItem";
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
				$i=0;
				while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['inspectionItem'] = $row['inspectionItem'];
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
		break;	
		//筛选
		case 'selectExcept':
			$companyId = isset($_POST["companyId"])?$_POST["companyId"]:'';
			$projectValue = isset($_POST["projectValue"])?$_POST["projectValue"]:'';
			$itemValue = isset($_POST["itemValue"])?$_POST["itemValue"]:'';
			$companyLevel = isset($_POST["companyLevel"])?$_POST["companyLevel"]:'';
			$startTime = isset($_POST["startTime"])?$_POST["startTime"]:'';
			$endTime = isset($_POST["endTime"])?$_POST["endTime"]:'';
			$userId = isset($_POST["userId"])?$_POST["userId"]:'';
			if($companyId==''){
				$str1 = "";
			}else{
				$str1 = "AND a.`companyId` ='".$companyId."'";
			}
			if($projectValue==''){
				$str2 = "";
			}else{
				$str2 = " AND a.`projectName` ='".$projectValue."'";
			}
			if($itemValue==''){
				$str3 = "";
			}else{
				$str3 = " AND b.`inspectItem` ='".$itemValue."'";
			}
			if($companyLevel=='总公司'){
				$sql = "SELECT c.*, b.`inspectItem`, b.`inspectDate`, a.`projectName`,b.`inspectPerson`, b.`inspectPosition` FROM `tb_inspectaccept_notice` AS c , `tb_inspectaccept_measure` AS b , `tb_project` AS a WHERE b.`proTimeStamp` = a.timeStamp ".$str2.$str3." AND b.`measureType`='检查验收' AND `b`.`id` = `c`.`measureId` AND b.`inspectDate` >= '$startTime' AND b.`inspectDate` <= '$endTime'  ORDER BY b.id DESC";
			}else if($companyLevel=='分公司'){
				$sql = "SELECT c.*, b.`inspectItem`, b.`inspectDate`, a.`projectName`,b.`inspectPerson`, b.`inspectPosition` FROM `tb_inspectaccept_notice` AS c , `tb_inspectaccept_measure` AS b , `tb_project` AS a WHERE b.`proTimeStamp` = a.timeStamp ".$str1.$str2.$str3." AND b.`measureType`='检查验收' AND `b`.`id` = `c`.`measureId` AND b.`inspectDate` >= '$startTime' AND b.`inspectDate` <= '$endTime'  ORDER BY b.id DESC";
			}else{//项目部
				$sql = "SELECT a.`proTimeStamp`, a.`department`, a.`post`, a.`username`, a.`phone`, f.id AS `projectId`, e.* FROM `tb_project_administrator` AS a , `tb_project` AS f , `tb_inspectaccept_measure` AS b, `tb_project_floor_information` AS d, `tb_inspectaccept_notice` AS e WHERE a.`department` = '项目部' AND a.`userId` = '$userId' AND a.`proTimeStamp` = f.`timeStamp` AND f.id = b.`projectId` AND `b`.`id` = `e`.`measureId` AND b.`measureType`='检查验收' ".$str3." AND d.section='$section' AND b.build='$build' GROUP BY id ORDER BY b.id DESC";
			}
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
				$i=0;
				while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['noticeTimeStamp'] = $row['noticeTimeStamp'];
					$resData['noticeNumber'] = $row['noticeNumber'];
					$resData['projectName'] = $row['projectName'];
					$resData['projectId'] = $row['projectId'];
					$resData['inspectItem'] = $row['inspectItemHead'];
					$resData['inspectDate'] = $row['inspectDate'];
					$resData['inspectPosition'] = $row['inspectPosition'];
					$resData['inspectLevel'] = $row['inspectLevel'];
					$resData['state'] = $row['state'];
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
		break;
		//获取当前分公司工程
		case 'getCurrentProject':
			$company = isset($_POST["company"])?$_POST["company"]:'';
			$sql = "SELECT * FROM tb_project WHERE company='$company'";
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
				$i=0;
				while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['projectName'] = $row['projectName'];
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
		break;
		
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	
?>
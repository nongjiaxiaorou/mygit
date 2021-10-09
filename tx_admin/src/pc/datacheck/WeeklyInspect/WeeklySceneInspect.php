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
					$sql = "SELECT a.*, b.company FROM tb_weekly_scene_notice AS a , tb_project AS b WHERE a.projectId = b.id ORDER BY a.id DESC";
				}else{
					$sql = "SELECT a.*, b.company FROM tb_weekly_scene_notice AS a , tb_project AS b WHERE a.projectId = b.id AND b.company = '$company' ORDER BY a.id DESC";
				}
			}else{
				$sql = "SELECT 	a.`proTimeStamp`, a.`department`, a.`post`, a.`username`, a.`phone`, b.id AS `projectId`, b.`projectName`, c.* FROM `tb_project_administrator` AS a, `tb_project` AS b, `tb_weekly_scene_notice` AS c WHERE a.`department` = '项目部' AND a.`userId` = '$userId'  AND a.`proTimeStamp` = b.`timeStamp` AND b.`id` = c.`projectId`  ORDER BY c.id DESC";	
			}
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
	            $i=0;
	            while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['noticeTimeStamp'] = $row['timeStamp'];
	                $resData['inspectCode'] = $row['inspectCode'];
	                $resData['inspectLevel'] = $row['inspectLevel'];
	                $resData['inspectPosition'] = $row['section'].$row['build'].$row['floor'].$row['unit'];
	                $resData['inspectUnit'] = $row['inspectUnit'];
	                $resData['inspectObj'] = $row['inspectObj'];
	                $resData['inspectDate'] = $row['inspectDate'];
	                $resData['noticeState'] = $row['noticeState'];
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
//			$noticeTimeStamp = '1606310749602';
			$sql = "SELECT * FROM tb_weekly_scene_notice WHERE timeStamp='$noticeTimeStamp'";
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
				$i=0;
				while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['inspectCode'] = $row['inspectCode'];
					$resData['projectName'] = $row['projectName'];
					$resData['inspectLevel'] = $row['inspectLevel'];
	                $resData['inspectPosition'] = $row['section'].$row['build'].$row['floor'].$row['unit'];
					$resData['majorCate'] = $row['majorCate'];
					$resData['inspectUnit'] = $row['inspectUnit'];
					$resData['inspectCate'] = $row['inspectCate'];
					$resData['noticeState'] = $row['noticeState'];
					$resData['inspectDate'] = $row['inspectDate'];
					$resData['endTime'] = $row['endTime'];
					
					$sql1 = "SELECT defect, defectPicture, replyPicture FROM tb_weekly_scene_notice_defect WHERE `timeStamp` = '$noticeTimeStamp'";
					$result1 = $conn->query($sql1);
					if($result1->num_rows>0)	{
						$j=0;
						while($row1 = $result1->fetch_assoc()){
//							echo $row1['defect'];
							$resData['defect'][$j] = $row1['defect'];
							$resData['defectPicture'][$j] = $row1['defectPicture'];
							$resData['replyPicture'][$j] = $row1['replyPicture'];
							
							$j++;
						}
					}
					$res = $resData;
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
				$str1 = "AND b.`companyId` ='".$companyId."'";
			}
			if($projectValue==''){
				$str2 = "";
			}else{
				$str2 = " AND a.`projectName` ='".$projectValue."'";
			}
			if($companyLevel=='总公司'){
				$sql = "SELECT a.*, b.company FROM tb_weekly_scene_notice AS a,tb_project AS b  WHERE a.projectId = b.id ".$str1.$str2."  AND a.inspectDate >= '$startTime' AND a.inspectDate <= '$endTime' ORDER BY a.id DESC";
			}else if($companyLevel=='总公司'){
				$sql = "SELECT a.*, b.company FROM tb_weekly_scene_notice AS a,tb_project AS b  WHERE a.projectId = b.id ".$str1.$str2."  AND a.inspectDate >= '$startTime' AND a.inspectDate <= '$endTime' ORDER BY a.id DESC";
			}else{//项目部
				$sql = "SELECT 	a.`proTimeStamp`, a.`department`, a.`post`, a.`username`, a.`phone`, b.id AS `projectId`, b.`projectName`, c.* FROM `tb_project_administrator` AS a, `tb_project` AS b, `tb_weekly_scene_notice` AS c WHERE a.`department` = '项目部' AND a.`userId` = '$userId'  AND a.`proTimeStamp` = b.`timeStamp` AND b.`id` = c.`projectId` AND a.inspectDate >= '$startTime' AND a.inspectDate <= '$endTime' ORDER BY c.id DESC";
			}
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
				$i=0;
				while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['noticeTimeStamp'] = $row['timeStamp'];
	                $resData['inspectCode'] = $row['inspectCode'];
	                $resData['inspectLevel'] = $row['inspectLevel'];
	                $resData['inspectPosition'] = $row['section'].$row['build'].$row['floor'].$row['unit'];
	                $resData['inspectUnit'] = $row['inspectUnit'];
	                $resData['inspectObj'] = $row['inspectObj'];
	                $resData['inspectDate'] = $row['inspectDate'];
	                $resData['noticeState'] = $row['noticeState'];
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
		break;
		//获取资料照片
		case 'getFilePicture':
			$noticeTimeStamp = isset($_POST["noticeTimeStamp"])?$_POST["noticeTimeStamp"]:'';
			
			$sql = "SELECT * FROM tb_weekly_scene_notice_filepic WHERE noticeTimeStamp = '$noticeTimeStamp'";
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
				$i=0;
				while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['scenePic'] = $row['scenePic'];
					$resData['meetingPic'] = $row['meetingPic'];
					$resData['summaryPic'] = $row['summaryPic'];
					$resData['otherPic'] = $row['otherPic'];
					$res = $resData;
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
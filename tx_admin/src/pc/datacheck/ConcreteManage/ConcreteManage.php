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
		//获取浇筑通知单数据
		case 'getNoticeData':
			$company = isset($_POST["company"])?$_POST["company"]:'';
			$userId = isset($_POST["userId"])?$_POST["userId"]:'';
			$level = isset($_POST["level"])?$_POST["level"]:'';
			if($level=="总公司"||$level=="分公司"){
				if($level=="总公司"){
			$sql = "SELECT b.* FROM `tb_concrete_pour` AS b , `tb_project` AS a WHERE b.`projectId` = a.id  ORDER BY b.id DESC";
				}else{
					$sql = "SELECT b.* FROM `tb_concrete_pour` AS b , `tb_project` AS a WHERE b.`projectId` = a.id AND b.`company`='$company'  ORDER BY b.id DESC";
				}
			}else{
				$sql = "SELECT  c.* FROM `tb_project_administrator` AS a , `tb_project` AS b , `tb_concrete_pour` AS c WHERE a.`department` = '项目部' AND a.`userId` = '$userId' AND a.`proTimeStamp` = b.`timeStamp` AND b.id = c.`projectId`  ORDER BY c.id DESC";
			}
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
	            $i=0;
	            while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['projectName'] = $row['projectName'];
	                $resData['concreteGrade'] = $row['concreteGrade'];
	                $resData['pouringVolume'] = $row['pouringVolume'];
	                $resData['pouringPosition'] = $row['pouringPosition'];
	                $resData['pouringDate'] = $row['pouringDate'];
	                $resData['sideDate'] = $row['sideDate'];
	                $resData['bystander'] = $row['bystander'];
					$resData['proState'] = $row['proState'];
					$resData['completeDate'] = $row['completeDate'];
					$resData['builder'] = $row['builder'];
					
					$resData['poured_describe'] = $row['poured_describe'];
					$resData['poured_img'] = $row['poured_img'];
					
					$resData['pouring_describe'] = $row['pouring_describe'];
					$resData['pouring_img'] = $row['pouring_img'];
					
					$resData['pour_describe'] = $row['pour_describe'];
					$resData['pour_img'] = $row['pour_img'];
					$res[$i] = $resData;
	                $i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
		break;
		//获取拆模通知单数据
		case 'getNoticeData1':
			$company = isset($_POST["company"])?$_POST["company"]:'';
			$userId = isset($_POST["userId"])?$_POST["userId"]:'';
			$level = isset($_POST["level"])?$_POST["level"]:'';
			if($level=="总公司"||$level=="分公司"){
				if($level=="总公司"){
			$sql = "SELECT b.* FROM `tb_concrete_removal` AS b , `tb_project` AS a WHERE b.`projectId` = a.id  ORDER BY b.id DESC";
				}else{
					$sql = "SELECT b.* FROM `tb_concrete_removal` AS b , `tb_project` AS a WHERE b.`projectId` = a.id AND b.`company`='$company'  ORDER BY b.id DESC";
				}
			}else{
				$sql = "SELECT  c.* FROM `tb_project_administrator` AS a , `tb_project` AS b , `tb_concrete_removal` AS c WHERE a.`department` = '项目部' AND a.`userId` = '$userId' AND a.`proTimeStamp` = b.`timeStamp` AND b.id = c.`projectId`  ORDER BY c.id DESC";
			}
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
	            $i=0;
	            while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['projectName'] = $row['projectName'];
	                $resData['concreteGrade'] = $row['concreteGrade'];
	                $resData['pouringDate'] = $row['pouringDate'];
	                $resData['removalPosition'] = $row['removalPosition'];
					$resData['proState'] = $row['proState'];
					$resData['completeDate'] = $row['completeDate'];
					$resData['createDate'] = $row['createDate'];
					
					$resData['removal_describe'] = $row['removal_describe'];
					$resData['removal_img'] = $row['removal_img'];
					$res[$i] = $resData;
	                $i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
		break;
		//获取通知单附件信息
		case 'getnoticeDetail':
			$id = isset($_POST["id"])?$_POST["id"]:'';
			$sql = "SELECT * FROM tb_concrete_sign WHERE cardid ='$id'";
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
				$i=0;
				while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['sign1'] = $row['sign1'];
					$resData['sign2'] = $row['sign2'];
					$resData['idea1'] = $row['idea1'];
					$resData['idea2'] = $row['idea2'];
					$resData['datetime1'] = $row['datetime1'];
					$resData['datetime2'] = $row['datetime2'];
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
			if($companyLevel=='总公司'){
				$sql = "SELECT b.* FROM `tb_concrete_pour` AS b , `tb_project` AS a WHERE b.`projectId` = a.id  AND b.`createDate` >= '$startTime' AND b.`createDate` <= '$endTime' ".$str1.$str2."  ORDER BY b.id DESC";
			}else if($companyLevel=='分公司'){
				$sql = "SELECT b.* FROM `tb_concrete_pour` AS b , `tb_project` AS a WHERE b.`projectId` = a.id AND b.`company`='$company' AND b.`createDate` >= '$startTime' AND b.`createDate` <= '$endTime' ".$str1.$str2."  ORDER BY b.id DESC";
			}else{//项目部
				$sql = "SELECT  c.* FROM `tb_project_administrator` AS b , `tb_project` AS a , `tb_concrete_pour` AS c WHERE b.`department` = '项目部' AND b.`userId` = '$userId' AND b.`proTimeStamp` = a.`timeStamp` AND a.id = c.`projectId` AND c.`createDate` >= '$startTime' AND c.`createDate` <= '$endTime' ".$str1.$str2."   ORDER BY c.id DESC";
			}
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
				$i=0;
				while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['projectName'] = $row['projectName'];
	                $resData['concreteGrade'] = $row['concreteGrade'];
	                $resData['pouringVolume'] = $row['pouringVolume'];
	                $resData['pouringPosition'] = $row['pouringPosition'];
	                $resData['pouringDate'] = $row['pouringDate'];
	                $resData['sideDate'] = $row['sideDate'];
	                $resData['bystander'] = $row['bystander'];
					$resData['proState'] = $row['proState'];
					$resData['completeDate'] = $row['completeDate'];
					$resData['builder'] = $row['builder'];
					
					$resData['poured_describe'] = $row['poured_describe'];
					$resData['poured_img'] = $row['poured_img'];
					
					$resData['pouring_describe'] = $row['pouring_describe'];
					$resData['pouring_img'] = $row['pouring_img'];
					
					$resData['pour_describe'] = $row['pour_describe'];
					$resData['pour_img'] = $row['pour_img'];
					$res[$i] = $resData;
	                $i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
		break;
		
		case 'selectExcept1':
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
			if($companyLevel=='总公司'){
				$sql = "SELECT b.* FROM `tb_concrete_removal` AS b , `tb_project` AS a WHERE b.`projectId` = a.id  AND b.`createDate` >= '$startTime' AND b.`createDate` <= '$endTime' ".$str1.$str2."  ORDER BY b.id DESC";
			}else if($companyLevel=='分公司'){
				$sql = "SELECT b.* FROM `tb_concrete_removal` AS b , `tb_project` AS a WHERE b.`projectId` = a.id AND b.`company`='$company' AND b.`createDate` >= '$startTime' AND b.`createDate` <= '$endTime' ".$str1.$str2."  ORDER BY b.id DESC";
			}else{//项目部
				$sql = "SELECT  c.* FROM `tb_project_administrator` AS b , `tb_project` AS a , `tb_concrete_removal` AS c WHERE b.`department` = '项目部' AND b.`userId` = '$userId' AND b.`proTimeStamp` = a.`timeStamp` AND a.id = c.`projectId` AND c.`createDate` >= '$startTime' AND c.`createDate` <= '$endTime' ".$str1.$str2."   ORDER BY c.id DESC";
			}
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
				$i=0;
				while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['projectName'] = $row['projectName'];
	                $resData['concreteGrade'] = $row['concreteGrade'];
	                $resData['pouringDate'] = $row['pouringDate'];
	                $resData['removalPosition'] = $row['removalPosition'];
					$resData['proState'] = $row['proState'];
					$resData['completeDate'] = $row['completeDate'];
					$resData['createDate'] = $row['createDate'];
					
					$resData['removal_describe'] = $row['removal_describe'];
					$resData['removal_img'] = $row['removal_img'];
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
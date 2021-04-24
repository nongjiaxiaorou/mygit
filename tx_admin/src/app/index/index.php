<?php
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	require ("../../../conn.php");
	error_reporting(0);
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	$username = isset($_POST["username"]) ? $_POST["username"] : '';
	$phone = isset($_POST["phone"]) ? $_POST["phone"] : '';
	$company = isset($_POST["company"]) ? $_POST["company"] : '';
	$userId = isset($_POST["userId"]) ? $_POST["userId"] : '';
	$userInfo = $username . '/' . $phone . '/' . $company . '/' . $userId;
	
	$branchCompany = '';//分公司名称
	$projectDept = '';//项目部
	$projectNum = 0;//项目总数
	/*总公司*/
	$headComNum = 0;//总公司数目
	$headCompany = '';//总公司名称
	//搜索是否有在总公司栏位
	$sql1_1 = "SELECT count(id) AS num1,proTimeStamp FROM `tb_project_fixedpost` WHERE qualityManHead = '$userInfo' OR technicalLead = '$userInfo' OR quailtyInspector = '$userInfo'";
//	echo $sql1_1;
	$res1_1 = $conn -> query($sql1_1);
	$row1_1 = $res1_1 -> fetch_assoc();
	if ($row1_1["num1"] > 0) {
		$headComNum = $row1_1["num1"];
		$proTimeStamp = $row1_1["proTimeStamp"];
		
		$sql1_2 = "SELECT company FROM `tb_project` WHERE timeStamp = '$proTimeStamp'";
		$res1_2 = $conn -> query($sql1_2);
		$row1_2 = $res1_2 -> fetch_assoc();
		$headCompany = $row1_2['company'];
	}
	if ($headComNum == 0) {
		//在自定义管理人员处查找总公司层级
		$sql1_3 = "SELECT COUNT(id) AS num1_2,company FROM `tb_project_administrator` WHERE department = '总公司' AND userId = '$userId'";
		$res1_3 = $conn -> query($sql1_3);
		$row1_3 = $res1_3 -> fetch_assoc();
		if ($row1_3['num1_2'] > 0) {
			$headComNum = 1;
			$headCompany = $row1_3['company'];
		} else {
			/*分公司*/
			//搜索是否有在分公司栏位
			$sql2_1 = "SELECT * from `tb_project_fixedpost` where chiefEngineer = '$userInfo' OR qualityManBranch = '$userInfo' OR areaLead = '$userInfo'";
			$res2_1 = $conn -> query($sql2_1);
			if ($res2_1 -> num_rows > 0) {
				$row2_1 = $res2_1 -> fetch_assoc();
				$proTimeStamp = $row2_1["proTimeStamp"];
				$sql2_2 = "SELECT company from `tb_project` where timeStamp='" . $proTimeStamp . "'";
				$res2_2 = $conn -> query($sql2_2);
				if ($res2_2) {
					$row2_2 = $res2_2 -> fetch_assoc();
					$branchCompany = $row2_2["company"];
				}
			} else {
				//在自定义管理人员处查找分公司层级
				$sql2_3 = "SELECT * FROM `tb_project_administrator` WHERE department = '分公司' AND userId = '$userId'";
				$res2_3 = $conn -> query($sql2_3);
				if ($res2_3 -> num_rows > 0) {
					$row2_3 = $res2_3 -> fetch_assoc();
					$branchCompany = $row2_3["company"];
				}
			}
		}
	}
	/*项目部*/
	if ($headComNum == 0 && $branchCompany == null) {
		//搜索是否有在项目部栏位
		$sql3_1 = "SELECT * FROM `tb_project_fixedpost` WHERE proManager = '$userInfo' OR proChief = '$userInfo' OR produceManager = '$userInfo' ORDER BY
			tb_project_fixedpost.id DESC";
		$res3_1 = $conn -> query($sql3_1);
		if ($res3_1 -> num_rows > 0) {
			$row3_1 = $res3_1 -> fetch_assoc();
			$proTimeStamp = $row3_1["proTimeStamp"];
			$sql3_2 = "SELECT * from `tb_project` where timeStamp='" . $proTimeStamp . "'";
			$res3_2 = $conn -> query($sql3_2);
			if ($res3_2) {
				$row3_2 = $res3_2 -> fetch_assoc();
				$projectDept = $row3_2["projectName"];
				$projectId = $row3_2["id"];
				$database = $row3_2["violationStandard"];
				$timeStamp = $row3_2["timeStamp"];
			}
		}
		//在自定义管理人员处查找项目部层级
		$sql3_3 = "SELECT * FROM `tb_project_administrator` WHERE department = '项目部' AND userId = '$userId'";
		$res3_3 = $conn -> query($sql3_3);
		if ($res3_3 -> num_rows > 0) {
			$row3_3 = $res3_3 -> fetch_assoc();
			$proTimeStamp = $row3_3["proTimeStamp"];
			$sql3_4 = "SELECT * from `tb_project` where timeStamp='" . $proTimeStamp . "'";
			$res3_4 = $conn -> query($sql3_4);
			if ($res3_4) {
				$row3_4 = $res3_4 -> fetch_assoc();
				$projectDept = $row3_4["projectName"];
				$projectId = $row3_4["id"];
				$database = $row3_4["violationStandard"];
				$timeStamp = $row3_4["timeStamp"];
			}
		}
	}
	
	//项目总数
	$sql = "SELECT Count(tb_project.id) as projectNum FROM tb_project";
	$res = $conn -> query($sql);
	$row = $res -> fetch_assoc();
	$projectNum = $row['projectNum'];
	$ret_data['projectNum'] = $projectNum;
	$ret_data['projectId'] = $projectId;
	$ret_data['database'] = $database;
	$ret_data['timeStamp'] = $timeStamp;
	switch($flag) {
		//获取层级
		case 'getLevel' :
			if($headComNum!=0){
				$ret_data['data']='总公司|'.$headCompany;
			}else if($headComNum==0&&$branchCompany!=null){
				$ret_data['data']='分公司|'.$branchCompany;				
			}else if($headComNum ==0&&$branchCompany==null){
				$ret_data['data']='项目部|'.$projectDept;
			}
			break;
	}
	$conn -> close();
	$json = json_encode($ret_data);
	echo $json;
	
?>
<?php
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	require ("../../../conn.php");
//	error_reporting(0);
	//设置时区东八区
	date_default_timezone_set('PRC');
	$today = date("Y-m-d");
	function get_yesterday_date() {
	 $yesterday = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
	 return date("Y-m-d", $yesterday);
	}
	$yesterday = get_yesterday_date();
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag){
		case '项目部':
			$projectId = isset($_POST["projectId"])?$_POST["projectId"] : '';
			//隐患累计数------------
			$sql1="SELECT COUNT(*) AS lateDanger_num FROM `view_hiddendanger` WHERE `projectId` = '$projectId'  AND `state` = '整改中' AND  `endDate`<'$today' ";
			$result1 = $conn->query($sql1);  
			$row1 = $result1->fetch_assoc();
			
			$sql2="SELECT COUNT(*) AS majorDanger_num FROM `view_hiddendanger` WHERE `projectId` = '$projectId'  AND `state` = '整改中' and `itemType` = '重大问题' ";
			$result2 = $conn->query($sql2);  
			$row2 = $result2->fetch_assoc();
			
			//整改闭合累计总数
			$sql3="SELECT COUNT(*) AS Rectification_num FROM `view_rectificationclose` WHERE `projectId` = '$projectId' ";
			$result3 = $conn->query($sql3);  
			$row3 = $result3->fetch_assoc();


			
			//今日逾期隐患新增数------------
			$sql11="SELECT COUNT(*) AS lateDanger_num1 FROM `view_hiddendanger` WHERE `projectId` = '$projectId'  AND `state` = '整改中'  AND  `endDate`>='$yesterday' AND `endDate`<'$today'";
			$result11 = $conn->query($sql11);  
			$row11 = $result11->fetch_assoc();
			
			//今日重大隐患新增数------------
			$sql22="SELECT COUNT(*) AS majorDanger_num1 FROM `view_hiddendanger` WHERE `projectId` = '$projectId'  AND `state` = '整改中' and `itemType` = '重大问题' AND  `endDate`>='$yesterday' AND `endDate`<'$today' ";
			$result22 = $conn->query($sql22);  
			$row22 = $result22->fetch_assoc();
			
			//今日整改卡片新增数------------
			$sql33="SELECT COUNT(*) AS Rectification_num1 FROM `view_rectificationclose` WHERE `projectId` = '$projectId' AND `rectificationDate` >='$yesterday' AND `rectificationDate` <='$today' ";
			$result33 = $conn->query($sql33);  
			$row33 = $result33->fetch_assoc();
			
			/*获取每周巡检指标*/
			$tody = date("Y-m-d");
			$tomorrow = date("Y-m-d",strtotime("+1 day"));
			//现场质量巡检累计数量
			$sql4="SELECT * FROM tb_weekly_scene_notice WHERE  noticeState = '整改中'"; 
			$result4 = $conn->query($sql4);
			$sceneTotal = $result4->num_rows;//现在质量巡检整改中累计数目
				
			//现场质量巡检今天数量
			$sql44="SELECT * FROM tb_weekly_scene_notice WHERE noticeState = '整改中' AND issueTime > '$tody' AND issueTime < '$tomorrow'"; 
			$result44 = $conn->query($sql44);
			$sceneTody = $result44->num_rows;//现在质量巡检整改中累计数目
				
			$sqldate = '{"逾期隐患":"'. $row1["lateDanger_num"].'|'.$row11["lateDanger_num1"].'","重大隐患":"'. $row2["majorDanger_num"].'|'.$row22["majorDanger_num1"].'","每周巡检":"'. $sceneTotal.'|'.$sceneTody.'","整改闭合":"'. $row3["Rectification_num"].'|'.$row33["Rectification_num1"].'"}';
	//		$conn -> close();
			echo json_encode($sqldate);//
		break;
		
		case '总公司':
			//隐患累计数------------
			$sql1="SELECT COUNT(*) AS lateDanger_num FROM `view_hiddendanger` WHERE  `state` = '整改中' AND  `endDate`<'$today' ";
			$result1 = $conn->query($sql1);  
			$row1 = $result1->fetch_assoc();
			
			$sql2="SELECT COUNT(*) AS majorDanger_num FROM `view_hiddendanger` WHERE  `state` = '整改中' and `itemType` = '重大问题' ";
			$result2 = $conn->query($sql2);  
			$row2 = $result2->fetch_assoc();
			
			//整改闭合累计总数
			$sql3="SELECT COUNT(*) AS Rectification_num FROM `view_rectificationclose`  ";
			$result3 = $conn->query($sql3);  
			$row3 = $result3->fetch_assoc();


			
			//今日逾期隐患新增数------------
			$sql11="SELECT COUNT(*) AS lateDanger_num1 FROM `view_hiddendanger` WHERE  `state` = '整改中'  AND  `endDate`>='$yesterday' AND `endDate`<'$today'";
			$result11 = $conn->query($sql11);  
			$row11 = $result11->fetch_assoc();
			
			//今日重大隐患新增数------------
			$sql22="SELECT COUNT(*) AS majorDanger_num1 FROM `view_hiddendanger` WHERE  `state` = '整改中' and `itemType` = '重大问题' AND  `endDate`>='$yesterday' AND `endDate`<'$today' ";
			$result22 = $conn->query($sql22);  
			$row22 = $result22->fetch_assoc();
			
			//今日整改卡片新增数------------
			$sql33="SELECT COUNT(*) AS Rectification_num1 FROM `view_rectificationclose` WHERE  `rectificationDate` >='$yesterday' AND `rectificationDate` <='$today' ";
			$result33 = $conn->query($sql33);  
			$row33 = $result33->fetch_assoc();
			
			$sqldate = '{"逾期隐患":"'. $row1["lateDanger_num"].'|'.$row11["lateDanger_num1"].'","重大隐患":"'. $row2["majorDanger_num"].'|'.$row22["majorDanger_num1"].'","每周巡检":"'. $row3["Rectification_num"].'|'.$row33["Rectification_num1"].'","整改闭合":"'. $row3["Rectification_num"].'|'.$row33["Rectification_num1"].'"}';
	//		$conn -> close();
			echo json_encode($sqldate);//
		break;
			
		case '分公司':
			$company = isset($_POST["company"])?$_POST["company"] : '';
			//隐患累计数------------
			$sql1="SELECT COUNT(*) AS lateDanger_num FROM `view_hiddendanger` WHERE `company` = '$company'  AND `state` = '整改中' AND  `endDate`<'$today' ";
			$result1 = $conn->query($sql1);  
			$row1 = $result1->fetch_assoc();
			
			$sql2="SELECT COUNT(*) AS majorDanger_num FROM `view_hiddendanger` WHERE `company` = '$company'  AND `state` = '整改中' and `itemType` = '重大问题' ";
			$result2 = $conn->query($sql2);  
			$row2 = $result2->fetch_assoc();
			
			//整改闭合累计总数
			$sql3="SELECT COUNT(*) AS Rectification_num FROM `view_rectificationclose` WHERE `company` = '$company' ";
			$result3 = $conn->query($sql3);  
			$row3 = $result3->fetch_assoc();


			
			//今日逾期隐患新增数------------
			$sql11="SELECT COUNT(*) AS lateDanger_num1 FROM `view_hiddendanger` WHERE `company` = '$company'  AND `state` = '整改中'  AND  `endDate`>='$yesterday' AND `endDate`<'$today'";
			$result11 = $conn->query($sql11);  
			$row11 = $result11->fetch_assoc();
			
			//今日重大隐患新增数------------
			$sql22="SELECT COUNT(*) AS majorDanger_num1 FROM `view_hiddendanger` WHERE `company` = '$company'  AND `state` = '整改中' and `itemType` = '重大问题' AND  `endDate`>='$yesterday' AND `endDate`<'$today' ";
			$result22 = $conn->query($sql22);  
			$row22 = $result22->fetch_assoc();
			
			//今日整改卡片新增数------------
			$sql33="SELECT COUNT(*) AS Rectification_num1 FROM `view_rectificationclose` WHERE `company` = '$company' AND `rectificationDate` >='$yesterday' AND `rectificationDate` <='$today' ";
			$result33 = $conn->query($sql33);  
			$row33 = $result33->fetch_assoc();
			
			$sqldate = '{"逾期隐患":"'. $row1["lateDanger_num"].'|'.$row11["lateDanger_num1"].'","重大隐患":"'. $row2["majorDanger_num"].'|'.$row22["majorDanger_num1"].'","每周巡检":"'. $row3["Rectification_num"].'|'.$row33["Rectification_num1"].'","整改闭合":"'. $row3["Rectification_num"].'|'.$row33["Rectification_num1"].'"}';
	//		$conn -> close();
			echo json_encode($sqldate);//
		break;
	}

?>
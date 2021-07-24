<?php
	require ("../../../conn.php");
	error_reporting(E_ALL ^ E_NOTICE);
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	date_default_timezone_set('PRC'); //东八时区 
	$date = date("Y-m-d H:i:s");
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag){
		case 'allPerson':
	//		$proTimeStamp='1601172808881';
			$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';
			$sql = "SELECT `proTimeStamp`,`proManager`,`proChief`,`produceManager` FROM `tb_project_fixedpost` WHERE proTimeStamp = '$proTimeStamp'";
			$result = $conn->query($sql);
			if($result->num_rows>0){
				$row = $result->fetch_assoc();
				$sqli = "SELECT `username`,`phone`,`userId`,`post` FROM `tb_project_administrator` WHERE `proTimeStamp`='$proTimeStamp' AND `department`='项目部'";
				$res = $conn->query($sqli);
				if($res->num_rows>0){
					$data = array();
					$i=0;
					while($rows=$res->fetch_assoc()){
						if($rows["post"]=="栋号长"||$rows["post"]=="施工员"||$rows["post"]=="质量员"){
							$data[$i] = '{"'.$rows["username"].'|'.$rows["phone"].'|'.$rows["post"].'":"'.$rows["userId"].'"}';
							$i++;
						}
					}
	
					if($row["proManager"]){
						$proManager = explode('/',$row["proManager"]);//项目经理
						$data[$i+1]= '{"'.$proManager[0].'|'.$proManager[1].'|项目经理":"'.$proManager[3].'"}';
					}
	
					if($row["proChief"]){
						$proChief = explode('/',$row["proChief"]);//项目总工
						$data[$i+2]= '{"'.$proChief[0].'|'.$proChief[1].'|项目总工":"'.$proChief[3].'"}';
					}
					
					if($row["produceManager"]){
						$produceManager = explode('/',$row["produceManager"]);//生产经理
						$data[$i+3]= '{"'.$produceManager[0].'|'.$produceManager[1].'|生产经理":"'.$produceManager[3].'"}';
					}
				}
				echo json_encode($data);
			}
		break;
		
		//公司层级获取人员
		case 'allComPerson':
	//		$proTimeStamp='1601172808881';
			$sql = "SELECT `proTimeStamp`,`proManager`,`proChief`,`produceManager` FROM `tb_project_fixedpost` ";
			$result = $conn->query($sql);
			if($result->num_rows>0){
				$row = $result->fetch_assoc();
				$sqli = "SELECT `username`,`phone`,`userId`,`post` FROM `tb_project_administrator` ";
				$res = $conn->query($sqli);
				if($res->num_rows>0){
					$data = array();
					$i=0;
					while($rows=$res->fetch_assoc()){
						if($rows["post"]=="栋号长"||$rows["post"]=="施工员"||$rows["post"]=="质量员"){
							$data[$i] = '{"'.$rows["username"].'|'.$rows["phone"].'|'.$rows["post"].'":"'.$rows["userId"].'"}';
							$i++;
						}
					}
	
					if($row["proManager"]){
						$proManager = explode('/',$row["proManager"]);//项目经理
						$data[$i+1]= '{"'.$proManager[0].'|'.$proManager[1].'|项目经理":"'.$proManager[3].'"}';
					}
	
					if($row["proChief"]){
						$proChief = explode('/',$row["proChief"]);//项目总工
						$data[$i+2]= '{"'.$proChief[0].'|'.$proChief[1].'|项目总工":"'.$proChief[3].'"}';
					}
					
					if($row["produceManager"]){
						$produceManager = explode('/',$row["produceManager"]);//生产经理
						$data[$i+3]= '{"'.$produceManager[0].'|'.$produceManager[1].'|生产经理":"'.$produceManager[3].'"}';
					}
				}
				echo json_encode($data);
			}
		break;
	}
	

?>
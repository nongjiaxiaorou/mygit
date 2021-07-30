<?php
	require ("../../../../conn.php");
	error_reporting(E_ALL ^ E_NOTICE);
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	date_default_timezone_set('PRC'); //东八时区 
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	
	switch($flag){
		case 'allPerson':
			$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';
			$sql = "SELECT `proTimeStamp`,`proManager`,`proChief`,`produceManager` FROM `tb_project_fixedpost` WHERE proTimeStamp = '$proTimeStamp'";
			$result = $conn->query($sql);
			if($result->num_rows>0){
				$row = $result->fetch_assoc();
				$sqli = "SELECT `username`,`phone`,`userId`,`post` FROM `tb_project_administrator` WHERE `proTimeStamp`='$proTimeStamp' AND `department`='项目部'";
				$res = $conn->query($sqli);
//				$res = array();
				$resData = array();
				$i=0;
				if($res->num_rows>0){
					while($rows=$res->fetch_assoc()){
						if($rows["post"]=="栋号长"||$rows["post"]=="施工员"||$rows["post"]=="质量员"){
							$resData[$i] = $rows["username"].'|'.$rows["phone"].'|'.$rows["post"].'|'.$rows["userId"];
							$i++;
						}
					}
					$proManager = explode('/',$row["proManager"]);//项目经理
					$proChief = explode('/',$row["proChief"]);//项目总工
					$produceManager = explode('/',$row["produceManager"]);//生产经理
					$resData[$i+1]= $proManager[0].'|'.$proManager[1].'|项目经理|'.$proManager[3];
					$resData[$i+2]= $proChief[0].'|'.$proChief[1].'|项目总工|'.$proChief[3];
					$resData[$i+3]= $produceManager[0].'|'.$produceManager[1].'|生产经理|'.$produceManager[3];
					$data['data'] = $resData;
				}
			}else{
				$data['code'] = 0;
			}
		break;
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	
	

?>
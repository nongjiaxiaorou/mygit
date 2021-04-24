<?php
	require ("../../../../conn.php");
	error_reporting(E_ALL ^ E_NOTICE);
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	//生成时间戳
	function msectime() {
   		list($msec, $sec) = explode(' ', microtime());
   		return  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
	}
	
	switch($flag){
		case 'getProblemDetail':
			$startTime = isset($_POST["startTime"]) ? $_POST["startTime"] : '';
			$endTime = isset($_POST["endTime"]) ? $_POST["endTime"] : '';
			// $build = isset($_POST["build"]) ? $_POST["build"] : '';
			// $floor = isset($_POST["floor"]) ? $_POST["floor"] : '';
			// $part = isset($_POST["part"]) ? $_POST["part"] : '';
			$title = isset($_POST["title"]) ? $_POST["title"] : '';
			// $floorInfo=$build.$floor.$part;
			// if($build==""){
			// $str1 = "";
			// }else{
			// 	$str1 = "AND inspectPosition LIKE '%".$build."%'";
			// }
			// if($floor==""&&$part==""){
				
			// }else{
			// 	$str1 = " AND inspectPosition LIKE '%".$floorInfo."%'";
			// }
			if($startTime==''&&$endTime==''){
				$str2 = "";
			}else{
				$str2 = "AND `rectificationDate` >='$startTime' AND `rectificationDate` <='$endTime'";
			}
			$sql = "SELECT * FROM `view_hiddendanger` where  projectName = '$title' ".$str2."  ";
			$result = $conn->query($sql);
			if($result->num_rows>0){
				$res = array();
				$resData = array();
				$i=0;
				while($row=$result->fetch_assoc()){
					$resData['id'] = $row["id"];
					$resData['inspectPosition'] = $row["inspectPosition"];
					$resData['inspectItemHead'] = $row["inspectItemHead"];
					$resData['violationContent'] = $row["violationContent"];
					$resData['itemType'] = $row["itemType"];
					$resData['state'] = $row["state"];
					$resData['rectificationDate'] = $row["rectificationDate"];
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
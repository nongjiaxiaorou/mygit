<?php
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	require ("../../../conn.php");
	error_reporting(0);
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag) {
		//获取区段栋号
		case 'getSectionBuild':
			$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';
			
			$sql = "SELECT a.projectName, a.section, GROUP_CONCAT(a.build) build, GROUP_CONCAT(a.unitName) unitName, GROUP_CONCAT(a.undergroundNumber) undergroundNumber, GROUP_CONCAT(a.abovegroundNumber) abovegroundNumber FROM txzlgl.tb_project_floor_information AS a WHERE a.`timeStamp` = '$proTimeStamp' GROUP BY a.section ORDER BY a.id ASC";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$ret_data["section"][$i] = $row["section"];
					$ret_data["build"][$i] = $row["build"];
					$ret_data["unitName"][$i] = $row["unitName"];
					$ret_data["undergroundNumber"][$i] = $row["undergroundNumber"];
					$ret_data["abovegroundNumber"][$i] = $row["abovegroundNumber"];
					$i++;	
				}
			}
		break;
		//根据栋号异步获取楼层单元
		case 'getFloorUnit':
			$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';
			$build = isset($_POST["build"]) ? $_POST["build"] : '';
			
			$sql = "SELECT a.projectName, a.unitName, a.undergroundNumber, a.abovegroundNumber FROM txzlgl.tb_project_floor_information AS a WHERE a.`timeStamp` = '$proTimeStamp' AND a.build = '$build' ";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$ret_data["unitName"][$i] = $row["unitName"];
					$ret_data["undergroundNumber"][$i] = $row["undergroundNumber"];
					$ret_data["abovegroundNumber"][$i] = $row["abovegroundNumber"];
					$i++;	
				}
			}
		break;
		case 'getFloorPic':
			$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';
			$section = isset($_POST["section"]) ? $_POST["section"] : '';
			$buildNum = isset($_POST["buildNum"]) ? $_POST["buildNum"] : '';
			$floor = isset($_POST["floor"]) ? $_POST["floor"] : '';
			
			
			$sql = "SELECT * FROM tb_project_floor_pic WHERE `proTimestamp` = '$proTimeStamp' AND `section` = '$section' AND `build` = '$buildNum' AND  `floor` = '$floor' ";
			// $result_today = mysql_query($sql_today, $this->mysql->conn) or die("Invalid query: " . mysql_error() . $sql);  // sql报错信息
			// echo $result_today;	
			// echo $sql;
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			$ret_data['floorPicName'] = $row['picName'];
		break;
		case 'getHandDrawPic':
			$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';
			// $pointStatus = isset($_POST["pointStatus"]) ? $_POST["pointStatus"] : '';
			$inspectPosition = isset($_POST["inspectPosition"]) ? $_POST["inspectPosition"] : '';	
			$inspectItem = isset($_POST["inspectItem"]) ? $_POST["inspectItem"] : '';	
			$sql = "SELECT * FROM `tb_inspectaccept_measure` WHERE `proTimestamp` = '$proTimeStamp' AND `inspectPosition` = '$inspectPosition' AND `inspectItem` = '$inspectItem'";
			$result = $conn->query($sql);
			if ($result) {
				$row = $result->fetch_assoc();
				$manualPrimaryPic = $row['manualPrimaryPic'];
				$primaryPicName = $row['manualPrimaryPicName'];
				$manualRetestPic = $row['manualRetestPic'];
				$retestPicName = $row['manualRetestPicName'];
				$ret_data['code'] = 1;
				$ret_data['manualPrimaryPic'] = $manualPrimaryPic;
				$ret_data['primaryPicName'] = $primaryPicName;
				$ret_data['manualRetestPic'] = $manualRetestPic;
				$ret_data['retestPicName'] = $retestPicName;
			} else {
				$ret_data['code'] = 0;
			}
			
		break;
	}
	$conn -> close();
	$json = json_encode($ret_data);
	echo $json;
?>
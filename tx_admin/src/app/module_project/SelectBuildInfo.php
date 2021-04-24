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
	}
	$conn -> close();
	$json = json_encode($ret_data);
	echo $json;
?>
<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../conn.php");
	date_default_timezone_set('PRC');
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	
	switch($flag){
		//新增栋号
		case 'addBuild':
			$buildNum = isset($_POST["buildNum"])?$_POST["buildNum"]:'';//新增栋号数量
			$zoneForm = isset($_POST["zoneForm"])?$_POST["zoneForm"]:'';
			$zoneForm = json_decode($zoneForm);
//			print_r($zoneForm);
			$section = $zoneForm->section;
			$proTimeStamp = $zoneForm->proTimeStamp;
			$projectName = $zoneForm->projectName;
			
			$i = 1;
			while($i <= $buildNum){
				$sql = "INSERT INTO tb_project_floor_information SET `timeStamp` = '$proTimeStamp',projectName = '$projectName',section = '$section' ";
				$result = $conn -> query($sql);
				$i++;
			}
			$conn -> close();
		break;
		//获取栋号
		case 'getBuild':
			$section = isset($_POST["section"])?$_POST["section"]:'';
			$projectName = isset($_POST["projectName"])?$_POST["projectName"]:'';
			$timeStamp = isset($_POST["timeStamp"])?$_POST["timeStamp"]:'';

			$sql = "SELECT * FROM tb_project_floor_information WHERE section = '$section' AND projectName = '$projectName' AND timeStamp = '$timeStamp' ORDER BY id ASC";
			$result = $conn -> query($sql);
			$resData = array();
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$resData[$i]["id"] = $row["id"];
					$resData[$i]["build"] = $row["build"];
					$resData[$i]["unitNum"] = $row["unitNum"];
					$resData[$i]["unitName"] = $row["unitName"];
					$resData[$i]["category"] = $row["category"];
					$resData[$i]["undergroundNumber"] = $row["undergroundNumber"];
					$resData[$i]["abovegroundNumber"] = $row["abovegroundNumber"];
					$i++;	
				}
			}
			$conn -> close();
			$json = json_encode($resData);
			echo $json;
		break;
		//删除栋号
		case 'deleteBuild':
			$buildId = isset($_POST["buildId"])?$_POST["buildId"]:'';//栋号id
			
			$sql = "SELECT * FROM tb_project_floor_information WHERE id = '$buildId'";
			$result = $conn -> query($sql);
			$resData = array();
			if ($result -> num_rows > 0) {
				$sql = "DELETE FROM tb_project_floor_information WHERE id = '$buildId'";
				$result = $conn -> query($sql);
			}
			$conn -> close();
		break;
		//定义单元名称
		case 'defineUnitName':
			$buildForm = isset($_POST["buildForm"])?$_POST["buildForm"]:'';
			$buildForm = json_decode($buildForm);
			
			$buildId = $buildForm ->options->id;
			$unitNum = $buildForm ->options->unitNum;
			$buildNameList = $buildForm ->buildNameList;
			$name2 = '';
			foreach($buildNameList as $unitName){
				$name1 = $unitName->name;
				$name2 .= $name1.'||';
			}

			$sql = "UPDATE tb_project_floor_information SET unitName = '$name2',unitNum = '$unitNum' WHERE id = '$buildId'";
			$result = $conn -> query($sql);
			$conn -> close();
		break;
		//获取单元名称
		case 'getUnitName':
			$buildForm = isset($_POST["buildForm"])?$_POST["buildForm"]:'';
			$buildForm = json_decode($buildForm);
			$buildId = $buildForm->options->id;
			
			$sql = "SELECT * FROM tb_project_floor_information WHERE id = '$buildId' ORDER BY id ASC";
			$result = $conn -> query($sql);
			$resData = array();
			if ($result -> num_rows > 0) {
				$row = $result -> fetch_assoc();
				$nameArr = explode('||', $row["unitName"]);
//				$a = json_decode($row["unitName"]);
				$i = 0;
				foreach($nameArr as $unitName){
//					echo $unitName;
					$resData['name'][$i] = $unitName;
					$i++;
				}
			}
			$conn -> close();
			$json = json_encode($resData);
			echo $json;
		break;
		//定义区段楼栋
		case 'defineBuild':
			$tableData = isset($_POST["tableData"])?$_POST["tableData"]:'';
			$zoneForm = isset($_POST["zoneForm"])?$_POST["zoneForm"]:'';
			$tableData = json_decode($tableData);
			$zoneForm = json_decode($zoneForm);
			
			//保存区段栋号设置
//			print_r($tableData);
			foreach($tableData as $buildInfo){
				$buildId = $buildInfo->id;
				$build = $buildInfo->build;
				$undergroundNumber = $buildInfo->undergroundNumber;
				$abovegroundNumber = $buildInfo->abovegroundNumber;
				$category = $buildInfo->category;
				$json = json_encode($category);
				echo $json;
				$sql = "UPDATE tb_project_floor_information SET build = '$build',undergroundNumber = '$undergroundNumber',abovegroundNumber = '$abovegroundNumber',category = '$category' WHERE id = '$buildId'";
				$result = $conn -> query($sql);
			}
			$conn -> close();
		break;
	}
	
?>
<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	if($flag=="getArchitectureData"){
		$tableType = isset($_POST["tableType"])?$_POST["tableType"]:'';
		$sql = "SELECT * FROM ".$tableType." WHERE `projectType`='建筑工程'";
		$result = $conn->query($sql);
		if($result->num_rows>0)	{
			$res = array();
			$resData = array();
            $i=0;
            while($row = $result->fetch_assoc()){
				$resData['index'] = $i+1;
				$resData['id'] = $row['id'];
                $resData['projectType'] = $row['projectType'];
                $resData['inspectionItem'] = $row['inspectionItem'];
                $resData['inspectionContent'] = $row['inspectionContent'];
                $resData['number'] = $row['number'];
                $resData['miniStandardValue'] = $row['miniStandardValue'];
                $resData['maxStandardValue'] = $row['maxStandardValue'];
				$resData['measurementType'] = $row['measurementType'];
				$res[$i] = $resData;
                $i++;
			}
			$data['data'] = $res;
		}else{
			$data['code'] = 0;
		}
	}else if($flag=="getDecorationData"){
		$tableType = isset($_POST["tableType"])?$_POST["tableType"]:'';
		$sql = "SELECT * FROM ".$tableType." WHERE `projectType`='装修工程'";
		$result = $conn->query($sql);
		if($result->num_rows>0)	{
			$res = array();
			$resData = array();
            $i=0;
            while($row = $result->fetch_assoc()){
				$resData['index'] = $i+1;
				$resData['id'] = $row['id'];
                $resData['projectType'] = $row['projectType'];
                $resData['inspectionItem'] = $row['inspectionItem'];
                $resData['inspectionContent'] = $row['inspectionContent'];
                $resData['number'] = $row['number'];
                $resData['miniStandardValue'] = $row['miniStandardValue'];
                $resData['maxStandardValue'] = $row['maxStandardValue'];
				$resData['measurementType'] = $row['measurementType'];
				$res[$i] = $resData;
                $i++;
			}
			$data['data'] = $res;
		}else{
			$data['code'] = 0;
		}
	}else if($flag=="addInspectionItem"){
		$projectType = isset($_POST["projectType"])?$_POST["projectType"]:'';
		$inspectionItem = isset($_POST["inspectionItem"])?$_POST["inspectionItem"]:'';
		$inspectionContent = isset($_POST["inspectionContent"])?$_POST["inspectionContent"]:'';
		$number = isset($_POST["number"])?$_POST["number"]:'';
		$miniStandardValue = isset($_POST["miniStandardValue"])?$_POST["miniStandardValue"]:'';
		$maxStandardValue = isset($_POST["maxStandardValue"])?$_POST["maxStandardValue"]:'';
		$measurementType = isset($_POST["measurementType"])?$_POST["measurementType"]:'';
		$tableType = isset($_POST["tableType"])?$_POST["tableType"]:'';
		$sql1 = "SELECT * FROM ".$tableType." WHERE projectType='$projectType' AND inspectionItem='$inspectionItem' AND inspectionContent='$inspectionContent' AND number='$number' AND miniStandardValue='$miniStandardValue' AND maxStandardValue='$maxStandardValue' AND measurementType='$measurementType'";
		$result1 = $conn->query($sql1);
		if($result1->num_rows==0){
			$sql = "INSERT INTO ".$tableType." SET projectType = '$projectType',inspectionItem = '$inspectionItem',inspectionContent = '$inspectionContent',number = '$number',miniStandardValue = '$miniStandardValue',maxStandardValue = '$maxStandardValue',measurementType = '$measurementType'";
		}
		$result = $conn->query($sql);
		if(!$result){
			$data['code'] = 0;
		}
	}else if($flag=="deleteItem"){
		$id = isset($_POST["id"])?$_POST["id"]:'';
		$tableType = isset($_POST["tableType"])?$_POST["tableType"]:'';
		$id_arr = json_decode($id);
		for($i=0;$i<count($id_arr);$i++){
			$sql = "DELETE FROM ".$tableType." WHERE `id`='$id_arr[$i]'";
			$result = $conn->query($sql);
		}
		if(!$result){
			$data['code'] = 0;
		}
	}else if($flag=="updateInspectionItem"){
		$id = isset($_POST["id"])?$_POST["id"]:'';
		$projectType = isset($_POST["projectType"])?$_POST["projectType"]:'';
		$inspectionItem = isset($_POST["inspectionItem"])?$_POST["inspectionItem"]:'';
		$inspectionContent = isset($_POST["inspectionContent"])?$_POST["inspectionContent"]:'';
		$number = isset($_POST["number"])?$_POST["number"]:'';
		$miniStandardValue = isset($_POST["miniStandardValue"])?$_POST["miniStandardValue"]:'';
		$maxStandardValue = isset($_POST["maxStandardValue"])?$_POST["maxStandardValue"]:'';
		$measurementType = isset($_POST["measurementType"])?$_POST["measurementType"]:'';
		$tableType = isset($_POST["tableType"])?$_POST["tableType"]:'';
		$sql = "UPDATE ".$tableType." set  projectType = '$projectType',inspectionItem = '$inspectionItem',inspectionContent = '$inspectionContent',number = '$number',miniStandardValue = '$miniStandardValue',maxStandardValue = '$maxStandardValue',measurementType = '$measurementType' WHERE id='$id'";
		$result = $conn->query($sql);
		if(!$result){
			$data['code'] = 0;
		}
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	
?>
<?php
	require ("../../../../conn.php");
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	$date = date("Y-m-d H:i:s");
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag){
		//获取拆模部位
		case 'removalPosition':
		$projectId = isset($_POST["projectId"])?$_POST["projectId"] : '';
		$sql = "SELECT * FROM tb_concrete_pour where proState = '完成旁站' and projectId = '$projectId' order by id desc";
		$res = $conn -> query($sql);
		if ($res -> num_rows > 0) {
			$i = 0;
			while ($row = $res -> fetch_assoc()) {
				$ret_data["data"][$i]["id"] = $row["id"];
				$ret_data["data"][$i]["pouringPosition"] = $row["pouringPosition"];
				$i++;
			}
			$ret_data['status']='success';
		}else{
			$ret_data["data"] = [];
			$ret_data["states"] = 'error';
		}
		$conn -> close();
		$json = json_encode($ret_data);
		echo $json;
		break;
		//根据拆模部位获取砼强度等级和浇筑时间
		case 'getConcreteTime':
		$projectId = isset($_POST["projectId"])?$_POST["projectId"] : '';
		$removalPosition = isset($_POST["removalPosition"])?$_POST["removalPosition"] : '';
		$sql = "SELECT * FROM tb_concrete_pour where proState = '完成旁站' and projectId = '$projectId' and pouringPosition LIKE '%$removalPosition%' order by id desc";
		$res = $conn -> query($sql);
		if ($res -> num_rows > 0) {
			$i = 0;
			while ($row = $res -> fetch_assoc()) {
				$ret_data["data"][$i]["id"] = $row["id"];
				$ret_data["data"][$i]["pouringDate"] = $row["pouringDate"];
				$pouringPosition = explode('/',$row["pouringPosition"]);
				$concreteGrade = explode('/',$row["concreteGrade"]);
				
				for($index=0;$index<count($pouringPosition);$index++)
					{
						if($pouringPosition[$index]==$removalPosition){
							$ret_data["data"][$i]["concreteGrade"] = $concreteGrade[$index];
							break;
						}
					}
				$i++;
			}
			$ret_data['status']='success';
		}else{
			$ret_data["data"] = [];
			$ret_data["states"] = 'error';
		}
		$conn -> close();
		$json = json_encode($ret_data);
		echo $json;
		break;
		//创建拆模
		case 'create_removal':
		$projectName = isset($_POST["projectName"])?$_POST["projectName"] : '';
		$company = isset($_POST["company"])?$_POST["company"] : '';
		$pouringDate = isset($_POST["pouringDate"])?$_POST["pouringDate"] : '';
		$concreteGrade = isset($_POST["concreteGrade"])?$_POST["concreteGrade"] : '';
		$removalPosition =isset($_POST["removalPosition"])?$_POST["removalPosition"] : '';
		$projectId = isset($_POST["projectId"])?$_POST["projectId"] : '';
		$createrId = isset($_POST["createrId"])?$_POST["createrId"] : '';
		$creater = isset($_POST["creater"])?$_POST["creater"] : '';
		$section = isset($_POST["section"])?$_POST["section"] : '';
		$build = isset($_POST["build"])?$_POST["build"] : '';
		$floor = isset($_POST["floor"])?$_POST["floor"] : '';
		$unitName = isset($_POST["unitName"])?$_POST["unitName"] : '';
		$sql = "INSERT INTO tb_concrete_removal (projectId,projectName,section,build,floor,unitName,concreteGrade,removalPosition,pouringDate,proState,company,createrId,creater,createDate) VALUES ('$projectId','$projectName','$section','$build','$floor','$unitName','$concreteGrade','$removalPosition','$pouringDate','拆模','$company','$createrId','$creater','$date')";
			$res = $conn -> query($sql);
			if ($res) {
				$ret_data['status']='success';
			}else{
				$ret_data['status']='error';
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
		//获取拆模卡片
		case 'getremoval':
			$isSelBuild = isset($_POST["isSelBuild"]) ? $_POST["isSelBuild"] : '';
			$buildSelData = isset($_POST["buildSelData"]) ? $_POST["buildSelData"] : '';
			if($isSelBuild){
				$buildSelData = json_decode($buildSelData,true);
				$build = $buildSelData["build"];
				$floor = $buildSelData["floor"];
				$unit = $buildSelData["unit"];
				$sql = "SELECT * FROM tb_concrete_removal where proState = '拆模' AND build='$build' AND floor='$floor' AND unitName='$unit' order by id desc";
			}else{
				$sql = "SELECT * FROM tb_concrete_removal where proState = '拆模' order by id desc";
			}
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["projectName"] = $row["projectName"];
					$ret_data["data"][$i]["creater"] = $row["creater"];
					$ret_data["data"][$i]["concreteGrade"] = $row["concreteGrade"];
					$ret_data["data"][$i]["removalPosition"] = $row["removalPosition"];
					$ret_data["data"][$i]["pouringDate"] = $row["pouringDate"];
					$ret_data["data"][$i]["proState"] = $row["proState"];
					$i++;
				}
				$ret_data['status']='success';
			}else{
				$ret_data["data"] = [];
				$ret_data["states"] = 'error';
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
			//删除拆模卡片
			case 'deleteRemoval':
		    $id = isset($_POST["id"])?$_POST["id"] : '';
		    
		    $sql = "SELECT * from tb_concrete_removal where id = '$id'";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$sql = "DELETE FROM tb_concrete_removal WHERE id = '$id' ";
				$res = $conn -> query($sql);
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
		//获取拆模卡片详情
		case 'removalDetails':
		    $id = isset($_POST["id"])?$_POST["id"] : '';
			$sql = "SELECT * FROM tb_concrete_removal where   id = '$id' order by id desc";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["projectName"] = $row["projectName"];
					$ret_data["data"][$i]["concreteGrade"] = $row["concreteGrade"];
					$ret_data["data"][$i]["removalPosition"] = $row["removalPosition"];
					$ret_data["data"][$i]["pouringDate"] = $row["pouringDate"];
					$i++;
				}
				$ret_data['status']='success';
			}else{
				$ret_data["data"] = [];
				$ret_data["states"] = 'error';
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
			
		//获取拆模报告图片描述
		case 'RemovalImg':
		    $id = isset($_POST["id"])?$_POST["id"] : '';
			$sql = "SELECT * FROM tb_concrete_removal where  id = '$id' ";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["removal_describe"] = $row["removal_describe"];
					$ret_data["data"][$i]["removal_img"] = $row["removal_img"];
					
					$i++;
				}
				$ret_data['status']='success';
			}else{
				$ret_data["data"] = [];
				$ret_data["states"] = 'error';
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
		//获取完成拆模卡片
		case 'CompleteRemoval':
			$isSelBuild = isset($_POST["isSelBuild"]) ? $_POST["isSelBuild"] : '';
			$buildSelData = isset($_POST["buildSelData"]) ? $_POST["buildSelData"] : '';
			if($isSelBuild){
				$buildSelData = json_decode($buildSelData,true);
				$build = $buildSelData["build"];
				$floor = $buildSelData["floor"];
				$unit = $buildSelData["unit"];
				$sql = "SELECT * FROM tb_concrete_removal where proState = '完工' AND build='$build' AND floor='$floor' AND unitName='$unit' order by id desc";
			}else{
				$sql = "SELECT * FROM tb_concrete_removal where proState = '完工' order by id desc";
			}
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["projectName"] = $row["projectName"];
					$ret_data["data"][$i]["creater"] = $row["creater"];
					$ret_data["data"][$i]["concreteGrade"] = $row["concreteGrade"];
					$ret_data["data"][$i]["removalPosition"] = $row["removalPosition"];
					$ret_data["data"][$i]["pouringDate"] = $row["pouringDate"];
					$ret_data["data"][$i]["proState"] = $row["proState"];
					$i++;
				}
				$ret_data['status']='success';
			}else{
				$ret_data["data"] = [];
				$ret_data["states"] = 'error';
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
	}
	

	

?>
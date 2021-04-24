<?php
	require ("../../../../conn.php");
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	$date = date("Y-m-d H:i:s");
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag){
		case 'create_pour':
		$projectName = isset($_POST["projectName"])?$_POST["projectName"] : '';
		$company = isset($_POST["company"])?$_POST["company"] : '';
		$pouringDate = isset($_POST["pouringDate"])?$_POST["pouringDate"] : '';
		$concreteGrade = isset($_POST["concreteGrade"])?$_POST["concreteGrade"] : '';
		$pouringPosition =isset($_POST["pouringPosition"])?$_POST["pouringPosition"] : '';
		$pouringVolume = isset($_POST["pouringVolume"])?$_POST["pouringVolume"] : '';
		$builder = isset($_POST["builder"])?$_POST["builder"] : '';
		$projectId = isset($_POST["projectId"])?$_POST["projectId"] : '';
		$createrId = isset($_POST["createrId"])?$_POST["createrId"] : '';
		$creater = isset($_POST["creater"])?$_POST["creater"] : '';
		$section = isset($_POST["section"])?$_POST["section"] : '';
		$build = isset($_POST["build"])?$_POST["build"] : '';
		$floor = isset($_POST["floor"])?$_POST["floor"] : '';
		$unitName = isset($_POST["unitName"])?$_POST["unitName"] : '';
		$sql = "INSERT INTO tb_concrete_pour (projectId,projectName,section,build,floor,unitName,concreteGrade,pouringVolume,pouringPosition,pouringDate,bystander,proState,builder,company,createrId,creater,createDate) VALUES ('$projectId','$projectName','$section','$build','$floor','$unitName','$concreteGrade','$pouringVolume','$pouringPosition','$pouringDate','$builder','浇筑','$builder','$company','$createrId','$creater','$date')";
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
		//获取浇筑令
		case 'getpour':
			$isSelBuild = isset($_POST["isSelBuild"]) ? $_POST["isSelBuild"] : '';
			$buildSelData = isset($_POST["buildSelData"]) ? $_POST["buildSelData"] : '';
			if($isSelBuild){
				$buildSelData = json_decode($buildSelData,true);
				$build = $buildSelData["build"];
				$floor = $buildSelData["floor"];
				$unit = $buildSelData["unit"];
				$sql = "SELECT * FROM tb_concrete_pour where proState = '浇筑' AND build='$build' AND floor='$floor' AND unitName='$unit' order by id desc";
			}else{
				$sql = "SELECT * FROM tb_concrete_pour where proState = '浇筑' order by id desc";
			}
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["projectName"] = $row["projectName"];
					$ret_data["data"][$i]["creater"] = $row["creater"];
					$ret_data["data"][$i]["concreteGrade"] = $row["concreteGrade"];
					$ret_data["data"][$i]["pouringVolume"] = $row["pouringVolume"];
					$ret_data["data"][$i]["pouringPosition"] = $row["pouringPosition"];
					$ret_data["data"][$i]["pouringDate"] = $row["pouringDate"];
					$ret_data["data"][$i]["proState"] = $row["proState"];
					$ret_data["data"][$i]["builder"] = $row["builder"];
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
		//获取浇筑令详情
		case 'PourDetails':
		    $id = isset($_POST["id"])?$_POST["id"] : '';
			$sql = "SELECT * FROM tb_concrete_pour where   id = '$id' order by id desc";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["projectName"] = $row["projectName"];
					$ret_data["data"][$i]["concreteGrade"] = $row["concreteGrade"];
					$ret_data["data"][$i]["pouringVolume"] = $row["pouringVolume"];
					$ret_data["data"][$i]["pouringPosition"] = $row["pouringPosition"];
					$ret_data["data"][$i]["pouringDate"] = $row["pouringDate"];
					$ret_data["data"][$i]["builder"] = $row["builder"];
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
			//删除浇筑令
			case 'deletePour':
		    $id = isset($_POST["id"])?$_POST["id"] : '';
		    
		    $sql = "SELECT * from tb_concrete_pour where id = '$id'";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$sql = "DELETE FROM tb_concrete_pour WHERE id = '$id' ";
				$res = $conn -> query($sql);
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
		//获取签名信息详情
		case 'PourSign':
		    $cardid = isset($_POST["cardid"])?$_POST["cardid"] : '';
			$sql = "SELECT * FROM tb_concrete_sign where  cardid = '$cardid' ";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["sign1"] = $row["sign1"];
					$ret_data["data"][$i]["idea1"] = $row["idea1"];
					$ret_data["data"][$i]["sign2"] = $row["sign2"];
					$ret_data["data"][$i]["idea2"] = $row["idea2"];
					$i++;
				}
				$ret_data['status']='success';
			}else{
				$ret_data["data"] = [];
				$ret_data["status"] = 'error';
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
		//获取旁站卡片
		case 'getside':
			$isSelBuild = isset($_POST["isSelBuild"]) ? $_POST["isSelBuild"] : '';
			$buildSelData = isset($_POST["buildSelData"]) ? $_POST["buildSelData"] : '';
			if($isSelBuild){
				$buildSelData = json_decode($buildSelData,true);
				$build = $buildSelData["build"];
				$floor = $buildSelData["floor"];
				$unit = $buildSelData["unit"];
				$sql = "SELECT * FROM tb_concrete_pour where proState = '旁站' AND build='$build' AND floor='$floor' AND unitName='$unit' order by id desc";
			}else{
				$sql = "SELECT * FROM tb_concrete_pour where proState = '旁站' order by id desc";
			}
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["projectName"] = $row["projectName"];
					$ret_data["data"][$i]["creater"] = $row["creater"];
					$ret_data["data"][$i]["concreteGrade"] = $row["concreteGrade"];
					$ret_data["data"][$i]["pouringVolume"] = $row["pouringVolume"];
					$ret_data["data"][$i]["pouringPosition"] = $row["pouringPosition"];
					$ret_data["data"][$i]["pouringDate"] = $row["pouringDate"];
					$ret_data["data"][$i]["proState"] = $row["proState"];
					$ret_data["data"][$i]["builder"] = $row["builder"];
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
			//下达旁站
			case 'assignside':
		    $cardid = isset($_POST["cardid"])?$_POST["cardid"] : '';
		    $bystander = isset($_POST["bystander"])?$_POST["bystander"] : '';
		    $sql = "SELECT * from tb_concrete_pour where id = '$cardid'";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$sql = "UPDATE `tb_concrete_pour` SET `proState` = '旁站' ,`sideDate` = '$date',`bystander`='$bystander' WHERE id = '$cardid'  ";
				$res = $conn -> query($sql);
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
			//完成旁站
			case 'completeside':
			$cardid = isset($_POST["cardid"])?$_POST["cardid"] : '';
		    $sql = "SELECT * from tb_concrete_pour where id = '$cardid'";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$sql = "UPDATE `tb_concrete_pour` SET `proState` = '完成旁站' ,`completeDate` = '$date' WHERE id = '$cardid'  ";
				$res = $conn -> query($sql);
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
		//获取完成旁站卡片
		case 'getcompleteside':
		$isSelBuild = isset($_POST["isSelBuild"]) ? $_POST["isSelBuild"] : '';
			$buildSelData = isset($_POST["buildSelData"]) ? $_POST["buildSelData"] : '';
			if($isSelBuild){
				$buildSelData = json_decode($buildSelData,true);
				$build = $buildSelData["build"];
				$floor = $buildSelData["floor"];
				$unit = $buildSelData["unit"];
				$sql = "SELECT * FROM tb_concrete_pour where proState = '完成旁站' AND build='$build' AND floor='$floor' AND unitName='$unit' order by id desc";
			}else{
				$sql = "SELECT * FROM tb_concrete_pour where proState = '完成旁站' order by id desc";
			}
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["projectName"] = $row["projectName"];
					$ret_data["data"][$i]["creater"] = $row["creater"];
					$ret_data["data"][$i]["concreteGrade"] = $row["concreteGrade"];
					$ret_data["data"][$i]["pouringVolume"] = $row["pouringVolume"];
					$ret_data["data"][$i]["pouringPosition"] = $row["pouringPosition"];
					$ret_data["data"][$i]["pouringDate"] = $row["pouringDate"];
					$ret_data["data"][$i]["proState"] = $row["proState"];
					$ret_data["data"][$i]["builder"] = $row["builder"];
					$ret_data["data"][$i]["completeDate"] = $row["completeDate"];
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
		//获取旁站详情
		case 'SideDetails':
		    $id = isset($_POST["id"])?$_POST["id"] : '';
			$sql = "SELECT * FROM tb_concrete_pour where  id = '$id' and ( proState = '旁站' or proState = '完成旁站') order by id desc";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["projectName"] = $row["projectName"];
					$ret_data["data"][$i]["concreteGrade"] = $row["concreteGrade"];
					$ret_data["data"][$i]["pouringVolume"] = $row["pouringVolume"];
					$ret_data["data"][$i]["pouringPosition"] = $row["pouringPosition"];
					$ret_data["data"][$i]["sideDate"] = $row["sideDate"];
					$ret_data["data"][$i]["bystander"] = $row["bystander"];
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
		//获取浇筑图片详情
		case 'PourImg':
		    $id = isset($_POST["id"])?$_POST["id"] : '';
			$sql = "SELECT * FROM tb_concrete_pour where  id = '$id' ";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["poured_describe"] = $row["poured_describe"];
					$ret_data["data"][$i]["poured_img"] = $row["poured_img"];
					
					$ret_data["data"][$i]["pouring_describe"] = $row["pouring_describe"];
					$ret_data["data"][$i]["pouring_img"] = $row["pouring_img"];
					
					$ret_data["data"][$i]["pour_describe"] = $row["pour_describe"];
					$ret_data["data"][$i]["pour_img"] = $row["pour_img"];
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
		    //获取施工员
		    case 'getbuilder':
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
							$data[$i] = '{"'.$rows["username"].'":"'.$rows["userId"].'"}';
							$i++;
						}
					}
	
					if($row["proManager"]){
						$proManager = explode('/',$row["proManager"]);//项目经理
						$data[$i+1]= '{"'.$proManager[0].'":"'.$proManager[3].'"}';
					}
	
					if($row["proChief"]){
						$proChief = explode('/',$row["proChief"]);//项目总工
						$data[$i+2]= '{"'.$proChief[0].'":"'.$proChief[3].'"}';
					}
					
					if($row["produceManager"]){
						$produceManager = explode('/',$row["produceManager"]);//生产经理
						$data[$i+3]= '{"'.$produceManager[0].'":"'.$produceManager[3].'"}';
					}
				}
				echo json_encode($data);
			}
		break;
	}

?>
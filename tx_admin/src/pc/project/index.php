<?php
	require ("../../../conn.php");
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	$ret_data = '';
	$ret_data["success"] = 'success';
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	$timeStamp = isset($_POST["timeStamp"])?$_POST["timeStamp"] : '';
	$projectName = isset($_POST["projectName"])?$_POST["projectName"] : '';
	$categories =isset($_POST["categories"])?$_POST["categories"] : '';
	$company = isset($_POST["company"])?$_POST["company"] : '';
	$companyId = isset($_POST["companyId"])?$_POST["companyId"] : '';
	$province = isset($_POST["province"])?$_POST["province"] : '';
	$city = isset($_POST["city"])?$_POST["city"] : '';
	$proPosition =isset($_POST["proPosition"])?$_POST["proPosition"] : '';
	$architecture = isset($_POST["architecture"])?$_POST["architecture"] : '';
	$startTime = isset($_POST["startTime"])?$_POST["startTime"] : '';
	$completedTime = isset($_POST["completedTime"])?$_POST["completedTime"] : '';
	
	switch($flag){
		//获取项目到本地系统
		case 'getProject':
			$sql = "SELECT * FROM tb_project order by id desc";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["timeStamp"] = $row["timeStamp"];
					$ret_data["data"][$i]["projectName"] = $row["projectName"];
					$ret_data["data"][$i]["categories"] = $row["categories"];
					$ret_data["data"][$i]["company"] = $row["company"];
					$ret_data["data"][$i]["startTime"] = $row["startTime"];
					$ret_data["data"][$i]["completedTime"] = $row["completedTime"];
					$ret_data["data"][$i]["proState"] = $row["proState"];
					$ret_data["data"][$i]["isCompleted"] = $row["isCompleted"];
					
					$ret_data["data"][$i]["province"] = $row["province"];//地区省
					$ret_data["data"][$i]["city"] = $row["city"];//地区市
					$ret_data["data"][$i]["section"] = $row["section"];//区段
					$ret_data["data"][$i]["architecture"] = $row["architecture"];//建筑面积
					$ret_data["data"][$i]["proPosition"] = $row["proPosition"];
					$ret_data["data"][$i]["projectId"] = $row["id"];					
					$i++;
				}
				$ret_data["success"] = 'success';
			}else{
				$ret_data["states"] = 'error';
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
		//筛选公司相关的工程
		case 'getFilterProject':
			$sql = "SELECT * FROM tb_project where company = '$company' order by id desc";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["timeStamp"] = $row["timeStamp"];
					$ret_data["data"][$i]["projectName"] = $row["projectName"];
//					$ret_data["data"][$i]["zone"] = $row["zone"];
					$ret_data["data"][$i]["categories"] = $row["categories"];
					$ret_data["data"][$i]["company"] = $row["company"];
					$ret_data["data"][$i]["startTime"] = $row["startTime"];
					$ret_data["data"][$i]["completedTime"] = $row["completedTime"];
					$ret_data["data"][$i]["proState"] = $row["proState"];
					$ret_data["data"][$i]["isCompleted"] = $row["isCompleted"];
					$i++;
				}
				$ret_data["success"] = 'success';
			}else{
				$ret_data["data"] = [];
				$ret_data["states"] = 'error';
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
		//新增项目到本地系统
		case 'addProject':
			$sql = "SELECT * FROM tb_project where timeStamp='".$timeStamp."' ";
			$res = $conn -> query($sql);
			if ($res -> num_rows ==0) {
				$sql = "INSERT INTO tb_project (timeStamp,projectName,categories,company,companyId,province,city,proPosition,architecture,startTime,completedTime) VALUES ('$timeStamp','$projectName','$categories','$company','$companyId','$province','$city','$proPosition','$architecture','$startTime','$completedTime') ";
				$res = $conn->query($sql);
				$ret_data["states"] = 'success';
			}else{
				
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
		//编辑工程 项目人员管理
		case 'editProject':
			$sql = "SELECT * FROM tb_project where timeStamp='".$timeStamp."' ";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$sql = "UPDATE tb_project SET isCompleted = '1' where timeStamp='".$timeStamp."'";
				$res = $conn->query($sql);
				$ret_data["states"] = 'success';
			}else{
				
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
	}

?>
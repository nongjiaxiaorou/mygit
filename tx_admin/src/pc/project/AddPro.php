<?php
	require ("../../../conn.php");
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	$ret_data = '';
	$ret_data["success"] = 'success';
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';

	
	switch($flag){
		//新增项目到本地系统
		case 'addProject':
			$timeStamp = isset($_POST["timeStamp"])?$_POST["timeStamp"] : '';
			$formData = isset($_POST["formData"])?$_POST["formData"] : '';
			$formData = json_decode($formData);
			
			$projectName = $formData ->projectName;
			$categories = $formData ->categories;
			$company = $formData ->company->value;
			$companyId = $formData ->company->companyId;
			$province = $formData ->province;
			$city = $formData ->city;
			$proPosition = $formData ->proPosition;
			$architecture = $formData ->architecture;
			$startTime = $formData ->startTime;
			$startTime = substr($startTime, 0,10);
			$completedTime = $formData ->completedTime;
			$completedTime = substr($completedTime, 0,10);
			$database = $formData ->database;
			
			$sql = "SELECT * FROM tb_project where timeStamp='".$timeStamp."' ";
			$res = $conn -> query($sql);
			if ($res -> num_rows ==0) {
				$sql = "INSERT INTO tb_project (`timeStamp`,projectName,categories,company,companyId,province,city,proPosition,architecture,startTime,completedTime,violationStandard) VALUES ('$timeStamp','$projectName','$categories','$company','$companyId','$province','$city','$proPosition','$architecture','$startTime','$completedTime','$database') ";
				$res = $conn->query($sql);
				$ret_data["states"] = 'success';
			}else{
				
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
		break;
		//获取定义好的违章数据库
		case 'getDataBase':
			
			$sql = "SELECT * FROM tb_system_database_depot order by id desc";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["depotName"] = $row["depotName"];
					
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
	}

?>
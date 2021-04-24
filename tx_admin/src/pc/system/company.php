<?php
	require ("../../../conn.php");
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	$ret_data = '';
	$ret_data["success"] = 'success';
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag){
		//获取项目到本地系统
		case 'getCompany':
			$framework = '';//架构标志 分二级跟三级 
			$sql = "SELECT * FROM tb_system_company order by id desc";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					if($row["companyRank"]=='总公司'){
						$framework = '三级架构';
					}
					if($row["companyRank"]=='公司'){
						$framework = '二级架构';
					}
					$ret_data["data"][$i]["companyId"] = $row["id"];
					$ret_data["data"][$i]["timeStamp"] = $row["timeStamp"];
					$ret_data["data"][$i]["companyName"] = $row["companyName"];
					$ret_data["data"][$i]["companyRank"] = $row["companyRank"];
					$ret_data["data"][$i]["createTime"] = $row["createTime"];
					$i++;
				}
				$ret_data["success"] = 'success';
				$ret_data["framework"] = $framework;
			}else{//首次进入集团公司界面
				$ret_data["framework"] = '';
				$ret_data["data"] = [];
			}
			
			break;
		//新增公司到本地系统
		case 'addCompany':
			$timeStamp = isset($_POST["timeStamp"])?$_POST["timeStamp"] : '';
			$companyName = isset($_POST["companyName"])?$_POST["companyName"] : '';
			$companyRank = isset($_POST["companyRank"])?$_POST["companyRank"] : '';
			$createTime = isset($_POST["createTime"])?$_POST["createTime"] : '';
			
			$sql = "SELECT * FROM tb_system_company where companyName='".$companyName."' ";
			$res = $conn -> query($sql);
			if ($res -> num_rows ==0) {
				$sql = "INSERT INTO tb_system_company (timeStamp,companyName,companyRank,createTime) VALUES ('$timeStamp','$companyName','$companyRank','$createTime') ";
				$res = $conn->query($sql);
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
			}
			
			break;
		//新增公司到本地系统
		case 'changeCompany':
			$companyId = isset($_POST["companyId"])?$_POST["companyId"] : '';
			$companyName = isset($_POST["companyName"])?$_POST["companyName"] : '';
			
			$sql = "SELECT * FROM tb_system_company where id='".$companyId."' ";
			$res = $conn -> query($sql);
			if ($res -> num_rows >0) {
				$sql = "UPDATE tb_system_company set companyName ='$companyName' where id='".$companyId."' ";
				$res = $conn->query($sql);
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
			}
			
			break;
		//编辑工程 项目人员管理
		case 'editProject':
			$sql = "SELECT * FROM tb_project where timeStamp='".$timeStamp."' ";
			$res = $conn -> query($sql);
			if ($res -> num_rows ==0) {
				$sql = "INSERT INTO tb_project (timeStamp,projectName,categories,company,province,city,proPosition,architecture,startTime,completedTime) VALUES ('$timeStamp','$projectName','$categories','$company','$province','$city','$proPosition','$architecture','$startTime','$completedTime') ";
				$res = $conn->query($sql);
				$ret_data["states"] = 'success';
			}else{
				
			}
			
			break;
	}
	$conn -> close();
	$json = json_encode($ret_data);
	echo $json;
?>
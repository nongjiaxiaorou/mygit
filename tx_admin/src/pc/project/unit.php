<?php
	require ("../../../conn.php");
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	$timeStamp = isset($_POST["timeStamp"]) ? $_POST["timeStamp"] : '';
	$Unit = isset($_POST["Unit"]) ? $_POST["Unit"] : '';
	$createTime = isset($_POST["createTime"]) ? $_POST["createTime"] : '';
	$unitId = isset($_POST["unitId"]) ? $_POST["unitId"] : '';
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	
	switch($flag){
		//添加分包单位
		case 'addUnit':
			$sql1 = "SELECT * FROM tb_project_subcontractor WHERE projectTimestamp='$timeStamp' AND subcontractor='$Unit'";
			$result = $conn -> query($sql1);
			if($result->num_rows==0){
				$sql = "INSERT INTO tb_project_subcontractor SET projectTimestamp='$timeStamp',subcontractor='$Unit',createTime='$createTime'";
				$res = $conn -> query($sql);
				if ($res) {
					$data["code"] = 1;
				}else{
					$data["code"] = 0;
				}
			}else{
				$data["code"] = 0;
			}
		break;
		case 'getUnit':
			$sql = "SELECT * FROM tb_project_subcontractor WHERE projectTimestamp='$timeStamp' ORDER BY id DESC";
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
			    $i=0;
			    while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
			        $resData['subcontractor'] = $row['subcontractor'];
			        $resData['createTime'] = $row['createTime'];
					$res[$i] = $resData;
			        $i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
		break;
		case 'deleteUnit':
			$sql = "DELETE FROM tb_project_subcontractor WHERE id='$unitId'";
			$result = $conn -> query($sql);
			if ($result) {
				$data["code"] = 1;
			}else{
				$data["code"] = 0;
			}
		break;
		//更新工程标准库
		case 'updateStandardTable':
			$timeStamp = isset($_POST["timeStamp"]) ? $_POST["timeStamp"] : '';
			$tableType = isset($_POST["tableType"]) ? $_POST["tableType"] : '';
			$sql = "UPDATE tb_project SET standard='$tableType' WHERE timeStamp='$timeStamp'";
			$res = $conn -> query($sql);
			if ($res) {
				$data["code"] = 1;
			}else{
				$data["code"] = 0;
			}
		break;
		case 'getStandardTable':
			$timeStamp = isset($_POST["timeStamp"]) ? $_POST["timeStamp"] : '';
			$sql = "SELECT * FROM tb_project WHERE timeStamp='$timeStamp'";
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$row = $result->fetch_assoc();
				$data['data'] = $row["standard"];
			}else{
				$data['code'] = 0;
			}
		break;
	}
	
	$conn -> close();
	$json = json_encode($data);
	echo $json;

?>
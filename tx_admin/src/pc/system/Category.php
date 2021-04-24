<?php
	require ("../../../conn.php");
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	$ret_data = '';
	$ret_data["success"] = 'success';
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag){
		//新增工程类别
		case 'addCategory':
			$category = isset($_POST["category"]) ? $_POST["category"] : '';
			
			$sql = "INSERT INTO tb_system_category set `category`='$category' ";
			$res = $conn -> query($sql);
			break;
		//获取工程类别
		case 'getCategory':
			$sql = "SELECT * from tb_system_category order by id DESC";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["category"] = $row["category"];
					
					$i++;
				}
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
				$ret_data["data"] = [];
			}
			break;
		//修改工程类别
		case 'changeCategory':
			$id = isset($_POST["id"]) ? $_POST["id"] : '';
			$category = isset($_POST["category"]) ? $_POST["category"] : '';
			
			$sql = "SELECT * from tb_system_category where id = '$id'";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$sql = "UPDATE tb_system_category set `category`='$category' where id = '$id' ";
				$res = $conn -> query($sql);
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
			}
			break;
		//删除工程类别
		case 'delCategory':
			$id = isset($_POST["id"]) ? $_POST["id"] : '';
			
			$sql = "SELECT * from tb_system_category where id = '$id'";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$sql = "DELETE from tb_system_category  where id = '$id' ";
				$res = $conn -> query($sql);
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
			}
			break;
	}
	$conn -> close();
	$json = json_encode($ret_data);
	echo $json;
?>
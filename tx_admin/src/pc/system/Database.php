<?php
	require ("../../../conn.php");
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	$ret_data = '';
	$ret_data["success"] = 'success';
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag){
		//新增违章条目
		case 'addItem':
			$majorCategories = isset($_POST["majorCategories"]) ? $_POST["majorCategories"] : '';
			$subItems = isset($_POST["subItems"]) ? $_POST["subItems"] : '';
			$mainPoints = isset($_POST["mainPoints"]) ? $_POST["mainPoints"] : '';
			$describe = isset($_POST["describe"]) ? $_POST["describe"] : '';
			$number = isset($_POST["number"]) ? $_POST["number"] : '';
			$depotId = isset($_POST["depotId"]) ? $_POST["depotId"] : '';
			
			$sql = "INSERT INTO tb_system_database set `majorCategories`='$majorCategories',`subItems`='$subItems',`mainPoints`='$mainPoints',`describe`='$describe',`number`='$number',`depotId`= '$depotId' ";
			$res = $conn -> query($sql);
			break;
		//获取违章条目
		case 'getItem':
			$sql = "SELECT * from tb_system_database order by id DESC";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["majorCategories"] = $row["majorCategories"];
					$ret_data["data"][$i]["subItems"] = $row["subItems"];
					$ret_data["data"][$i]["mainPoints"] = $row["mainPoints"];
					$ret_data["data"][$i]["describe"] = $row["describe"];
					$ret_data["data"][$i]["number"] = $row["number"];
					$ret_data["data"][$i]["depotId"] = $row["depotId"];
					
					$i++;
				}
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
				$ret_data["data"] = [];
			}
			break;
		//修改违章条目
		case 'changeItem':
			$id = isset($_POST["id"]) ? $_POST["id"] : '';
			$majorCategories = isset($_POST["majorCategories"]) ? $_POST["majorCategories"] : '';
			$subItems = isset($_POST["subItems"]) ? $_POST["subItems"] : '';
			$mainPoints = isset($_POST["mainPoints"]) ? $_POST["mainPoints"] : '';
			$describe = isset($_POST["describe"]) ? $_POST["describe"] : '';
			$number = isset($_POST["number"]) ? $_POST["number"] : '';
			
			$sql = "SELECT * from tb_system_database where id = '$id'";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$sql = "UPDATE tb_system_database set `majorCategories`='$majorCategories',`subItems`='$subItems',`mainPoints`='$mainPoints',`describe`='$describe',`number`='$number' where id = '$id' ";
				$res = $conn -> query($sql);
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
				$ret_data["data"] = [];
			}
			break;
		//新增自定义库
		case 'addDepot':
			$depotName = isset($_POST["depotName"]) ? $_POST["depotName"] : '';
			
			$sql = "SELECT * from tb_system_database_depot where depotName = '$depotName'";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$ret_data["states"] = 'error';
			}else{
				$sql = "INSERT INTO tb_system_database_depot set `depotName`='$depotName' ";
				$res = $conn -> query($sql);
				$ret_data["states"] = 'success';
			}
			break;
		//获取自定义库
		case 'getDepot':
			$sql = "SELECT * from tb_system_database_depot order by id asc";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["depotName"] = $row["depotName"];
					
					$i++;
				}
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
				$ret_data["data"] = [];
			}
			break;
		//根据自定义库获取违章条目
		case 'selectDepot':
			$depotId = isset($_POST["depotId"]) ? $_POST["depotId"] : '';
			
			$sql = "SELECT * from tb_system_database where depotId = '$depotId' order by id desc";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["majorCategories"] = $row["majorCategories"];
					$ret_data["data"][$i]["subItems"] = $row["subItems"];
					$ret_data["data"][$i]["mainPoints"] = $row["mainPoints"];
					$ret_data["data"][$i]["describe"] = $row["describe"];
					$ret_data["data"][$i]["number"] = $row["number"];
					$ret_data["data"][$i]["depotId"] = $row["depotId"];
					
					$i++;
				}
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
				$ret_data["data"] = [];
			}
			break;
	}
	$conn -> close();
	$json = json_encode($ret_data);
	echo $json;
?>
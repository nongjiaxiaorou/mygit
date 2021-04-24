<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$ret_data = array();
	switch($flag){
		//获取巡查小组信息
		case 'getGroupData':

			$sql="SELECT * FROM tb_quality_group_member AS a ORDER BY a.id ASC"; 
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				$i = 0;
				while ($row = $result -> fetch_assoc()) {
					$ret_data["data"][$i]["groupName"] = $row["groupName"];
					$ret_data["data"][$i]["groupID"] = $row["groupID"];
					$ret_data["data"][$i]["groupPost"] = $row["groupPost"];
					$ret_data["data"][$i]["userName"] = $row["name"];
					$ret_data["data"][$i]["phone"] = $row["phnoeNumber"];
					$ret_data["data"][$i]["userId"] = $row["userID"];
					$ret_data["data"][$i]["company"] = $row["company"];
					$ret_data["data"][$i]["account"] = $row["class"];
					
					$i++;
				}
			}else{
				$jsonresult = 'failed';
			}
		break;
	}
	
	$json = json_encode($ret_data);
	echo $json;
	$conn->close();	
?>
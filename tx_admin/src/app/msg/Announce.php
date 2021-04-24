<?php
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	require ("../../../conn.php");
	error_reporting(0);
	date_default_timezone_set('PRC');
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag) {

		case 'getAnnounce':
			$sql = "SELECT * FROM tb_announce ";
			$result = $conn->query($sql);
			if($result->num_rows>0){
				$res = array();
				$resData = array();
				$i=0;
				while($rows=$result->fetch_assoc()){
					$resData["id"] = $rows["id"];
					$resData["timeStamp"] = $rows["timeStamp"];
					$resData["title"] = $rows["title"];
					$resData["content"] = $rows["content"];
					$res[$i] = $resData;
					$i++;
				}
				$data["data"]=$res;
			}else{
				$data["code"] = 0;
				$data["msg"] = "暂无公告！";
			}
			echo json_encode($data);
		break;
	}
?>
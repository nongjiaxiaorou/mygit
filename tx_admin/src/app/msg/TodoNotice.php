<?php
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	require ("../../../conn.php");
	error_reporting(0);
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	$data = array(
	"code"=>1,
	"msg"=>"",
	"data"=>[]
	);
	
	switch($flag) {
		//获取消息
		case 'getNotice':
			$startTime = isset($_POST["startTime"]) ? $_POST["startTime"] : '';
			$endTime = isset($_POST["endTime"]) ? $_POST["endTime"] : '';
			$messagetype = isset($_POST["messagetype"]) ? $_POST["messagetype"] : '';
			$userId = isset($_POST["userId"]) ? $_POST["userId"] : '';
			if($messagetype==''){
				$str1 = "";
			}else{
				$str1 = "AND `messagetype` ='$messagetype' ";
			}
			if($startTime==''&&$endTime==''){
				$str2 = "";
			}else{
				$str2 = "AND `time` >='$startTime' AND `time` <='$endTime'";
			}
			$sql = "SELECT * FROM tb_msg_notice WHERE userId = '$userId' and `delete` = '0' ".$str1." ".$str2." order by `time` desc";
			$result = $conn->query($sql);
			if($result->num_rows>0){
				$res = array();
				$resData = array();
				$i=0;
				while($rows=$result->fetch_assoc()){
					$resData["id"] = $rows["id"];
					$resData["content"] = $rows["content"];
					$resData["proState"] = $rows["proState"];
					$resData["cardId"] = $rows["cardId"];
					$resData["cardTimeStamp"] = $rows["cardTimeStamp"];
					$resData["time"] = $rows["time"];
					$resData["messagetype"] = $rows["messagetype"];
					$resData["title"] = $rows["title"];
					$resData["read"] = $rows["read"];
					$res[$i] = $resData;
					$i++;
				}
				$data["data"]=$res;
			}else{
				$data["code"] = 0;
				$data["msg"] = "暂无消息！";
			}
			echo json_encode($data);
		break;
		//删除浇筑令
		case 'deleteNotice':
		    $id = isset($_POST["id"])?$_POST["id"] : '';
		    
		    $sql = "SELECT * from tb_msg_notice where id = '$id'";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$sql = "update  tb_msg_notice set `delete` = '1' where id = '$id' ";
				$res = $conn -> query($sql);
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
		break;
		//消息已读
		case 'changeRed':
		    $id = isset($_POST["id"])?$_POST["id"] : '';
		    
		    $sql = "SELECT * from tb_msg_notice where id = '$id'";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$sql = "update  tb_msg_notice set `read` = '1' where id = '$id' ";
				$res = $conn -> query($sql);
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
	}
?>
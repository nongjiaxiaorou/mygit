<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);

	switch($flag){
		//修改部门公告
		case 'editAnnouncement':
			
			$formData = isset($_POST["formData"])?$_POST["formData"]:'';
			$formData = json_decode($formData);
			$title = $formData->title;
			$content = $formData->content;
			$timeStamp = $formData->timeStamp;

			$sql = "SELECT * FROM tb_announce WHERE timeStamp='$timeStamp'";
			$result = $conn->query($sql);
			if($result->num_rows>0){
				$sql = "UPDATE tb_announce SET title = '$title',content = '$content' WHERE timeStamp = '$timeStamp'";
				$result = $conn->query($sql);
				$data['code'] = 1;
			}else{
				$data['code'] = 0;
			}
		break;
		
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	
?>
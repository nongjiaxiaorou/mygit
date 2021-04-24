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
		//新增部门公告
		case 'addAnnouncement':
			
			$formData = isset($_POST["formData"])?$_POST["formData"]:'';
			$timeStamp = isset($_POST["timeStamp"])?$_POST["timeStamp"]:'';
			$formData = json_decode($formData);
			$title = $formData->title;
			$content = $formData->content;

			$sql = "SELECT * FROM tb_announce WHERE timeStamp='$timeStamp'";
			$result = $conn->query($sql);
			if($result->num_rows==0){
				$sql = "INSERT INTO tb_announce SET timeStamp = '$timeStamp',title = '$title',content = '$content'";
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
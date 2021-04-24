<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$ret_data = array();
	switch($flag){
		//获取巡查小组信息
		case 'getAdminData':
			$taskTimeStamp = isset($_POST["taskTimeStamp"])?$_POST["taskTimeStamp"]:'';
			
			$sql="SELECT a.companyRank, a.groupLeader, b.qualityPic, b.qualityReplyPic, b.constructPic, b.constructReplyPic, b.filePic, b.fileReplyPic FROM tb_quality_inspect_task AS a INNER JOIN tb_quality_inspect_question_pic AS b ON a.`timeStamp` = b.taskTimeStamp AND a.`timeStamp` = '$taskTimeStamp' "; 
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				$i = 0;
				while ($row = $result -> fetch_assoc()) {
					$ret_data["data"]["companyRank"] = $row["companyRank"];
					$ret_data["data"]["groupLeader"] = $row["groupLeader"];
					$ret_data["data"]["qualityPic"] = $row["qualityPic"];
					$ret_data["data"]["qualityReplyPic"] = $row["qualityReplyPic"];
					$ret_data["data"]["constructPic"] = $row["constructPic"];
					$ret_data["data"]["constructReplyPic"] = $row["constructReplyPic"];
					$ret_data["data"]["filePic"] = $row["filePic"];
					$ret_data["data"]["fileReplyPic"] = $row["fileReplyPic"];
					$ret_data["state"] = 'success';
					$i++;
				}
			}else{
				$ret_data['data'] = [];
				$ret_data["state"] = 'error';
			}
		break;
	}
	
	$json = json_encode($ret_data);
	echo $json;
	$conn->close();	
?>
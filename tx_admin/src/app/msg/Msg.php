<?php
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	require ("../../../conn.php");
	error_reporting(0);
	date_default_timezone_set('PRC');
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag) {

		case 'getMsgNum':
			$userId = isset($_POST["userId"]) ? $_POST["userId"] : '';
			
			//获取质量预警（目前：问题隐患/整改闭合率/每周巡检次数）
			$sql1 = "SELECT * FROM tb_msg_warning ";
			$result1 = $conn -> query($sql1);
			if ($result1 -> num_rows > 0) {
				$i=0;$j=0;
				$ret_data['warningTotal'] = strval($result1 -> num_rows);
				while ($row = $result1 -> fetch_assoc()) {
					$isReadArr = explode('/', $row["isRead"]);
					$isin = in_array($userId,$isReadArr);
					if(!$isin){//判断该用户是否已读 （删除的必定已读）
						$j++;
					}
					$i++;
					$ret_data["states"] = 'success';
				}
				$ret_data['warning'] = strval($j);
			}else{
				$ret_data['warning'] = '0';
				$ret_data["warningStates"] = 'error';
			}
			
			//获取通知待办消息数量（未删除未读）
			$sql2 = "SELECT * FROM tb_msg_notice WHERE userId = '$userId' AND `delete` = '0' ";
			$result2 = $conn -> query($sql2);
			if ($result2 -> num_rows > 0) {
				$i=0;$j=0;
				$ret_data['noticeTotal'] = strval($result2 -> num_rows);
				while ($row = $result2 -> fetch_assoc()) {
					$isReadArr = explode('/', $row["isRead"]);
					$isin = in_array($userId,$isReadArr);
					if(!$row["read"]){//判断该用户是否已读 （删除的必定已读）
						$j++;
					}
					$i++;
				}
				$ret_data['notice'] = strval($j);
			}else{
				$ret_data['notice'] = '0';
				$ret_data["noticeStates"] = 'error';
			}
			
			//获取部门公告数量
			$sql3 = "SELECT * FROM `tb_announce` "; 
			$result3 = $conn->query($sql3);
			if ($result3 -> num_rows > 0) {
				$ret_data['announceTotal'] = strval($result3 -> num_rows);
			}else{
				$ret_data['announce'] = '0';
				$ret_data["announceStates"] = 'error';
			}
		break;
	}
	$conn -> close();
	$json = json_encode($ret_data);
	echo $json;
?>
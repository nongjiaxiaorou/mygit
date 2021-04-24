<?php
header("Access-Control-Allow-Origin: *");
// 允许任意域名发起的跨域请求
require ("../../../../conn.php");
error_reporting(0);
date_default_timezone_set('PRC');
$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
$cardId = isset($_POST["cardId"]) ? $_POST["cardId"] : '';

$sql0 = "SELECT  * FROM `tb_weekly_file_notice` WHERE id = '$cardId'";
$result0 = $conn -> query($sql0);
$row0 = $result0 -> fetch_assoc();
$timeStamp = $row0["timeStamp"];

switch($flag) {
	//获取资料照片
	case 'getFilePic' :
		
		$sql1 = "SELECT  * FROM `tb_weekly_file_notice_filepic` WHERE noticeTimeStamp = '$timeStamp' ORDER BY `id` ASC";
		$result1 = $conn -> query($sql1);
		if ($result1 -> num_rows > 0) {
			$resData = array();
			$i = 0;
			while ($row1 = $result1 -> fetch_assoc()) {
				$resData["id"][$i] = $row1["id"];
				$resData["problemPic"][$i] = $row1["problemPic"];
				$resData["meetingPic"][$i] = $row1["meetingPic"];
				$resData["summaryPic"][$i] = $row1["summaryPic"];
				$resData["otherPic"][$i] = $row1["otherPic"];
				$resData["code"] = 1;
				$i++;
			}
		} else {
			$resData["code"] = 0;
		}
		$resData["timeStamp"] = $timeStamp;
		echo json_encode($resData);
		break;
	//获取资料照片
	case 'changeNoticeState' :

		$sjc = $_POST["sjc"];
		$sql1 = "SELECT a.noticeState FROM tb_weekly_file_notice AS a WHERE `timeStamp` = '$timeStamp'";
		$result1 = $conn -> query($sql1);
		$row1 = $result1 -> fetch_assoc();
		if ($row1['noticeState']=='整改中') {
			$sql = "UPDATE  `tb_weekly_file_notice` SET `noticeState`='已完成' WHERE `timeStamp` = '$timeStamp'";
			$result = $conn -> query($sql);
		} else {
			$resData["states"] = '未下发';
		}


		echo json_encode($resData);
		break;
}
?>
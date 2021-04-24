<?php
header("Access-Control-Allow-Origin: *");
// 允许任意域名发起的跨域请求
require ("../../../../conn.php");
error_reporting(0);
date_default_timezone_set('PRC');
$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
$cardId = isset($_POST["cardId"]) ? $_POST["cardId"] : '';

$sql0 = "SELECT  * FROM `tb_weekly_scene_notice` WHERE id = '$cardId'";
$result0 = $conn -> query($sql0);
$row0 = $result0 -> fetch_assoc();
$timeStamp = $row0["timeStamp"];

function imgToBase64($img_file, $file_patch) {
	$img_base64 = '';
	if ($img_file) {
		$img_file = '../../../images/app_pic/' . $file_patch . $img_file;
		if (file_exists($img_file)) {
			$app_img_file = $img_file;
			// 图片路径
			$img_info = getimagesize($app_img_file);
			// 取得图片的大小，类型等
			//echo '<pre>' . print_r($img_info, true) . '</pre><br>';
			$fp = fopen($app_img_file, "r");
			// 图片是否可读权限
			if ($fp) {
				$filesize = filesize($app_img_file);
				$content = fread($fp, $filesize);
				$file_content = chunk_split(base64_encode($content));
				// base64编码
				switch ($img_info[2]) {//判读图片类型
					case 1 :
						$img_type = "gif";
						break;
					case 2 :
						$img_type = "jpg";
						break;
					case 3 :
						$img_type = "png";
						break;
				}
				$img_base64 = 'data:image/' . $img_type . ';base64,' . $file_content;
				//合成图片的base64编码
			}
			fclose($fp);
		} else {
			$img_base64 = null;
		}
	} else {
		$img_base64 = null;
	}
	//		echo $img_base64;
	return $img_base64;
	//返回图片的base64
}

switch($flag) {
	//获取违章数据库
	case 'getDefect' :

		$sql = "SELECT * FROM tb_weekly_scene_notice_defect where timeStamp = '$timeStamp' ORDER BY tb_weekly_scene_notice_defect.id DESC";

		$result = $conn -> query($sql);
		if ($result -> num_rows > 0) {
			$i = 0;
			while ($row = $result -> fetch_assoc()) {
				$ret_data["data"][$i]["id"] = $row["id"];
				$ret_data["data"][$i]["defect"] = $row["defect"];
				$ret_data["data"][$i]["timeStamp"] = $row["timeStamp"];
				$ret_data["data"][$i]["createTime"] = $row["createTime"];
				$ret_data["data"][$i]["object"] = $row["object"];
				$ret_data["data"][$i]["endTime"] = $row0["endTime"];
				$ret_data["data"][$i]["database"] = $row0["database"];
				$ret_data["data"][$i]["majorCate"] = $row0["majorCate"];
				$ret_data["data"][$i]["timeStamp"] = $row0["timeStamp"];

				$i++;
			}
		}
		$conn -> close();
		$json = json_encode($ret_data);
		echo $json;
		break;
	//获取资料照片
	case 'getFilePic' :

		$sql1 = "SELECT  * FROM `tb_weekly_scene_notice_filepic` WHERE noticeTimeStamp = '$timeStamp' ORDER BY `id` ASC";
		$result1 = $conn -> query($sql1);
		if ($result1 -> num_rows > 0) {
			$resData = array();
			$i = 0;
			while ($row1 = $result1 -> fetch_assoc()) {
				$resData["id"][$i] = $row1["id"];
				$scenePicArr = explode('||', $row1["scenePic"]);
				for ($j = 0; $j < count($scenePicArr) - 1; $j++) {
					$resData["scenePic"][$j] = imgToBase64($scenePicArr[$j], 'filePic/');
				}
				$meetingPicArr = explode('||', $row1["meetingPic"]);
				for ($j = 0; $j < count($meetingPicArr) - 1; $j++) {
					$resData["meetingPic"][$j] = imgToBase64($meetingPicArr[$j], 'filePic/');
				}
				$summaryPicArr = explode('||', $row1["summaryPic"]);
				for ($j = 0; $j < count($summaryPicArr) - 1; $j++) {
					$resData["summaryPic"][$j] = imgToBase64($summaryPicArr[$j], 'filePic/');
				}
				$otherPicArr = explode('||', $row1["otherPic"]);
				for ($j = 0; $j < count($otherPicArr) - 1; $j++) {
					$resData["otherPic"][$j] = imgToBase64($otherPicArr[$j], 'filePic/');
				}

				$resData["code"] = 1;
				$i++;
			}
		} else {
			$resData["code"] = 0;
		}

		echo json_encode($resData);
		break;
	//完成缺陷
	case 'changeNoticeState' :

		$sql1 = "SELECT a.violationState FROM tb_weekly_scene_notice AS a WHERE `timeStamp` = '$timeStamp'";
		$result1 = $conn -> query($sql1);
		
		$row1 = $result1 -> fetch_assoc();
		if ($row1['violationState']=='整改中') {//整改中的通知单才能改变通知单状态
			$sql2 = "SELECT b.`timeStamp`, b.defect, b.defectState FROM tb_weekly_scene_notice_defect AS b WHERE b.`timeStamp` = '$timeStamp' AND b.defectState = '0' ";
			
			$result2 = $conn -> query($sql2);
			if($result2 -> num_rows > 0){//有未完成的缺陷的通知单 状态改为未完成
				$sql3 = "SELECT * FROM `tb_weekly_scene_notice` WHERE `timeStamp` = '$timeStamp'";
				$result3 = $conn -> query($sql3);
				
				if ($result3) {
					$sql4 = "UPDATE  `tb_weekly_scene_notice` SET `noticeState`='未完成' WHERE `timeStamp` = '$timeStamp'";
					$result4 = $conn -> query($sql4);
					$resData["states"] = 'success';
					$resData["msg"] = '未完成';
				} else {
					
				}
			}else{//没有未完成的缺陷通知单状态为已完成
				$sql1 = "SELECT * FROM `tb_weekly_scene_notice` WHERE `timeStamp` = '$timeStamp'";
				$result1 = $conn -> query($sql1);
				if ($result1) {
					$sql = "UPDATE  `tb_weekly_scene_notice` SET `noticeState`='已完成' WHERE `timeStamp` = '$timeStamp'";
					$result = $conn -> query($sql);
					$resData["states"] = 'success';
					$resData["msg"] = '已完成';
				} else {
					
				}
			}
		} else {
			$resData["states"] = 'error';
			$resData["msg"] = '未下达';
		}

		echo json_encode($resData);
		break;
}
?>
<?php
	require ("../../../../conn.php");
	error_reporting(E_ALL || ~E_NOTICE);
	date_default_timezone_set('PRC');
	$flag = $_POST['flag'];
	//	$flag ="pic_uload";
	//	imgToBase64('16039622130.jpg','defectPic/');
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
		//获取图片base64
		case 'getImageList' :
			$id = isset($_POST["id"]) ? $_POST["id"] : '';
	
			$sql1 = "SELECT * FROM tb_weekly_scene_notice_defect WHERE id='$id'";
			$result1 = $conn -> query($sql1);
			if ($result1 -> num_rows > 0) {
				$resData = array();
				$i = 0;
				while ($row1 = $result1 -> fetch_assoc()) {
					$resData["id"][$i] = $row1["id"];
					$resData["defectPicture"][$i] = $row1["defectPicture"];
					$resData["replyPicture"][$i] = $row1["replyPicture"];
	
					$resData["code"] = 1;
					$i++;
				}
			} else {
				$resData["code"] = 0;
			}
			echo json_encode($resData);
			break;
		//获取检查部位
		case 'getInspectPos' :
			$projectName = isset($_POST["projectName"]) ? $_POST["projectName"] : '';
	
			$sql1 = "SELECT  * FROM `tb_project_floor_information` WHERE `projectName` = '$projectName' ORDER BY `build` ASC";
			$result1 = $conn -> query($sql1);
			if ($result1 -> num_rows > 0) {
				$resData = array();
				$i = 0;
				while ($row1 = $result1 -> fetch_assoc()) {
					$resData["id"][$i] = $row1["id"];
					$resData["build"][$i] = $row1["build"];
					$resData["unitName"][$i] = $row1["unitName"];
					$resData["undergroundNumber"][$i] = $row1["undergroundNumber"];
					$resData["abovegroundNumber"][$i] = $row1["abovegroundNumber"];
	
					$resData["code"] = 1;
					$i++;
				}
			} else {
				$resData["code"] = 0;
			}
			//			print_r($resData);
			echo json_encode($resData);
			break;
		//保存记录详情
		case 'saveDefectDetail' :
			$formData = isset($_POST["formData"]) ? $_POST["formData"] : '';
			$id = isset($_POST["id"]) ? $_POST["id"] : '';
			$formData = json_decode($formData);
	
			$inspectPosition = $formData -> inspectPosition;
			$inspectPosition = implode('||', $inspectPosition);
			$responses = $formData -> responses;
			$rectifyPosition = $formData -> rectifyPosition;
			$rectifyPosition = implode('||', $rectifyPosition);
			$time = getdate();
			$timeNow = $time['year'] . "-" . $time['mon'] . "-" . $time['mday'] . "/" . $time['hours'] . ":" . $time['minutes'] . ":" . $time['seconds'];
	
			$sql1 = "UPDATE tb_weekly_scene_notice_defect SET inspectPosition = '$inspectPosition' ,responses = '$responses' ,rectifyPosition = '$rectifyPosition',replyTime = '$timeNow' WHERE id = '$id' ";
			$result1 = $conn -> query($sql1);
	
			$sql2 = "SELECT * FROM tb_weekly_scene_notice_defect AS a WHERE a.replyPicture IS NULL AND a.rectifyPosition IS NULL AND a.id = '$id'";
			$result2 = $conn -> query($sql2);
	
			if ($result2 -> num_rows > 0) {//未回复
	
			} else {//已回复  改变状态
				$sql3 = "UPDATE tb_weekly_scene_notice_defect SET defectState = '1' WHERE id = '$id' ";
				$result3 = $conn -> query($sql3);
			}
			echo json_encode($resData);
			break;
		//获取缺陷详情
		case 'getDefectDetail' :
			$id = isset($_POST["id"]) ? $_POST["id"] : '';
			
			$sql1 = "SELECT  * FROM `tb_weekly_scene_notice_defect` WHERE id = '$id' ORDER BY `id` ASC";
			$result1 = $conn -> query($sql1);
			if ($result1 -> num_rows > 0) {
				$resData = array();
				$i = 0;
				while ($row1 = $result1 -> fetch_assoc()) {
					$resData["id"][$i] = $row1["id"];
					$resData["responses"][$i] = $row1["responses"];
					$resData["inspectPosition"][$i] = $row1["inspectPosition"];
					$resData["rectifyPosition"][$i] = $row1["rectifyPosition"];
	
					$resData["code"] = 1;
					$i++;
				}
			} else {
				$resData["code"] = 0;
			}
			
			echo json_encode($resData);
			break;
	}
	
	$conn -> close();
	?>

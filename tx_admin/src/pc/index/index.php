<?php
	header("Access-Control-Allow-Origin: *"); //允许任意域名发起的跨域请求
	require("../../../conn.php");
	date_default_timezone_set('PRC');
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$ret_data = array();
	switch($flag){
		//获取部门公告
		case 'getAnnounce':

			$sql="SELECT * FROM `tb_announce` ORDER BY id DESC"; 
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				$i = 0;
				while ($row = $result -> fetch_assoc()) {
					$ret_data["data"][$i]["timeStamp"] = $row["timeStamp"];
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["title"] = $row["title"];
					$ret_data["data"][$i]["content"] = $row["content"];
					$ret_data["state"] = 'success';
					$i++;
				}
			}else{
				$ret_data["state"] = 'error';
			}
		break;
		//删除部门公告
		case 'deleteAnnounce':
			$id = isset($_POST["id"])?$_POST["id"]:'';	
			$sql="SELECT * FROM `tb_announce` WHERE id = '$id' "; 
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				$sql="DELETE FROM `tb_announce` WHERE id = '$id' "; 
				$result = $conn->query($sql);
				$ret_data["state"] = 'success';
				
			}else{
				$ret_data["state"] = 'error';
			}
		break;
		//获取通数据看板的指标
		case 'getNotice':
			$tody = date("Y-m-d");
			$tomorrow = date("Y-m-d",strtotime("+1 day"));
			/*获取每周巡检指标*/
			//现场质量巡检累计数量
			$sql1="SELECT * FROM tb_weekly_scene_notice"; 
			$sql2="SELECT * FROM tb_msg_notice"; 
			$result1 = $conn->query($sql1);
			$result2 = $conn->query($sql2);
			$noticeTotal = floatval($result2->num_rows); // 预警消息总数
			$sceneTotal = floatval($result1->num_rows);//现在质量巡检整改中累计数目
			$sceneTotal = round($sceneTotal/$noticeTotal*100,1);
			$ret_data['sceneTotal'] = $sceneTotal;
			
			/*获取项目总数*/
			$sql3="SELECT * FROM tb_project"; 
			$result3 = $conn->query($sql3);
			$projectNum = $result3->num_rows;//项目总数
			$projectNum = strval($projectNum);
			$ret_data['projectNum'] = $projectNum;
			
			//获取所有的整改中的项目
			$sql="SELECT * FROM tb_weekly_scene_notice WHERE violationState = '整改中'"; 
			$result = $conn->query($sql);
			$rectifyTotal = floatval($result->num_rows);
			$sql1="SELECT * FROM `view_hiddendanger` WHERE `state` = '整改中' and `itemType` = '重大问题' ";
			$result1 = $conn->query($sql1);  
			$bigProblem = floatval($result1->num_rows);
			$bigProblem = round($bigProblem/$rectifyTotal*100,1); 
			$ret_data['bigProblem'] = $bigProblem;
			
			// 获取重大问题百分数
			$sql2="SELECT * FROM `view_hiddendanger` WHERE `state` = '整改中' and `itemType` = '一般问题' ";
			$result2 = $conn->query($sql2);  
			$generalProblem = floatval($result2->num_rows);
			$generalProblem = round($generalProblem/$rectifyTotal*100,1); // 获取一般问题百分数
			$ret_data['generalProblem'] = $generalProblem;
	

			// 获取整改闭合问题总数
			$sql6="SELECT * FROM `view_rectificationclose`";
			$sql61="SELECT * FROM `view_rectificationclose`WHERE `state` = '已关闭'";
			$result6 = $conn->query($sql6);  
			$result61 = $conn->query($sql61);  
			$num1 = floatval($result6->num_rows);
			$num2 = floatval($result61->num_rows);
			$rectifyClose = round(($num2/$num1)*100,1);
			$ret_data['rectifyClose'] = $rectifyClose;

			/*获取通知待办消息数量（未删除未读）*/
			$userId = isset($_POST["userId"])?$_POST["userId"]:'';
			
			$sql4 = "SELECT * FROM tb_msg_notice WHERE userId = '$userId' AND `delete` = '0' ";
			$result4 = $conn -> query($sql4);
			if ($result4 -> num_rows > 0) {
				$i=0;$j=0;
				$ret_data['noticeTotal'] = strval($result4 -> num_rows);
				while ($row = $result4 -> fetch_assoc()) {
					$isReadArr = explode('/', $row["read"]);
					$isin = in_array($userId,$isReadArr);
					if(!$row["read"]){ // 判断该用户是否已读 （删除的必定已读）
						$j++;
					}
					$i++;
				}
				$ret_data['notice'] = strval($j);
			}else{
				$ret_data['notice'] = '0';
				$ret_data["noticeStates"] = 'error';
			}
			
			//获取质量预警（目前：问题隐患/整改闭合率/每周巡检次数）
			$sql1 = "SELECT * FROM tb_msg_warning ";
			$result1 = $conn -> query($sql1);
			if ($result1 -> num_rows > 0) {
				$i=0;$j=0;
				$ret_data['warningTotal'] = strval($result1 -> num_rows);
				while ($row = $result1 -> fetch_assoc()) {
					$isReadArr = explode('/', $row["isRead"]);
					$isin = in_array($userId,$isReadArr);
					if($row["isRead"]==null){//判断该用户是否已读 （删除的必定已读）
						$j++;
					}else {
						$i++;
					}
					$ret_data["states"] = 'success';
				}
				$ret_data['warning'] = strval($j);
			}else{
				$ret_data['warning'] = '0';
				$ret_data["warningStates"] = 'error';
			}	
		break;
		// 获取每周预警图表数据
		case 'getChart': 
			$month = [];
			$lastweek = [];
			for($i = 0 ; $i < 4 ; $i++)
			{
				$month[$i] = strval(date('Y-m',strtotime("-$i month")));
				$sql = "SELECT * FROM `tb_msg_warning` WHERE  warningTime like '%$month[$i]%' AND warningCate = '整改闭合率'";
				$result = $conn -> query($sql);
				$charRectifyClose_MM =  $result->num_rows;
				$ret_data['charRectifyClose_MM'][$i] = strval($charRectifyClose_MM);
				$sql1 = "SELECT * FROM `view_hiddendanger` WHERE  uploadDate like '%$month[$i]%' AND `state` = '整改中' and `itemType` = '重大问题'";
				$result1 = $conn -> query($sql1);
				$charBigProblem_MM =  $result1->num_rows;
				$ret_data['charBigProblem_MM'][$i] = strval($charBigProblem_MM);
				$sql2 = "SELECT * FROM `view_hiddendanger` WHERE  uploadDate like '%$month[$i]%' AND `state` = '整改中' and `itemType` = '一般问题'";
				$result2 = $conn -> query($sql2);
				$charProblem_MM =  $result2->num_rows;
				$ret_data['charProblem_MM'][$i] = strval($charProblem_MM);
				// $ret_data['month'][$i] = $month[$i];
			}
			for ($j = 0 ;$j < 7 ; $j++){
				$lastweek[$j] = strval(date('Y-m-d',strtotime("-$j day")));
				$ret_data['lastweek'][$j] = $lastweek[$j];
				$sql = "SELECT * FROM `tb_msg_warning` WHERE  warningTime like '%$lastweek[$j]%' AND warningCate = '整改闭合率'";
				$result = $conn -> query($sql);
				$charRectifyClose_DD =  $result->num_rows;
				$ret_data['charRectifyClose_DD'][$j] = strval($charRectifyClose_DD);
				$sql1 = "SELECT * FROM `view_hiddendanger` WHERE  uploadDate like '%$lastweek[$j]%' AND `state` = '整改中' and `itemType` = '重大问题'";
				$result1 = $conn -> query($sql1);
				$charBigProblem_DD =  $result1->num_rows;
				$ret_data['charBigProblem_DD'][$j] = strval($charBigProblem_DD);		
				$sql2 = "SELECT * FROM `view_hiddendanger` WHERE  uploadDate like '%$lastweek[$j]%' AND `state` = '整改中' and `itemType` = '一般问题'";
				$result2 = $conn -> query($sql2);
				$charProblem_DD =  $result2->num_rows;
				$ret_data['charProblem_DD'][$j] = strval($charProblem_DD);		
			}
			// echo json_encode($charRectifyClose);
		break;
			
	}
	
	$json = json_encode($ret_data);
	echo $json;
	$conn->close();	
?>
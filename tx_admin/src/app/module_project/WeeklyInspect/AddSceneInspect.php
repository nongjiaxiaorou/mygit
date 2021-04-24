<?php
header("Access-Control-Allow-Origin: *");
// 允许任意域名发起的跨域请求
require ("../../../../conn.php");
error_reporting(0);
date_default_timezone_set('PRC');
$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';

switch($flag) {
	//获取通知单编码
	case 'getInspectCode' :

		//按照同欣要求 编号体系应该为 部门+日期(8位数字)+流水号(3位数字)
		$projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
		$nowDateStr = isset($_POST["nowDateStr"]) ? $_POST["nowDateStr"] : '';//当前日期串

		//查找通知单表-取出最后一条记录
		$sql1 = "SELECT * FROM 	tb_weekly_scene_notice WHERE projectId = '" . $projectId . "' AND inspectCode LIKE '%" . $nowDateStr . "%' ORDER BY `id` DESC LIMIT 1";
		$result1 = $conn -> query($sql1);
		if ($result1 -> num_rows > 0) {
			while ($row1 = $result1 -> fetch_assoc()) {
				//判断编号的主体和内容是不是最新的主体和内容
				$serialNumber = explode("-", $row1["inspectCode"]);
				$arrayElementOneValue = $serialNumber[0];//当前日期串
				$arrayElementTwoValue = $serialNumber[1];//数字内容
				$arrayElementThreeValue = $serialNumber[2];//工程id

				//判断第一个值和第二个值是否等于编号的主体和内容
				if ($arrayElementOneValue == $nowDateStr) {//第二个值的判断
					//将 0008 转换成数字 8（0008在调试中是最后一张通知单，那么现在下张新的通知单应该是0009）
					$figure = intval($arrayElementTwoValue);
					$figureNext = $figure + 1;
					//判断 $figureNext 是不是一个数字还是两个或多个数字
					$figureNextSwitchString = strval($figureNext);
					//将数字类型的值转换成字符串类型的值
					$switchStringLength = strlen($figureNextSwitchString);
					//数字长度
					//使用 switch 语句来判断更为合适    1/2/3/4是数字的位数
					switch ($switchStringLength) {
						case 1 :
							$bianha = '00' . $figureNext;
							break;
						case 2 :
							$bianha = '0' . $figureNext;
							break;
						default :
							$bianha = $figureNext;
					}
				} else {
					//只满足第一个值符合的情况，就重新引用新的主体和内容
					$bianha = "001";
				}
				//拼接编号
				$number = $bianha;
			}
		} else {
			//没有记录的情况下 编号从 0001开始
			$number = '001';
		}

		$jsonresult = 'success';
		$otherdate = '{"result":"' . $jsonresult . '",
					"bianhao":"' . $number . '"
					}';
		$json = '[' . $sqldate . $otherdate . ']';
		echo $json;
		break;
	//根据所选违章数据库获取对应分部分项
	case 'getSubItem':
		$depotName = isset($_POST["depotName"]) ? $_POST["depotName"] : '';

		$sql = "SELECT a.subItems, a.majorCategories, b.id FROM tb_system_database AS a INNER JOIN tb_system_database_depot AS b ON a.depotId = b.id AND b.depotName = '$depotName' GROUP BY a.subItems ORDER BY a.subItems ASC";
		$result = $conn -> query($sql);
		if ($result -> num_rows > 0) {
			$i=0;
			while ($row = $result -> fetch_assoc()) {
				$ret_data["data"][$i]["subItems"] = $row["subItems"];
				$i++;	
			}
		}
		$conn -> close();
		$json = json_encode($ret_data);
		echo $json;
		break;
	//新建现场质量巡检草稿通知单
	case 'saveSceneInspect':
		$formData = isset($_POST["formData"]) ? $_POST["formData"] : '';
		$buildSelData = isset($_POST["buildSelData"]) ? $_POST["buildSelData"] : '';
		$timeStamp = isset($_POST["timeStamp"]) ? $_POST["timeStamp"] : '';
		$time=getdate();
		$createTime=$time['year']."-".$time['mon']."-".$time['mday']."/".$time['hours'].":".$time['minutes'].":".$time['seconds'];
		
		$formData = json_decode($formData);
		$projectId = $formData->projectId;
		$inspectLevel = $formData->inspectLevel;
		$inspectCate = $formData->inspectCate;
		$inspectCode = $formData->inspectCode;
		$inspectUnit = $formData->inspectUnit;
		$inspectObj = $formData->inspectObj;
		$majorCate = $formData->majorCate;//数组
		$majorCate = implode("||", $majorCate);
		$database = $formData->database;
		$subItem = $formData->subItem;//数组//检查类型（分部分项）
		$subItem = implode('||', $subItem);
		$violationState = $formData->violationState;
		$leaderPhone = $formData->leaderPhone;
		$violationItem = $formData->violationItem;//数组
		$violationItemStr = implode("||", $violationItem);
		$inspectDate = $formData->inspectDate;
		$endDate = $formData->endDate;
		
		$buildSelData = json_decode($buildSelData);
		$section = $buildSelData->section;
		$build = $buildSelData->build;
		$floor = $buildSelData->floor;
		$unit = $buildSelData->unit;
		//插入通知单
		$sql = "INSERT INTO tb_weekly_scene_notice SET `timeStamp` = '$timeStamp',projectId = '$projectId',inspectCode = '$inspectCode',projectName = '$inspectObj',inspectLevel = '$inspectLevel',inspectCate = '$inspectCate',inspectUnit = '$inspectUnit',inspectObj = '$inspectObj',`database` = '$database',subItem = '$subItem',majorCate = '$majorCate',violationItem = '$violationItemStr',inspectDate = '$inspectDate',violationState = '$violationState',endTime = '$endDate',creatorPhone = '$leaderPhone',createTime = '$createTime',noticeState = '草稿',section ='$section',build = '$build',floor = '$floor',unit = '$unit' ";
		$result = $conn -> query($sql);
		
		//插入资料照片
		$sql = "INSERT INTO tb_weekly_scene_notice_filepic SET `noticeTimeStamp` = '$timeStamp',projectId = '$projectId',inspectCode = '$inspectCode',projectName = '$inspectObj'";
		$result = $conn -> query($sql);
		
		//循环插入通知单对应缺陷（违章条目）
		for($i=0;$i<count($violationItem);$i++){
			$sql = "INSERT INTO tb_weekly_scene_notice_defect SET `timeStamp` = '$timeStamp',`projectId` = '$projectId',`inspectCode` = '$inspectCode',`projectName` = '$inspectObj',`defect`='".$violationItem[$i]."',`object` = '$inspectObj',`database` = '$database',`createTime` = '$createTime',`defectState` = '0'";
			$result = $conn -> query($sql);
		}
		
		$conn -> close();
		$json = json_encode($ret_data);
		echo $json;
		break;
}
?>
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
		$sql1 = "SELECT * FROM 	tb_weekly_file_notice WHERE projectId = '" . $projectId . "' AND inspectCode LIKE '%" . $nowDateStr . "%' ORDER BY `id` DESC LIMIT 1";
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
							$bianha = '00' . $figureNext;
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
	//新建现场质量巡检草稿通知单
	case 'saveFileInspect':
		$formData = isset($_POST["formData"]) ? $_POST["formData"] : '';
		$buildSelData = isset($_POST["buildSelData"]) ? $_POST["buildSelData"] : '';
		$time=getdate();
		$createTime=$time['year']."-".$time['mon']."-".$time['mday']."/".$time['hours'].":".$time['minutes'].":".$time['seconds'];
		
		$formData = json_decode($formData);
		$projectId = $formData->projectId;
		$inspectCode = $formData->inspectCode;
		$inspectPerson = $formData->inspectPerson;
		$fileName = $formData->fileName;
		$inspectDate = $formData->inspectDate;
		$timeStamp = $formData->timeStamp;
		
		$buildSelData = json_decode($buildSelData);
		$section = $buildSelData->section;
		$build = $buildSelData->build;
		$floor = $buildSelData->floor;
		$unit = $buildSelData->unit;
		
		//插入通知单
		$sql = "INSERT INTO tb_weekly_file_notice SET `timeStamp` = '$timeStamp',projectId = '$projectId',projectName = (SELECT projectName FROM tb_project WHERE id = '$projectId'),inspectCode = '$inspectCode',fileName = '$fileName',inspectPerson = '$inspectPerson',createTime = '$createTime',inspectDate = '$inspectDate',noticeState = '草稿',section ='$section',build = '$build',floor = '$floor',unit = '$unit' ";
		$result = $conn -> query($sql);
		//插入资料照片
		$sql = "INSERT INTO tb_weekly_file_notice_filepic SET `noticeTimeStamp` = '$timeStamp',projectId = '$projectId',inspectCode = '$inspectCode',fileName = '$fileName'";
		$result = $conn -> query($sql);
		
		$conn -> close();
		$json = json_encode($ret_data);
		echo $json;
		break;
}
?>
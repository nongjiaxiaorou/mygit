<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../../conn.php");
//	error_reporting( E_ALL&~E_NOTICE );
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	date_default_timezone_set('PRC'); //东八时区 
	//生成时间戳
	function msectime() {
   		list($msec, $sec) = explode(' ', microtime());
   		return  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
	}
	switch($flag){
		//保存通知单以及图片
		case 'saveViolationPic':
			$formData = isset($_POST["formData"]) ? $_POST["formData"] : '';
			$buildInfo = isset($_POST["buildInfo"]) ? $_POST["buildInfo"] : '';
			$projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$number = isset($_POST["number"]) ? $_POST["number"] : '';
			$formData = json_decode($formData);
			$buildInfo = json_decode($buildInfo);
			$timeStamp = msectime();
			$noticeNumber = $formData->noticeNumber;
			$inspectLevel = $formData->inspectLevel;
			$inspectItem = $formData->inspectItem;
			$inspectPosition = $formData->inspectPosition;
			$inspectPerson = $formData->inspectPerson;
			$inspectDate = $formData->inspectDate;
			$endDate = $formData->endDate;
			$inspectProcedure = $formData->inspectProcedure; 
			$itemList = $formData->itemList;
			$inspectProcedure = implode("||",$inspectProcedure);
			
			$sql1 = "INSERT INTO `tb_inspectaccept_notice` SET `projectId`='$projectId',`measureId`='$measureId',`noticeTimeStamp`='$timeStamp',`noticeNumber`='$noticeNumber',`inspectLevel`='$inspectLevel',`inspectPosition`='$inspectPosition',`inspectDate`='$inspectDate',`endDate`='$endDate',`state`='草稿',`inspectPersonName`='$inspectPerson',`inspectProcedure`='$inspectProcedure',`inspectItemHead`='$inspectItem',`isDelete`='0',`isRead`='0',`number`='$number'";
			$result1 = $conn->query($sql1);
			if($result1){
				if(count($itemList)>0){
					$img_path='../../../images/app_pic/inspectPic/';
					
					$arr = [];
					$now_time = time();
					$now_date = date('Y-m-d H:i:s',$now_time);
					$end_time = date('Y-m-d H:i:s',strtotime("+3 day"));
					$buildInfo = $buildInfo->section . "||" . $buildInfo->build;
					$i=0;
					foreach($itemList as $key=>$val){
						$base = $val->picPath;
						$images_name = "";
						$images_name =  $timeStamp.$i.'.'.'jpg';
						$base64 = explode(",",$base);
						$data1 = base64_decode($base64[1]);
						file_put_contents($img_path.$images_name, $data1);
						$type = $val->title;
						$number = $val->number;
						$itemDescribe = $val->itemDescribe;
						$sql2 = "INSERT INTO `tb_inspectaccept_violationitem` SET `projectId`='$projectId',`noticeTimeStamp`='$timeStamp',`itemType`='$type',`violationContent`='$itemDescribe',`picFile`='$images_name',`uploadDate`='$now_date',`createTime`='$now_date',`endDate`='$end_time',`number`='$number',`buildInfo`='$buildInfo'";
						$data['sql2'] = $sql2;
						$i++;
					    $result2 = $conn->query($sql2);
					}
				}
			}else{
				$data["code"] = 0;
			}
			
		break;
		//修改通知单以及图片
		case 'alterViolationPic':
			$formData = isset($_POST["formData"]) ? $_POST["formData"] : '';
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$inspectCardParam = isset($_POST["inspectCardParam"]) ? $_POST["inspectCardParam"] : '';
			$formData = json_decode($formData);
			$timeStamp = msectime();
			$inspectLevel = $formData->inspectLevel;
			$inspectItem = $formData->inspectItem;
			$inspectPosition = $formData->inspectPosition;
			$inspectPerson = $formData->inspectPerson;
			$inspectDate = $formData->inspectDate;
			$endDate = $formData->endDate;
			$inspectProcedure = $formData->inspectProcedure;
			$itemList = $formData->itemList;
			
			$sql1 = "UPDATE `tb_inspectaccept_notice` SET `inspectLevel`='$inspectLevel',`inspectPosition`='$inspectPosition',`inspectDate`='$inspectDate',`endDate`='$endDate',`inspectPersonName`='$inspectPerson',`inspectProcedure`='$inspectProcedure',`inspectItemHead`='$inspectItem',`isDelete`='0',`isRead`='0' WHERE id='$inspectCardParam'";
			$result1 = $conn->query($sql1);
			if($result1){
//				if(count($itemList)>0){
//					$img_path='../../../images/app_pic/inspectPic/';
//					
//					$arr = [];
//					$now_time= time();
//					$now_date= date('Y-m-d H:i:s',$now_time);
//					$i=0;
//					foreach($itemList as $key=>$val){
//						$base = $val->picPath;
//						$images_name = "";
//						$images_name =  $timeStamp.$i.'.'.'jpg';
//						$base64 = explode(",",$base);
//						$data1 = base64_decode($base64[1]);
//						file_put_contents($img_path.$images_name, $data1);
//						$type = $val->title;
//						$number = $val->number;
//						$itemDescribe = $val->itemDescribe;
//						$sql2 = "INSERT INTO `tb_inspectaccept_violationitem` SET `projectId`='$projectId',`noticeTimeStamp`='$timeStamp',`itemType`='$type',`violationContent`='$itemDescribe',`picFile`='$images_name',`uploadDate`='$now_date',`createTime`='$now_date',`number`='$number'";
//						$i++;
//					    $result2 = $conn->query($sql2);
//					}
//				}
			}else{
				$data["code"] = 0;
			}
			
		break;
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	

?>
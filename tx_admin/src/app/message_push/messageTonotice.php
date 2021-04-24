<?php
	require ("../../../conn.php");
	error_reporting(E_ALL ^ E_NOTICE);
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	date_default_timezone_set('PRC'); //设置中国时区
	$date = date("Y-m-d H:i:s");
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	$selectPerson = isset($_POST["selectPerson"]) ? $_POST["selectPerson"] : '';
	$cardId = isset($_POST["cardId"]) ? $_POST["cardId"] : '';
	$cardTimeStamp = isset($_POST["cardTimeStamp"]) ? $_POST["cardTimeStamp"] : '';
	
	switch($flag){
		case 'pour':
			date_default_timezone_set('PRC'); //设置中国时区
			$Person = explode(",",$selectPerson);
			$time=date("Y-m-d H:i:s");
			$proState = '浇筑';
			$sql1="SELECT * FROM tb_concrete_pour WHERE id='$cardId'";
			$result1 = $conn->query($sql1);
			$row1=$result1->fetch_assoc();
			$projectName=$row1["projectName"];
			$build=$row1["build"];
			$floor=$row1["floor"];
			$unitName=$row1["unitName"];
			$projectId=$row1["projectId"];
			$content=$projectName.$build.$floor.$unitName."的砼浇筑签名";
			$position=$projectName.$build.$floor.$unitName;
			$title='混凝土浇筑令';
			$timeStamp=time().mt_rand(111, 999);
			echo 123;
			foreach($Person as $receiver){
				$receiver = explode('|', $receiver);
				$receiverPhone = $receiver[1];
				$receiverid = $receiver[3];
				$sql2="INSERT INTO tb_msg_notice ( `timeStamp`, `title`, `content`, `cardId`, `position`, `time`, `read`, `delete`, `receiver`, `userId`, `projectId`, `proState`, `messagetype` )
	VALUES
		( '$timeStamp', '$title', '$content', '$cardId', '$position', '$time', '0', '0', '$receiverPhone','$receiverid', '$projectId', '$proState', '砼浇筑管理' )";
			    $result2 = $conn->query($sql2);
				
			}
		break;
		case 'side':
			date_default_timezone_set('PRC'); //设置中国时区
			$Person = explode(",",$selectPerson);
			$time=date("Y-m-d H:i:s");
			$proState = '旁站';
			$sql1="SELECT * FROM tb_concrete_pour WHERE id='$cardId'";
			$result1 = $conn->query($sql1);
			$row1=$result1->fetch_assoc();
			$projectName=$row1["projectName"];
			$build=$row1["build"];
			$floor=$row1["floor"];
			$unitName=$row1["unitName"];
			$projectId=$row1["projectId"];
			$content=$projectName.$build.$floor.$unitName."的砼旁站通知";
			$position=$projectName.$build.$floor.$unitName;
			$title='旁站通知';
			$timeStamp=time().mt_rand(111, 999);
			foreach($Person as $receiver){
				$receiver = explode('|', $receiver);
				$receiverPhone = $receiver[1];
				$receiverid = $receiver[3];
				$sql2="INSERT INTO tb_msg_notice ( `timeStamp`, `title`, `content`, `cardId`, `position`, `time`, `read`, `delete`, `receiver`, `userId`, `projectId`, `proState`, `messagetype` )
	VALUES
		( '$timeStamp', '$title', '$content', '$cardId', '$position', '$time', '0', '0', '$receiverPhone','$receiverid', '$projectId', '$proState', '砼浇筑管理' )";
			    $result2 = $conn->query($sql2);
				
			}
		break;
		case 'removal':
			date_default_timezone_set('PRC'); //设置中国时区
			$Person = explode(",",$selectPerson);
			$time=date("Y-m-d H:i:s");
			$proState = '拆模';
			$sql1="SELECT * FROM tb_concrete_removal WHERE id='$cardId'";
			$result1 = $conn->query($sql1);
			$row1=$result1->fetch_assoc();
			$projectName=$row1["projectName"];
			$build=$row1["build"];
			$floor=$row1["floor"];
			$unitName=$row1["unitName"];
			$projectId=$row1["projectId"];
			$content=$projectName.$build.$floor.$unitName."的砼拆模通知";
			$position=$projectName.$build.$floor.$unitName;
			$title='拆模申请';
			$timeStamp=time().mt_rand(111, 999);
			
			foreach($Person as $receiver){
				$receiver = explode('|', $receiver);
				$receiverPhone = $receiver[1];
				$receiverid = $receiver[3];
				$sql2="INSERT INTO tb_msg_notice ( `timeStamp`, `title`, `content`, `cardId`, `position`, `time`, `read`, `delete`, `receiver`, `userId`, `projectId`, `proState`, `messagetype` )
	VALUES
		( '$timeStamp', '$title', '$content', '$cardId', '$position', '$time', '0', '0', '$receiverPhone','$receiverid', '$projectId', '$proState', '砼拆模管理' )";
			    $result2 = $conn->query($sql2);
				
			}
		break;
		//现在质量巡查草稿下发
		case 'completeInspectPush':
			$Person = explode(",",$selectPerson);
			$time=date("Y-m-d H:i:s");
			$date=date("Y-m-d");

			$sql1 = "SELECT * FROM tb_weekly_scene_notice WHERE id='$cardId'";
			$result1 = $conn->query($sql1);
			$row1 = $result1->fetch_assoc();
		
			$section = $row1["section"];
			$build = $row1["build"];
			$floor = $row1["floor"];
			$unitName  = $row1["unit"];
			$projectId = $row1["projectId"];
			$content = '关于'.$date.'的现场质量巡检的问题整改通知';
			$position = $projectName.$section.$build.$floor.$unitName;
			$timeStamp=time().mt_rand(111, 999);
			
			foreach($Person as $receiver){
				$receiver = explode('|', $receiver);
				$receiverPhone = $receiver[1];
				$receiverid = $receiver[3];
				$sql2="INSERT INTO tb_msg_notice SET timeStamp = '$timeStamp', title = '现场质量巡检' , `content` = '$content', `cardId` = '$cardId', `position` = '$position', `time` = '$time', `read` = '0', `delete` = '0', `receiver` = '$receiverPhone',`userId` = '$receiverid', `projectId` = '$projectId' , `messagetype` = '每周巡检' ";
			    $result2 = $conn->query($sql2);
				
			}
		break;
		//方案资料巡查草稿下发
		case 'completeFilePush':
			$Person = explode(",",$selectPerson);
			$time=date("Y-m-d H:i:s");
			$date=date("Y-m-d");

			$sql1 = "SELECT * FROM tb_weekly_file_notice WHERE id='$cardId'";
			$result1 = $conn->query($sql1);
			$row1 = $result1->fetch_assoc();
		
			$section = $row1["section"];
			$build = $row1["build"];
			$floor = $row1["floor"];
			$unitName  = $row1["unit"];
			$projectId = $row1["projectId"];
			$content = '关于'.$date.'的方案资料巡查的问题整改通知';
			$position = $projectName.$section.$build.$floor.$unitName;
			$timeStamp=time().mt_rand(111, 999);
			
			foreach($Person as $receiver){
				$receiver = explode('|', $receiver);
				$receiverPhone = $receiver[1];
				$receiverid = $receiver[3];
				$sql2="INSERT INTO tb_msg_notice SET timeStamp = '$timeStamp', title = '方案资料巡查' , `content` = '$content', `cardId` = '$cardId',cardTimeStamp = '".$row1["timeStamp"]."', `position` = '$position', `time` = '$time', `read` = '0', `delete` = '0', `receiver` = '$receiverPhone',`userId` = '$receiverid', `projectId` = '$projectId' , `messagetype` = '每周巡检' ";
			    $result2 = $conn->query($sql2);
				
			}
		break;
		//质量检查/检查评分汇总任务下发
		case 'completeInsScorePush':
			$Person = explode(",",$selectPerson);
			$time=date("Y-m-d H:i:s");
			$date=date("Y-m-d");

			$sql1 = "SELECT * FROM tb_quality_inspect_task WHERE `timeStamp` = '$cardTimeStamp'";
			$result1 = $conn->query($sql1);
			$row1 = $result1->fetch_assoc();
			$inspectObj = $row1["inspectObj"];
			$inspectName = $row1["inspectName"];
			$projectId = $row1["projectId"];
			$content = '关于'.$inspectObj.$inspectName.'的意见回复';
			$position = $projectName.$section.$build.$floor.$unitName;
			$timeStamp=time().mt_rand(111, 999);
			
			foreach($Person as $receiver){
				$receiver = explode('|', $receiver);
				$receiverPhone = $receiver[1];
				$receiverid = $receiver[3];
				$sql2="INSERT INTO tb_msg_notice SET timeStamp = '$timeStamp', title = '评分确认' , `content` = '$content', `cardId` = '$cardId',cardTimeStamp = '$cardTimeStamp', `time` = '$time', `read` = '0', `delete` = '0', `receiver` = '$receiverPhone',`userId` = '$receiverid', `projectId` = '$projectId' , `messagetype` = '质量检查' ";
			    $result2 = $conn->query($sql2);
				//下发给项目经理，即外部检查
				$sql3="INSERT INTO tb_msg_external_inspect SET timeStamp = '$timeStamp',taskTimeStamp='$cardTimeStamp', inspectName = '".$row1['inspectName']."' , `taskId` = '".$row1['taskId']."', `groupId` = '".$row1['groupId']."',inspectObj = '".$row1['inspectObj']."', `groupLeader` = '".$row1['groupLeader']."', `deputyLeader` = '".$row1['deputyLeader']."', `company` = '".$row1['company']."', `companyRank` = '".$row1['companyRank']."',`projectId` = '".$row1['projectId']."', `rectifyState` = '整改中' , `msgReceiver` = '$receiverid' , `actualMarks` = '".$row1['actualMarks']."' , `correctMarks` = '".$row1['correctMarks']."' , `createTime` = '$time' , `isDelete` = '0' , `isRead` = '0' ";
				$result2 = $conn->query($sql3);
			}
		break;
		//质量检查/检查评分汇总下发
		// case 'completeInsScorePush':
		// 	$Person = explode(",",$selectPerson);
		// 	$time=date("Y-m-d H:i:s");
		// 	$date=date("Y-m-d");
		
		// 	$sql1 = "SELECT * FROM tb_quality_inspect_task WHERE `timeStamp` = '$cardTimeStamp'";
		// 	$result1 = $conn->query($sql1);
		// 	$row1 = $result1->fetch_assoc();
		// 	$timeStamp=time().mt_rand(111, 999);
			
		// 	foreach($Person as $receiver){
		// 		$receiver = explode('|', $receiver);
		// 		$receiverPhone = $receiver[1];
		// 		$receiverid = $receiver[3];
		// 		$sql2="INSERT INTO tb_msg_external_inspect SET timeStamp = '$timeStamp',taskTimeStamp='$cardTimeStamp', inspectName = '".$row1['inspectName']."' , `taskId` = '".$row1['taskId']."', `groupId` = '".$row1['groupId']."',inspectObj = '".$row1['inspectObj']."', `groupLeader` = '".$row1['groupLeader']."', `deputyLeader` = '".$row1['deputyLeader']."', `company` = '".$row1['company']."', `companyRank` = '".$row1['companyRank']."',`projectId` = '".$row1['projectId']."', `rectifyState` = '".$row1['rectifyState']."' , `msgReceiver` = '$receiverid' , `actualMarks` = '".$row1['actualMarks']."' , `correctMarks` = '".$row1['correctMarks']."' , `createTime` = '$time' , `isDelete` = '0' , `isRead` = '0' ";
		// 	    $result2 = $conn->query($sql2);
		// 	}
		// break;
	}
	

?>
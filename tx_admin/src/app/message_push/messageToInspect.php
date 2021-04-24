<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	
	switch($flag){
		//推送检查消息
		case 'InspectMessagePush':
			$cardTimeStamp = isset($_POST["cardTimeStamp"]) ? $_POST["cardTimeStamp"] : '';
			$selectPerson = isset($_POST["selectPerson"]) ? $_POST["selectPerson"] : '';
			$Person = explode(",",$selectPerson);
			$time=date("Y-m-d H:i:s");
			$date=date("Y-m-d");
			$sql1 = "SELECT * FROM tb_inspectaccept_notice AS a,tb_project AS b WHERE a.`noticeTimeStamp` = '$cardTimeStamp' AND a.projectId=b.id";
			$result1 = $conn->query($sql1);
			$row1 = $result1->fetch_assoc();
			$projectName = $row1["projectName"];
			$inspectPosition = $row1["inspectPosition"];
			$projectId = $row1["projectId"];
			$content = '关于'.$projectName.'-'.$inspectPosition.'的整改通知';
			$timeStamp=time().mt_rand(111, 999);
			$res = array();
			$resData = array();
            $i=0;
            if($result1->num_rows>0)	{
				$sql3="UPDATE tb_inspectaccept_notice SET state = '整改中' WHERE noticeTimeStamp='$cardTimeStamp' ";
				$result3 = $conn->query($sql3);
        	 	foreach($Person as $receiver){
        	 		$receiver = explode('|', $receiver);
        	 		$receiverPhone = $receiver[1];
        	 		$receiverid = $receiver[3];
        	 		$sql2="INSERT INTO tb_msg_notice SET timeStamp = '$timeStamp', title = '项目检查回复' , `content` = '$content',cardTimeStamp = '$cardTimeStamp', `time` = '$time', `read` = '0', `delete` = '0', `receiver` = '$receiverPhone',`userId` = '$receiverid', `projectId` = '$projectId' , `messagetype` = '项目检查', `position` = '$inspectPosition'";
        	 	    $result2 = $conn->query($sql2);
        	 	}
				$data['data'] = $res;
            }else {
				$data['code'] = 0;
			}
		break;
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	

?>
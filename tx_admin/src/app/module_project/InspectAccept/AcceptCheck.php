<?php
	require ("../../../../conn.php");
	error_reporting(E_ALL ^ E_NOTICE);
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	//生成时间戳
	function msectime() {
   		list($msec, $sec) = explode(' ', microtime());
   		return  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
	}
	
	switch($flag){
		case 'getPersonInfo':
			$inspectItem = isset($_POST["inspectItem"]) ? $_POST["inspectItem"] : '';
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$procedure = isset($_POST["procedure"]) ? $_POST["procedure"] : '';
			$procedure = preg_replace("/\\d+/",'', $procedure);
			$sql = "SELECT `acceptPostName`,`acceptPersonPhone`,`acceptProcess`,`acceptTimeStamp` FROM `tb_inspectaccept_processaccept` WHERE measureId='$measureId' AND `inspectItem`='$inspectItem' AND `acceptProcedure`='$procedure'";
			$result = $conn->query($sql);
			if($result->num_rows>0){
				$people = array();
				while($row=$result->fetch_assoc()){
					$people[$row["acceptPostName"]] = $row["acceptPersonPhone"];
					$data["acceptProcess"] = $row["acceptProcess"];
					$acceptTimeStamp = $row["acceptTimeStamp"];
				}
				$data["item"] = $people;
				
				
				$sql1 = "SELECT signNamePerson tb_msg_itemaccept WHERE acceptTimeStamp='$acceptTimeStamp' AND signNameBase IS NOT NULL AND signNameBase<>''";
		//		echo $sql;
				$result1 = $conn->query($sql1);
				if($result1->num_rows>0){
					$i=0;
					$name = array();
					while($rows1=$result1->fetch_assoc()){
						$number=$rows1["signNamePerson"];
						$time = $rows1["signNameTime"];
						$sql2 = "SELECT `name` FROM `tb_system_user`  WHERE ` `='$number'";
						$result2 = $conn->query($sql2);
						if($result2->num_rows>0){
							$rows2 = $result2->fetch_assoc();
							$name[$i] = $rows2["name"]."|".$number."|".$time;
						}
						$i++;
					}
					$data["personName"]=$name;
				}
				$sql3 = "SELECT qualityPersonOpinion,signNamePerson FROM tb_msg_itemaccept WHERE acceptTimeStamp='$acceptTimeStamp' AND qualityPersonOpinion<>''";
				$result3 = $conn->query($sql3);
				if($result3->num_rows>0){
					$yijian = array();
					$i=0;
					while($row3=$result3->fetch_assoc()){
						$number=$row3["signNamePerson"];
						$opinion = $row3["qualityPersonOpinion"];
							$yijian[$i] = $number."|".$opinion;
						$i++;
					}
					$data["opinion"]=$yijian;
				}
				$data["acceptTimeStamp"]=$acceptTimeStamp;
			}else{
				$data["code"] = 0;
			}
		break;
		case 'personSave':
			$acceptPositon = isset($_POST["acceptPositon"]) ? $_POST["acceptPositon"] : '';
			$acceptObject = isset($_POST["acceptObject"]) ? $_POST["acceptObject"] : '';
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$inspectItem = isset($_POST["inspectItem"]) ? $_POST["inspectItem"] : '';
			$procedure = isset($_POST["procedure"]) ? $_POST["procedure"] : '';
			$acceptProcess = isset($_POST["acceptProcess"]) ? $_POST["acceptProcess"] : '';
			$projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
			$allLevelPerson = isset($_POST["allLevelPerson"]) ? $_POST["allLevelPerson"] : '';
			$procedure=preg_replace("/\\d+/",'', $procedure);
			$millisecond = msectime();
			$allLevelPerson = json_decode($allLevelPerson,true);
			$num=0;
			$num = count($allLevelPerson["first"])>0?++$num:$num;
			$num = count($allLevelPerson["second"])>0?++$num:$num;
			$num = count($allLevelPerson["third"])>0?++$num:$num;
			$num = count($allLevelPerson["fouth"])>0?++$num:$num;
//			if($structurePosition=="点击选择结构部位"){
				$structurePosition='';
//			}
			$sql1 ="SELECT `acceptTimeStamp`,`acceptState` FROM `tb_inspectaccept_processaccept` WHERE measureId='$measureId' AND `inspectItem`='$inspectItem' AND `acceptProcedure`='$procedure'";
			$result1 = $conn->query($sql1);
			
			if($result1->num_rows>0){
		//		$row1 = $result1->fetch_assoc();
				while($row1=$result1->fetch_assoc()){
					if($row1["acceptState"]=="待验收"||$row1["acceptState"]=="验收中"){
						$sql2 ="DELETE FROM `tb_msg_itemaccept` WHERE acceptTimeStamp='".$row1["acceptTimeStamp"]."'";
						$conn->query($sql2);
						$sql3 ="DELETE FROM `tb_inspectaccept_processaccept` WHERE measureId='$measureId' AND acceptTimeStamp='".$row1["acceptTimeStamp"]."' AND `inspectItem`='$inspectItem' AND `acceptProcedure`='$procedure'";
						$conn->query($sql3);
					}
				}
			}
			
			$i = 1;
			foreach ($allLevelPerson as $key => $value) {
				$level = $num."-".$i;
				foreach ($value as $index => $res) {
//					print_r($res);
//					echo $res['userId'];
					$userId = $res['userId'];
					$acceptPostName = $res['title'];
					$sql2 = "SELECT * FROM tb_system_user WHERE id='$userId'";
					$result2 = $conn->query($sql2);
					$row2=$result2->fetch_assoc();
					$acceptPersonPhone = $row2["phone"];
					$sql1 = "REPLACE INTO tb_inspectaccept_processaccept SET acceptTimeStamp='$millisecond',measureId='$measureId',inspectItem='$inspectItem',timeStampStr='',acceptProcedure='$procedure',inspectPosition='$acceptPositon',acceptPersonId='$userId',acceptPersonPhone='$acceptPersonPhone',acceptState='待验收',level='$level',acceptProcess='$acceptProcess',acceptPostName='$acceptPostName',projectId='$projectId'";
					$result1 = $conn->query($sql1);
					if($result1){
						$data["code"] = 1;
					}else{
						$data["code"] = 0;
					}
				}
				$i++;
			}
		break;
		case 'getAcceptType':
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$sql = "SELECT COUNT(id) AS num,`number` FROM `tb_inspectaccept_notice` WHERE `measureId`='$measureId' GROUP BY `number`";
			$result = $conn->query($sql);
			if($result->num_rows>0){
				$arr = array();
				while($row=$result->fetch_assoc()){
					$number = $row["number"];
					$sqli = "SELECT `noticeTimeStamp` FROM `tb_inspectaccept_notice` WHERE `measureId`='$measureId' AND `number`='$number'";
					$result1 = $conn->query($sqli);
					if($result1->num_rows>0){
						$sjcArr = array();
						$j=0;
						while($rows=$result1->fetch_assoc()){
							$sjcArr[$j] = $rows["noticeTimeStamp"];
							$j++;
						}
						$sjc = implode(",",$sjcArr);
					}
					$arr[$number]=$row["num"]."|".$sjc;
				}
				$data['data']=$arr;
			}else{
				$data["code"] = 0;
			}
		break;
		case 'getInspectContent':
			$inspectTimeStamp = isset($_POST["inspectTimeStamp"]) ? $_POST["inspectTimeStamp"] : '';
			if($inspectTimeStamp==""){
				$sql1 =  "SELECT `number` FROM `tb_inspectaccept_notice` WHERE `noticeTimeStamp` in ('".$inspectTimeStamp."') GROUP BY `number`";
			}else{
				$sql1 =  "SELECT `number` FROM `tb_inspectaccept_notice` WHERE `noticeTimeStamp` in (".$inspectTimeStamp.") GROUP BY `number`";
			}
			$result1 = $conn->query($sql1);
			$row1=$result1->fetch_assoc();
			$sql = "SELECT `acceptProcess`,`inspectItemHead`,`number` FROM `tb_inspectaccept_procedure` WHERE `procedureName` LIKE '%".$row1["number"]."%' AND NOT ISNULL(`number`)";
			$result = $conn->query($sql);
			if($result->num_rows>0){
				
				$arr = array();
				$item = array();
				while($row=$result->fetch_assoc()){
					$arr["acceptProcess"]=$row["acceptProcess"];
					$number = $row["number"];
					$sqli = "SELECT `id`,`violationContent`,`rectificationStatus` FROM `tb_inspectaccept_violationitem` WHERE `noticeTimeStamp` in (".$inspectTimeStamp.") AND `number`='$number'";
					$result1 = $conn->query($sqli);
					if($result1->num_rows>0){
						$Arr = array();
						$i=0;
						while($rows=$result1->fetch_assoc()){
							$Arr[$i] = $rows["violationContent"]."&".$inspectTimeStamp."&".$rows["rectificationStatus"]."&".$rows["id"];
							$i++;
						}
						$item[$row["inspectItemHead"]] = $Arr;
					}
				}
				$data['acceptProcess']=$arr;
				$data["item"]=$item;
			}else{
				$data["code"] = 0;
			}
		break;
		case 'getAcceptTimeStamp':
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$procedure = isset($_POST["procedure"]) ? $_POST["procedure"] : '';
			$sql = "SELECT acceptTimeStamp FROM tb_inspectaccept_processaccept WHERE `measureId`='$measureId' AND `acceptProcedure`='$procedure' GROUP BY `acceptTimeStamp`";
			$result = $conn->query($sql);
			if($result->num_rows>0){
				$row=$result->fetch_assoc();
				$data['data']=$row["acceptTimeStamp"];
			}else{
				$data["code"] = 0;
			}
		break;
		case 'submitSure':
//			$ysdx = $_POST["ysdx"];
//			$cardid = $_POST["cardid"];
//			$ysgx = $_POST["ysgx"];
//			$jgbw = $_POST["jgbw"];
//			$sjc = $_POST["sjc"];
//			$hblc_str = $_POST["hblc_str"];
//			$gcid = $_POST["gcid"];
			$inspectItem = isset($_POST["inspectItem"]) ? $_POST["inspectItem"] : '';
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$procedure = isset($_POST["procedure"]) ? $_POST["procedure"] : '';
			$structurePositon = isset($_POST["structurePositon"]) ? $_POST["structurePositon"] : '';
			$projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
//			$acceptFloor = isset($_POST["acceptFloor"]) ? $_POST["acceptFloor"] : ''; //验收楼层 用||拼接
			
			
			$now_time= time();
			$now_date= date('Y-m-d H:i:s',$now_time);
			if($structurePositon==""){
				$sql = "SELECT * FROM `tb_inspectaccept_processaccept` WHERE measureId='$measureId' AND `inspectItem`='$inspectItem' AND `acceptProcedure`='$procedure' AND `level` LIKE '%-1%'";
				$result = $conn->query($sql);
				if($result->num_rows>0){
					$phone = array();
					$i=0;
					$j=0;
					if(strpos($acceptFloor,'层')!== false){
						$acceptFloor = explode("|",$acceptFloor);
						$timeStamp_arr = array();
						for($i=0;$i<count($acceptFloor)-1;$i++){
							$sql3 = "SELECT * FROM tb_inspectaccept_notice WHERE projectId='$projectId' AND inspectPosition='$acceptFloor[$i]' AND inspectItemHead='$inspectItem' AND inspectProcedure LIKE '%$procedure%'";
							$result3 = $conn->query($sql3);
							while($row3=$result3->fetch_assoc()){
								$timeStamp_arr[$j] = $row3["noticeTimeStamp"];
								$j++;
							}
						}
						$all_sjc = implode("||",$timeStamp_arr);
						$sql1 = "UPDATE `tb_inspectaccept_processaccept` SET `timeStampStr` = '$all_sjc' WHERE measureId='$measureId' AND `inspectItem`='$inspectItem' AND `acceptProcedure`='$procedure'";
					}else{
						$sql1 = "UPDATE `tb_inspectaccept_processaccept` SET `timeStampStr` = '$all_sjc' WHERE measureId='$measureId' AND `inspectItem`='$inspectItem' AND `acceptProcedure`='$procedure'";
					}
					$conn->query($sql1);
					while($row=$result->fetch_assoc()){
						$phone[$i] = $row["acceptPersonPhone"];
						$i++;
						
						$sql2 = "SELECT * FROM `tb_msg_itemaccept` WHERE acceptTimeStamp= '".$row["acceptTimeStamp"]."' AND acceptPersonId = '".$row["acceptPersonId"]."'";
						$result2 = $conn->query($sql2);
						if($result2->num_rows>0){
							$sqli = "UPDATE tb_msg_itemaccept SET isDelete='0',isRead='0',pushTime ='$now_date',timeStampStr ='$all_sjc' WHERE acceptTimeStamp= '".$row["acceptTimeStamp"]."' AND acceptPersonId = '".$row["acceptPersonId"]."' AND acceptFloor = '".$row["acceptFloor"]."'";
							$sql4 = "UPDATE tb_msg_notice SET read='0',delete='0',pushTime ='$now_date',timeStampStr ='$all_sjc' WHERE acceptTimeStamp= '".$row["acceptTimeStamp"]."' AND acceptPersonId = '".$row["acceptPersonId"]."' AND acceptFloor = '".$row["acceptFloor"]."'";
							$conn->query($sql4);
						}else{
							$sqli = "INSERT INTO tb_msg_itemaccept SET acceptTimeStamp='".$row["acceptTimeStamp"]."',signNameTime='',signNamePerson='".$row["acceptPersonPhone"]."',signNameBase='',isDelete='0',isRead='0',TimeStampStr='$all_sjc',level='".$row["level"]."',pushTime='$now_date',acceptFloor='".$row["acceptFloor"]."',acceptPersonId='".$row["acceptPersonId"]."'";
							$projectName = $row["projectName"];
							$inspectPosition = $row["inspectPosition"];
							$acceptPersonPhone = $row["acceptPersonPhone"];
							$acceptPersonId = $row["acceptPersonId"];
							$projectId = $row["projectId"];
							$content = '关于'.$projectName.'-'.$inspectPosition.'的验收通知';
							$timeStamp=time().mt_rand(111, 999);
							$now_time = time();
							$now_date = date('Y-m-d H:i:s',$now_time);
							$sql3 = "INSERT INTO tb_msg_notice SET timeStamp = '$timeStamp', title = '项目验收' , `content` = '$content',cardTimeStamp = '".$row["acceptTimeStamp"]."', `time` = '$now_date', `read` = '0', `delete` = '0', `receiver` = '$acceptPersonPhone',`userId` = '$acceptPersonId', `projectId` = '$projectId' , `messagetype` = '项目验收', `position` = '$inspectPosition'";
							$conn->query($sql3);
						}
						$conn->query($sqli);
					}
					$data["item"]=$phone;
				}else{
					$data["code"]=0;
				}
			}else{//暂时没有使用
				$sql = "SELECT `id`,`acceptTimeStamp`,`acceptPersonPhone`,`level`,`acceptFloor` FROM `tb_inspectaccept_processaccept` WHERE measureId='$measureId' AND `inspectItem`='$inspectItem' AND `acceptProcedure`='$procedure' AND `structurePosition`='$structurePositon' AND `level` LIKE '%-1%'";
				$result = $conn->query($sql);
				if($result->num_rows>0){
					$phone = array();
					$i=0;
					$sql1 = "UPDATE `tb_inspectaccept_processaccept` SET `timeStampStr` = '$sjc' WHERE cardID='$cardid' AND `inspectItem`='$inspectItem' AND `验收工序`='$ysgx' AND `structurePosition`='$jgbw'";
					$conn->query($sql1);
					while($row=$result->fetch_assoc()){
		//				$sql2 = "UPDATE `项目工序验收` SET 验收状态='验收中' WHERE `检查项目` = '$ysdx' AND `结构部位` = '$jgbw' AND `cardID`='$cardid'";
		//				$conn->query($sql2);
						$phone[$i] = $row["验收人手机"];
						$i++;
						$sql2 = "SELECT * FROM `tb_msg_itemaccept` WHERE acceptTimeStamp= '".$row["acceptTimeStamp"]."' AND 签名人 = '".$row["验收人手机"]."'";
						$result2 = $conn->query($sql2);
						if($result2->num_rows>0){
							$sqli = "UPDATE 消息推送验收 SET 删除='0',已读='0',推送时间 ='$now_date',排查时间戳 ='$sjc'  WHERE 时间戳= '".$row["时间戳"]."' AND 签名人 = '".$row["验收人手机"]."' AND 验收楼层 = '".$row["验收楼层"]."'";
						}else{
							$sqli = "INSERT INTO 消息推送验收 (时间戳,时间,签名人,签名,删除,已读,排查时间戳,层级,推送时间,验收楼层) VALUES ('".$row["时间戳"]."','','".$row["验收人手机"]."','','0','0','$sjc','".$row["层级"]."','$now_date','".$row["验收楼层"]."')";
						}
						$conn->query($sqli);
					}
					$data["item"]=$phone;
				}else{
					$data["code"]=0;
				}
			}
		break;
		//获取整改参数
		case 'getAcceptParam':
			$acceptCardTimeStamp = isset($_POST["acceptCardTimeStamp"]) ? $_POST["acceptCardTimeStamp"] : '';
			$sql = "SELECT * FROM tb_inspectaccept_processaccept WHERE acceptTimeStamp='$acceptCardTimeStamp' GROUP BY acceptTimeStamp";
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
		    $i=0;
		    if($result->num_rows>0)	{
				$row = $result->fetch_assoc();
				$resData['accept']['id'] = $row['measureId'];
				$resData['accept']['createUserName'] = $row['createUserName'];
				$resData['accept']['inspectDate'] = $row['inspectDate'];
				$resData['accept']['inspectItem'] = $row['inspectItem'];
				$resData['accept']['inspectPosition'] = $row['inspectPosition'];
				$resData['accept']['projectName'] = $row['projectName'];
				$resData['accept']['subcontract'] = $row['subcontract'];
				// $res[$i] = $resData;
				// $i++;
				$sql1 = "SELECT COUNT(id) AS num,`number` FROM `tb_inspectaccept_notice` WHERE `measureId`='".$row['measureId']."' GROUP BY `number`";
				$result1 = $conn->query($sql1);
				if($result1->num_rows>0){
					$arr = array();
					while($row1=$result1->fetch_assoc()){
						$number = $row1["number"];
						$sqli = "SELECT `noticeTimeStamp` FROM `tb_inspectaccept_notice` WHERE `measureId`='".$row['measureId']."' AND `number`='$number'";
						$result1 = $conn->query($sqli);
						if($result1->num_rows>0){
							$sjcArr = array();
							$j=0;
							while($rows=$result1->fetch_assoc()){
								$sjcArr[$j] = $rows["noticeTimeStamp"];
								$j++;
							}
							$sjc = implode(",",$sjcArr);
						}
						$arr[$number]=$row1["num"]."|".$sjc;
						$resData['inspect']['content'] = $row['acceptProcedure'];
						$resData['inspect']['timeStamp'] = $arr;
						// $res[$i] = $resData;
						// $i++;
					}
				}
				$data['data'] = $resData;
		    }else {
				$data['code'] = 0;
			}
		break;
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	
	

?>
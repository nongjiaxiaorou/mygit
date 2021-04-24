<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	$now_time= time();
	$now_date= date('Y-m-d H:i:s',$now_time);
	
	switch($flag){
		//更新签名信息
		case 'acceptController':
			$acceptTimeStamp = isset($_POST["acceptTimeStamp"]) ? $_POST["acceptTimeStamp"] : '';
			$userid = isset($_POST["userid"]) ? $_POST["userid"] : '';
			$signImage = isset($_POST["signImage"]) ? $_POST["signImage"] : '';
			$opinion = isset($_POST["opinion"]) ? $_POST["opinion"] : '';
			$sql1 = "UPDATE tb_msg_itemaccept SET signNameTime='$now_date',signNameBase='$signImage',qualityPersonOpinion='$opinion' WHERE acceptTimeStamp='$acceptTimeStamp' AND acceptPersonId='$userid'";
			$result1 = $conn->query($sql1);
			$sql = "SELECT * FROM tb_msg_itemaccept where acceptTimeStamp='$acceptTimeStamp'";
			$result = $conn->query($sql);
			if($result->num_rows>0){
				$rows = $result->fetch_assoc();
				$acceptTimeStamp = $rows["acceptTimeStamp"];
				$sql2 = "SELECT `level`,`TimeStampStr`,`signNameBase` FROM `tb_msg_itemaccept` WHERE `acceptTimeStamp`='$acceptTimeStamp'";
				$result2 = $conn->query($sql2);
				$rows2 = $result2->fetch_assoc();
				while($rows2=$result2->fetch_assoc()){
					$level = $rows2["level"];
					$level = explode("-", $level);
					
					if(($level[0]==$level[1])&&($rows2["signNameBase"]!="")){
						$sql3 = "UPDATE `tb_inspectaccept_processaccept` SET `acceptState`='已验收' WHERE `acceptTimeStamp`='$acceptTimeStamp'";
						$conn->query($sql3);
					}else{
						$sql3 = "SELECT COUNT(id) AS num FROM `tb_msg_itemaccept` WHERE `acceptTimeStamp`='$acceptTimeStamp' AND (ISNULL(`signNameBase`) OR `signNameBase`='')";
						$result3 = $conn->query($sql3);
						$row3 = $result3->fetch_assoc();
						if($row3["num"]==0){
							$cj = $level[0]."-".($level[1]+1);
							$sql5 = "SELECT * FROM `tb_inspectaccept_processaccept` AS a,`tb_project` AS b WHERE a.`acceptTimeStamp`='$acceptTimeStamp' AND a.`level` = '$cj' AND a.projectId=b.id";
							echo $sql5;
							$result5 = $conn->query($sql5);
							if($result5->num_rows>0){
								// $perPhone = array();
								// $j=0;
								while($row5=$result5->fetch_assoc()){
									$sql6 = "SELECT * FROM `tb_msg_itemaccept` AS a,tb_project AS b,tb_inspectaccept_processaccept AS c WHERE a.`acceptTimeStamp`='$acceptTimeStamp' AND a.`signNamePerson`='".$row5["acceptPersonPhone"]."' AND c.projectId=b.id GROUP BY a.acceptTimeStamp";
									$result6 = $conn->query($sql6);
									if($result6->num_rows ==0){
										$row6=$result6->fetch_assoc();
										$projectName = $row5["projectName"];
										$inspectPosition = $row5["inspectPosition"];
										$acceptPersonPhone = $row5["acceptPersonPhone"];
										$acceptPersonId = $row5["acceptPersonId"];
										$projectId = $row5["projectId"];
										$content = '关于'.$projectName.'-'.$inspectPosition.'的验收通知';
										$timeStamp=time().mt_rand(111, 999);
										$now_time = time();
										$now_date = date('Y-m-d H:i:s',$now_time);
										$sql7 = "INSERT INTO tb_msg_itemaccept SET acceptTimeStamp='$acceptTimeStamp',signNameTime='',signNamePerson='".$row5["acceptPersonPhone"]."',signNameBase='',isDelete='0',isRead='0',TimeStampStr='".$rows2["TimeStampStr"]."',level='$cj',pushTime='$now_date',acceptPersonId='".$row5["acceptPersonId"]."'";
										$conn->query($sql7);
										$sql8 = "INSERT INTO tb_msg_notice SET timeStamp = '$timeStamp', title = '项目验收' , `content` = '$content',cardTimeStamp = '$acceptTimeStamp', `time` = '$now_date', `read` = '0', `delete` = '0', `receiver` = '$acceptPersonPhone',`userId` = '$acceptPersonId', `projectId` = '$projectId' , `messagetype` = '项目验收', `position` = '$inspectPosition'";
										$conn->query($sql8);
										// $perPhone[$j]=$row5["验收人手机"];
										// $j++;
									}
									
								}
							}
						}
					}
				}
				
				if($result1>0){
					
				}else{
					$data["code"] = 0;
				}
			}
		break;
		case 'getSignOpinion':
			$acceptTimeStamp = isset($_POST["acceptTimeStamp"]) ? $_POST["acceptTimeStamp"] : '';
			$acceptPersonId = isset($_POST["acceptPersonId"]) ? $_POST["acceptPersonId"] : '';
			$sql = "SELECT * FROM tb_msg_itemaccept where acceptTimeStamp='$acceptTimeStamp' AND acceptPersonId='$acceptPersonId'";
			$result = $conn->query($sql);
			if($result->num_rows>0){
				$res = array();
				$resData = array();
				$i=0;
				while($row=$result->fetch_assoc()){
					$resData["signNameBase"] = $row["signNameBase"];
					$resData["qualityPersonOpinion"] = $row["qualityPersonOpinion"];
					$res[$i] = $resData;
					$i++;
				}
				$data['data']=$res;
			}else{
				$data["code"] = 0;
			}
		break;
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	

?>
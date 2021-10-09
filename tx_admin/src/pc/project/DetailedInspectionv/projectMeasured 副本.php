<?php
	require ("../../../../conn.php");
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	$ret_data = '';
	$ret_data["success"] = 'success';
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	$proTimeStamp = isset($_POST["proTimeStamp"])?$_POST["proTimeStamp"] : '';
	$projectName = isset($_POST["projectName"])?$_POST["projectName"] : '';
	$projectCategory =isset($_POST["projectCategory"])?$_POST["projectCategory"] : '';
	$measureState = isset($_POST["measureState"])?$_POST["measureState"] : '';
	$inspectPosition = isset($_POST["inspectPosition"])?$_POST["inspectPosition"] : '';
	$completedTime = isset($_POST["completedTime"])?$_POST["completedTime"] : '';
	$inspectItem = isset($_POST["inspectItem"])?$_POST["inspectItem"] : '';
	$inspectDate = isset($_POST["inspectDate"])?$_POST["inspectDate"] : '';
	$inspectPerson = isset($_POST["inspectPerson"])?$_POST["inspectPerson"] : '';
	$constructionTeam = isset($_POST["constructionTeam"])?$_POST["constructionTeam"] : '';
	$teamLeaderName = isset($_POST["teamLeaderName"])?$_POST["teamLeaderName"] : '';
	$contactMode = isset($_POST["contactMode"])?$_POST["contactMode"] : '';
	$constructionDate = isset($_POST["constructionDate"])?$_POST["constructionDate"] : '';
	$id = isset($_POST["id"])?$_POST["id"] : '';
	switch($flag){
		//获取项目到本地系统
		case 'getMeasureData':
			$sql = "SELECT * FROM tb_inspectaccept_measure where `inspectPosition`= '$inspectPosition' order by id desc";
			$res = $conn -> query($sql);
			if ($res->num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["inspectPosition"] = $row["inspectPosition"];
					$ret_data["data"][$i]["inspectItem"] = $row["inspectItem"];
					$ret_data["data"][$i]["inspectDate"] = $row["inspectDate"];
					$ret_data["data"][$i]["inspectPerson"] = $row["inspectPerson"];
					$ret_data["data"][$i]["proTimeStamp"] = $row["proTimeStamp"];
					$ret_data["data"][$i]["id"] = $row["id"];
//					$ret_data["data"][$i]["city"] = $row["city"];
					
					$i++;
				}
				$ret_data["success"] = 'success';
			}else{
				$ret_data["states"] = 'error';
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;

			//修改项目实测信息
			case 'Modify':
				$sql = "UPDATE tb_inspectaccept_measure SET inspectPosition = '$inspectPosition' , inspectDate = '$inspectDate' , inspectPerson = '$inspectPerson' , inspectItem = '$inspectItem' , constructionTeam = '$constructionTeam' , teamLeaderName = '$teamLeaderName' , contactMode = '$contactMode' , constructionDate = '$constructionDate' WHERE `id`= '$id'";
				$result = $conn -> query($sql);
				if ($result) {
					$ret_data["success"] = 'success';
				} 
				else {
					$ret_data['success'] = 'error';
				}
	
				$conn -> close();
				$json = json_encode($ret_data);
				echo $json;
				break;

			//删除
			case 'Delete':
				$sql = "DELETE FROM tb_inspectaccept_measure WHERE `id`= '$id'";
				$resDelete = $conn -> query($sql);
				if ($resDelete) {
					$ret_data["success"] = 'success';
				}
				else {
					$ret_data['success'] = 'error';
				}
	
				$conn -> close();
				$json = json_encode($ret_data);
				echo $json;
				break;

			//获取实测实量数据
			case 'measuredRealData':
				$sql = "SELECT * FROM tb_measure_measurepoint where `measureId`= '$id' and `pointInitialStatus`='不合格' GROUP BY `measurePointName`";
				$res = $conn -> query($sql);
				if ($res->num_rows > 0) {
					$i = 0;
					while ($row = $res -> fetch_assoc()) {
						$measurePointName = $row['measurePointName'];
						$j = 0;
						$dataSon = [];
						// $sql1 = "SELECT * FROM tb_measure_measurepoint where `measureId`= '$id' and `pointInitialStatus`='不合格' and `measurePointName`='$measurePointName' order by id desc";
						$sql1 = "SELECT * FROM tb_measure_measurepoint where `measureId`= '$id' and `measurePointName`='$measurePointName' order by id desc";
						$ret_data["data"][$i]["sql1"] = $sql1;
						$res1 = $conn -> query($sql1);
						 while ($row1 = $res1 -> fetch_assoc()) {
							 $initTestStatus = $row1["pointInitialStatus"];
							 $pointRetestStatus = $row1["pointRetestStatus"];
							 $finaltestStatus = $row1["finaltestStatus"];
							 if ($initTestStatus == '合格') {
								$dataSon[$j]["pointInitialValue"] = $row1["pointInitialStatus"];
							 } else {
								$dataSon[$j]["pointInitialValue"] = $row1["pointInitialValue"];							
							 }
							 if($pointRetestStatus == '合格' ) {
								$dataSon[$j]["pointRetestValue"] = $row1["pointRetestStatus"];
							 }
							 else {
								$dataSon[$j]["pointRetestValue"] = $row1["pointRetestValue"];
							 }
							 if($finaltestStatus == '合格' ) {
								$dataSon[$j]["pointFinaltestValue"] = $row1["finaltestStatus"];
							 }
							 else {
								$dataSon[$j]["pointFinaltestValue"] = $row1["pointFinaltestValue"];
							 }
							$dataSon[$j]["measurePointNumber"] = $row1["measurePointNumber"];
							$dataSon[$j]["pointRetestValue"] = $row1["pointRetestValue"];
							$dataSon[$j]["pointFinaltestValue"] = $row1["pointFinaltestValue"];
							$j++;
						 }
						
						$ret_data["data"][$i]["j"] = $j;
						$ret_data["data"][$i]["measurePointName"] = $row["measurePointName"];
						$sql2 = "SELECT * FROM `tb_measure_standard` where `inspectContent`='$measurePointName'";
						$res2 = $conn -> query($sql2);
						$row2 = $res2 -> fetch_assoc();
						$ret_data["data"][$i]["number"] = $row2["number"];
						$status = $row["status"];
						$ret_data["data"][$i]["status"] = $row["status"];
						$ret_data["data"][$i]["qualityInspectionValue"] = $row["qualityInspectionValue"];
						$ret_data["data"][$i]["qualityInspectionStatus"] = $row["qualityInspectionStatus"];
						$ret_data['data'][$i]['dataSon'] = $dataSon;
						
						$i++;
					}

				}else{
					$ret_data["states"] = 'error';
				}
				$conn -> close();
				$json = json_encode($ret_data);
				echo $json;
				break;
			
			// 加载手绘图片
			case 'HandPlot':
				$id = isset($_POST["id"])?$_POST["id"] : '';
				$sql = "SELECT manualPrimaryPicName,manualRetestPicName FROM tb_inspectaccept_measure where `id`= '$id' order by id desc";
				$res = $conn -> query($sql);
				$row = $res -> fetch_assoc();
				$manualPrimaryPicName = $row["manualPrimaryPicName"];
				$manualRetestPicName = $row["manualRetestPicName"];
				$ret_data["data"]["picName1"] = $manualPrimaryPicName;
				$ret_data["data"]["picName2"] = $manualRetestPicName;

				if ($res) {
					$ret_data["success"] = 'success';
				}
				else {
					$ret_data['success'] = 'error';
				}
				
				$conn -> close();
				$json = json_encode($ret_data);
				echo $json;	
			break;


			//获取点数量
			case 'accessPoint':
				$sql = "SELECT *  FROM tb_measure_measurepoint where `measureId`= '$id' GROUP BY `status`";
				$res = $conn -> query($sql);
				$row = $res -> fetch_assoc(); 
				$status = $row["status"];
				if ($status == '初测') {
					$sql3 = "SELECT count(*) as num FROM tb_measure_measurepoint where `measureId`= '$id' and `pointInitialStatus`='不合格' order by id desc";
					$res3 = $conn -> query($sql3);
					$row3 = $res3 -> fetch_assoc(); 
					$UnqualifiedNum = $row3["num"];
					$ret_data["data"]["UnqualifiedNum"] = $UnqualifiedNum;
					$ret_data["data"]["sql3"] = $sql3;

				}
				else if ($status == '复测') {
					$sql3 = "SELECT count(*) as num FROM tb_measure_measurepoint where `measureId`= '$id' and `pointRetestStatus`='不合格' order by id desc";
					$res3 = $conn -> query($sql3);
					$row3 = $res3 -> fetch_assoc(); 
					$UnqualifiedNum = $row3["num"];
					$ret_data["data"]["UnqualifiedNum"] = $UnqualifiedNum;
				}
				else {
					$sql3 = "SELECT count(*) as num FROM tb_measure_measurepoint where `measureId`= '$id' and `finaltestStatus`='不合格' order by id desc";
					$res3 = $conn -> query($sql3);
					$row3 = $res3 -> fetch_assoc(); 
					$UnqualifiedNum = $row3["num"];
					$ret_data["data"]["UnqualifiedNum"] = $UnqualifiedNum;
				}						
				// $ret_data["data"][$i]["pointRetestStatus"] = $row["pointRetestStatus"];
				// $ret_data["data"][$i]["finaltestStatus"] = $row["finaltestStatus"];
//					$ret_data["data"][$i]["city"] = $row["city"];
				
				$sql4 = "SELECT count(*) as MeasuredPoints FROM tb_measure_pointinfo where `measureId`= '$id' order by id desc";
				$res4 = $conn -> query($sql4);
				$row4 = $res4 -> fetch_assoc();
				$MeasuredPoints = $row4["MeasuredPoints"];
				$ret_data["data"]["MeasuredPoints"] = $MeasuredPoints;

				$conn -> close();
				$json = json_encode($ret_data);
				echo $json;
			break;
			
			// 实测测点图纸
			case 'MeasuringPoint':
				$id = isset($_POST["id"])?$_POST["id"] : '';
				$sql0 = "SELECT * FROM tb_measure_measurepoint where `measureId`= '$id' order by id desc";
				$res0 = $conn -> query($sql0);
				$row0 = $res0 -> fetch_assoc();
				$ret_data["data"]["status"] = $row0["status"];

				$sql = "SELECT * FROM tb_inspectaccept_measure where `id`= '$id' order by id desc";
				$res = $conn -> query($sql);
				$row = $res -> fetch_assoc();
				$ret_data["data"]["measurePointPic"] = $row["measurePointPic"];
				$ret_data["data"]["primaryMeasurePic"] = $row["primaryMeasurePIC"];

				if ($res) {
					$ret_data["success"] = 'success';
				}
				else {
					$ret_data['success'] = 'error';
				}
				
				$conn -> close();
				$json = json_encode($ret_data);
				echo $json;	
			break;


			// 实测测量表
			case 'getTableData':
				$id = isset($_POST["id"])?$_POST["id"] : '';
				$inspectItem = isset($_POST["inspectItem"])?$_POST["inspectItem"] : '';
				// $sql0 = "SELECT * FROM tb_measure_measurepoint where `measureId`= '$id' order by id desc";
				// $res0 = $conn -> query($sql0);
				// $row0 = $res0 -> fetch_assoc();
				// $status = $row0["status"];
				$sql = "SELECT * FROM tb_measure_measurepoint where `measureId`= '$id' AND `pointInitialStatus` = '不合格' order by id desc";
				$res = $conn -> query($sql);
				if ($res->num_rows > 0) {
					$i = 0;
					while ($row = $res -> fetch_assoc()) {
						$ret_data["data"][$i]["measurePointName"] = $row["measurePointName"];
						$ret_data["data"][$i]["pointInitialValue"] = $row["pointInitialValue"];
						$ret_data["data"][$i]["measurePointNumber"] = $row["measurePointNumber"];
						
						$measurePointName = $ret_data["data"][$i]["measurePointName"];

						$sql2 = "SELECT * FROM tb_measure_standard where `inspectItem`= '$inspectItem' AND `inspectContent`= '$measurePointName' order by id desc";
						$res2 = $conn -> query($sql2);
						while ($row2 = $res2 -> fetch_assoc()) {
							$ret_data["data"][$i]["minStandardValue"] = $row2["minStandardValue"];
							$ret_data["data"][$i]["maxStandardValue"] = $row2["maxStandardValue"];	
						}
						$i++;
					}
					$ret_data["success"] = 'success';
				}else{
					$ret_data["states"] = 'error';
				}

				$conn -> close();
				$json = json_encode($ret_data);
				echo $json;
				break;

	}

?>
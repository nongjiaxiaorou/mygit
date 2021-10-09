<?php
	require ("../../../../conn.php");
	error_reporting(E_ALL ^ E_NOTICE);
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	date_default_timezone_set('PRC'); //东八时区 echo
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
		case 'getMeasureType':
			$inspectItem = isset($_POST["inspectItem"]) ? $_POST["inspectItem"] : '';
			$pointStatus = isset($_POST["pointStatus"]) ? $_POST["pointStatus"] : '';
			$projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$sql = "SELECT * FROM tb_project WHERE `id`='$projectId'";
			$result = $conn -> query($sql);
			$row = $result->fetch_assoc();
			$standardTable = $row["standard"];
			$sql1 = "SELECT * FROM ".$standardTable." WHERE `inspectItem`=$inspectItem";
			$result1 = $conn -> query($sql1);
			$res = array();
			$resData = array();
		    $i=0;
		    if($result1->num_rows>0){
			 	while($row1 = $result1->fetch_assoc()){
					$measurePointName = $row1["inspectContent"];
					// 查出该测点类型未布点数
					if($pointStatus=="初测"){
						$sqli = "SELECT measurePointName,measurePointNumber,id FROM `tb_measure_measurepoint` WHERE `pointInitialStatus`='不合格' AND `projectId`='$projectId' AND `measureId`='$measureId' AND `measurePointName`='$measurePointName ' AND `status`='$pointStatus' AND NOT EXISTS ( SELECT * FROM `tb_measure_pointinfo` WHERE `tb_measure_measurepoint`.measurePointNumber = `tb_measure_pointinfo`.number AND `measureId`='$measureId')";
					}else if($pointStatus=="复测"){
						$sqli = "SELECT measurePointName,measurePointNumber,id FROM `tb_measure_measurepoint` WHERE `pointRetestStatus`='不合格' AND `projectId`='$projectId' AND `measureId`='$measureId' AND `measurePointName`='$measurePointName ' AND `status`='$pointStatus' AND NOT EXISTS ( SELECT * FROM `tb_measure_pointinfo` WHERE `tb_measure_measurepoint`.measurePointNumber = `tb_measure_pointinfo`.number AND `measureId`='$measureId')";
					}else if($pointStatus=="终测"){
						$sqli = "SELECT measurePointName,measurePointNumber,id FROM `tb_measure_measurepoint` WHERE `finaltestStatus`='不合格' AND `projectId`='$projectId' AND `measureId`='$measureId' AND `measurePointName`='$measurePointName ' AND `status`='$pointStatus' AND NOT EXISTS ( SELECT * FROM `tb_measure_pointinfo` WHERE `tb_measure_measurepoint`.measurePointNumber = `tb_measure_pointinfo`.number AND `measureId`='$measureId')";
					}
					$resulti = $conn->query($sqli);
					$resData['notPutNum'] = $resulti->num_rows;
					$j=0;
					$children = array();
					$data['pointStatus'] = $pointStatus;
					if($pointStatus=='初测'){
						$sql2 = "SELECT count(id) AS unQualifiedNum FROM tb_measure_measurepoint WHERE `measurePointName`='$measurePointName' AND `measureId`='$measureId' AND pointInitialStatus='不合格'";
						$sql3 = "SELECT count(id) AS qualifiedNum FROM tb_measure_measurepoint WHERE `measurePointName`='$measurePointName' AND `measureId`='$measureId' AND pointInitialStatus='合格'";
						$sql4 = "SELECT * FROM tb_measure_measurepoint WHERE `measurePointName`='$measurePointName' AND `measureId`='$measureId' AND pointInitialStatus='不合格'";
						$result2 = $conn -> query($sql2);
						$result3 = $conn -> query($sql3);
						$result4 = $conn -> query($sql4);
						
						if($result2 -> num_rows>0){
							$row2 = $result2 ->fetch_assoc();
							$unQualifiedNum = $row2["unQualifiedNum"];
						}
						// $resData['sql'] = $sql4;
						while($row4 = $result4 ->fetch_assoc()){
							$children[$j]['number'] = $row4['measurePointNumber'];
							$children[$j]['initTestVal'] = $row4['pointInitialValue']=='' ? null : $row4['pointInitialValue'];
							$children[$j]['retestVal'] = $row4['pointRetestValue']==''? null : $row4['pointRetestValue'];
							$children[$j]['finalTestVal'] = $row4['pointFinaltestValue']==''? null : $row4['pointFinaltestValue'];
							$children[$j]['initStatus'] = $row4['pointInitialStatus'] == ''? null : $row4['pointInitialStatus'];
							$children[$j]['retestStatus'] = $row4['pointRetestStatus'] == ''? null : $row4['pointRetestStatus'];
							$children[$j]['finalStatus'] = $row4['pointFinaltestStatus'] == ''? null : $row4['pointFinaltestStatus'];
							$children[$j]['status'] = $row4['pointInitialStatus'] == ''? null : $row4['pointInitialStatus'];
							$j++;
						}
						if($result3 -> num_rows>0){
							$row3 = $result3 ->fetch_assoc();
							$qualifiedNum = $row3["qualifiedNum"];
						}
						
					}else if($pointStatus=='复测'){
						$sql2 = "SELECT count(id) AS unQualifiedNum FROM tb_measure_measurepoint WHERE `measurePointName`='$measurePointName' AND `measureId`='$measureId' AND `pointRetestStatus`='不合格'";
						$sql3 = "SELECT count(id) AS qualifiedNum FROM tb_measure_measurepoint WHERE `measurePointName`='$measurePointName' AND `measureId`='$measureId' AND `pointRetestStatus`='合格'";
						// echo $sql3;
						$sql4 = "SELECT * FROM tb_measure_measurepoint WHERE `measurePointName`='$measurePointName' AND `measureId`='$measureId' AND pointInitialStatus='不合格'";
						$result2 = $conn -> query($sql2);
						$result3 = $conn -> query($sql3);
						$result4 = $conn -> query($sql4);
						
						if($result2 -> num_rows>0){
							$row2 = $result2 ->fetch_assoc();
							$unQualifiedNum = $row2["unQualifiedNum"];
						}
						while($row4 = $result4 ->fetch_assoc()){
							$children[$j]['number'] = $row4['measurePointNumber'];
							$children[$j]['initTestVal'] = $row4['pointInitialValue']==''? null : $row4['pointInitialValue'];
							$children[$j]['retestVal'] = $row4['pointRetestValue']==''? null : $row4['pointRetestValue'];
							$children[$j]['finalTestVal'] = $row4['pointFinaltestValue']==''? null : $row4['pointFinaltestValue'];
							$children[$j]['initStatus'] = $row4['pointInitialStatus'] == ''? null : $row4['pointInitialStatus'];
							$children[$j]['retestStatus'] = $row4['pointRetestStatus'] == ''? null : $row4['pointRetestStatus'];
							$children[$j]['finalStatus'] = $row4['pointFinaltestStatus'] == ''? null : $row4['pointFinaltestStatus'];
							$children[$j]['status'] = $row4['pointRetestStatus'] == ''? null : $row4['pointRetestStatus'];
							$j++;
						}
						if($result3 -> num_rows>0){
							$row3 = $result3 ->fetch_assoc();
							$qualifiedNum = $row3["qualifiedNum"];
						}
					}else if($pointStatus=='终测'){
						$sql2 = "SELECT count(id) AS unQualifiedNum FROM tb_measure_measurepoint WHERE `measurePointName`='$measurePointName' AND `measureId`='$measureId' AND finaltestStatus='不合格'";
						$sql3 = "SELECT count(id) AS qualifiedNum FROM tb_measure_measurepoint WHERE `measurePointName`='$measurePointName' AND `measureId`='$measureId' AND finaltestStatus='合格'";
						$sql4 = "SELECT * FROM tb_measure_measurepoint WHERE `measurePointName`='$measurePointName' AND `measureId`='$measureId' AND pointInitialStatus='不合格'";
						$result2 = $conn -> query($sql2);
						$result3 = $conn -> query($sql3);
						$result4 = $conn -> query($sql4);
						
						if($result2 -> num_rows>0){
							$row2 = $result2 ->fetch_assoc();
							$unQualifiedNum = $row2["unQualifiedNum"];
						}
						while($row4 = $result4 ->fetch_assoc()){
							$children[$j]['number'] = $row4['measurePointNumber'];
							$children[$j]['initTestVal'] = $row4['pointInitialValue']==''? null : $row4['pointInitialValue'];
							$children[$j]['retestVal'] = $row4['pointRetestValue']==''? null : $row4['pointRetestValue'];
							$children[$j]['finalTestVal'] = $row4['pointFinaltestValue']==''? null : $row4['pointFinaltestValue'];
							$children[$j]['initStatus'] = $row4['pointInitialStatus'] == ''? null : $row4['pointInitialStatus'];
							$children[$j]['retestStatus'] = $row4['pointRetestStatus'] == ''? null : $row4['pointRetestStatus'];
							$children[$j]['finalStatus'] = $row4['finaltestStatus'] == ''? null : $row4['finaltestStatus'];
							$children[$j]['status'] = $row4['finaltestStatus'] == ''? null : $row4['finaltestStatus'];
							$j++;
						}
						if($result3 -> num_rows>0){
							$row3 = $result3 ->fetch_assoc();
							$qualifiedNum = $row3["qualifiedNum"];
						}						
					}
					$sql5 = "SELECT * FROM tb_measure_measurepoint WHERE `measurePointName`='$measurePointName' AND `measureId`='$measureId' AND missItem='true'";
					$sql6 = "SELECT * FROM tb_measure_measurepoint WHERE `measurePointName`='$measurePointName' AND `measureId`='$measureId' AND `projectId` = '$projectId' ";
					$result5 = $conn -> query($sql5);
					$result6 = $conn -> query($sql6);
					$row5 = $result5 ->fetch_assoc();
					$row6 = $result6 ->fetch_assoc();
					$resData['id'] = $row1['id'];
					$resData['measureType'] = $row1['inspectContent']; 
					$resData['qualifiedBadge'] = $qualifiedNum;
					$resData['unQualifiedBadge'] = $unQualifiedNum;
					$resData['range'] = "[".$row1['minStandardValue'].",".$row1['maxStandardValue']."]";
					$resData['number'] = $row1['number'];
					$resData['missItem'] = $row5['missItem'];
					$resData['qualityPercentage'] = $row6['qualityInspectionValue'];
					$resData['qualityInspectionStatus'] = $row6['qualityInspectionStatus'];
					$resData['children'] = $children;
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
		    }else {
				$data['code'] = 0;
			}
		break;
		case 'intoReTest':
			$projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$pointArr = isset($_POST["pointArr"]) ? $_POST["pointArr"] : '';
			$pointArr = json_decode($pointArr);
			$pointArrLength = sizeof($pointArr);
			for ($i=0; $i<$pointArrLength; $i++) {
				$children = $pointArr[$i]->children;
				// $data['children'] = 11;
				$childrenLen = count($children);
				for ($j=0; $j < $childrenLen; $j++) {
					// $data['chidren'][$j] = $children[$j];
					$initTestVal = $children[$j]->initTestVal;
					$number = $children[$j]->number;
					$initStatus = $children[$j]->initStatus;
					$sql = "UPDATE `tb_measure_measurepoint` SET `pointRetestValue` = '$initTestVal',`pointRetestStatus`='$initStatus' WHERE `projectId` = '$projectId' AND `measureId` = '$measureId' AND `measurePointNumber`='$number' AND `pointInitialStatus` =  '不合格'";
					$result = $conn->query($sql);
					if($result)	{
						$data['code1'] = 1;
					}else {
						$data['code1'] = 0;
					}
					$sql = "UPDATE `tb_measure_measurepoint` SET `pointRetestStatus` = '合格' WHERE `projectId` = '$projectId' AND `measureId` = '$measureId' AND `pointInitialValue` = 'hg' ";
					$result = $conn->query($sql);
				}
			}
			$sql = "UPDATE `tb_measure_measurepoint` SET `status` = '复测' WHERE `projectId` = '$projectId' AND `measureId` = '$measureId'";
			$result = $conn->query($sql);
		    if($result)	{
				$data['code'] = 1;
		    }else {
				$data['code'] = 0;
			}
		break;
		case 'finalTest':
			$projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$pointArr = isset($_POST["pointArr"]) ? $_POST["pointArr"] : '';
			$pointArr = json_decode($pointArr);
			$data['pointArr'] = $pointArr;
			$pointArrLength = sizeof($pointArr);
			$sql = "UPDATE `tb_measure_measurepoint` SET `finaltestStatus` = '合格' WHERE `projectId` = '$projectId' AND `measureId` = '$measureId' AND `pointInitialValue` = 'hg' ";
			$result = $conn->query($sql);
			for ($i=0; $i<$pointArrLength; $i++) {
				$children = $pointArr[$i]->children;
				$measurePointName = $pointArr[$i]->measureType;
				
				for ($j=0; $j<count($children); $j++) {
					// $data['chidren'][$j] = $children[$j];
					$reTestval = $children[$j]->retestVal;
					$number = $children[$j]->number;
					$retestStatus = $children[$j]->retestStatus;
					$sql = "UPDATE `tb_measure_measurepoint` SET `pointFinaltestValue` = '$reTestval',`finaltestStatus`='$retestStatus' WHERE `projectId` = '$projectId' AND `measureId` = '$measureId' AND `measurePointNumber`='$number' AND `pointInitialStatus` = '不合格' ";
					$result = $conn->query($sql);
					if($result)	{
						$data['code1'] = 1;
					}else {
						$data['code1'] = 0;
					}
				}
				$sql1 = "SELECT * FROM tb_measure_measurepoint WHERE `measurePointName`='$measurePointName' AND `measureId`='$measureId' AND `projectId` = '$projectId' AND `finaltestStatus`='合格' ";
				$sql2 = "SELECT * FROM tb_measure_measurepoint WHERE `measurePointName`='$measurePointName' AND `measureId`='$measureId' AND `projectId` = '$projectId' ";
				$result1 = $conn->query($sql1);
				$result2 = $conn->query($sql2);
				$qualifiedNum = $result1->num_rows;
				$totalNum = $result2->num_rows;
				$data['qualifiedNum'] = $qualifiedNum;
				$data['totalNum'] = $totalNum;
				$qualityInspectStatus = '';
				$qualityPercentage = round($qualifiedNum/$totalNum*100,2) . '%';
				$qualityInspectValue = round($qualifiedNum/$totalNum*100,2);
				if ( $qualityInspectValue >= 80 ) {
					$qualityInspectStatus = '合格';
				} else {
					$qualityInspectStatus = '不合格';
				}
				$data['qualityInspectValue'] = $qualityInspectValue;
				$sql = "UPDATE `tb_measure_measurepoint` SET `qualityInspectionValue`= '$qualityPercentage',`qualityInspectionStatus`='$qualityInspectStatus' WHERE `projectId` = '$projectId' AND `measureId` = '$measureId' AND `measurePointName` = '$measurePointName' ";
				$result = $conn->query($sql);
				// $data['qualityPercentage'][$i] = $qualityPercentage[$i];
			}
			$sql = "UPDATE `tb_measure_measurepoint` SET `status` = '终测' WHERE `projectId` = '$projectId' AND `measureId` = '$measureId'";
			$result = $conn->query($sql);
		    if($result)	{
				$data['code'] = 1;
		    }else {
				$data['code'] = 0;
			}
			
			
			
		break;
		case 'getPointStatus':
			$projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$sql = "SELECT * FROM `tb_measure_measurepoint` WHERE `projectId` = '$projectId' AND `measureId`='$measureId'";
			$result = $conn->query($sql);
			$res = array();
			$resData = array();
		    $i=0;
		    if($result->num_rows>0)	{
			 	while($row = $result->fetch_assoc()){
					$resData['status'] = $row["status"] == null? '初测':$row['status'];  // 若第一次进入初测时有未测量的值, 则后面再点进去会出现 pointStatus==null 的情形
					$res[$i] = $resData;
		            $i++;
				}
				$data['data'] = $res;
		    }else {
				$data['code'] = 0;
			}
		break;
		case 'saveMeasurePoint':
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
			$unQualifiedInfo = isset($_POST["unQualifiedInfo"]) ? $_POST["unQualifiedInfo"] : '';
			$qualifiedNum = isset($_POST["qualifiedNum"]) ? $_POST["qualifiedNum"] : '';
			$measureType = isset($_POST["measureType"]) ? $_POST["measureType"] : '';
			$pointStatus = isset($_POST["pointStatus"]) ? $_POST["pointStatus"] : '';
			$number = isset($_POST["number"]) ? $_POST["number"] : '';
			$missItem = isset($_POST["missItem"]) ? $_POST["missItem"] : '';
			$totalNumOld = isset($_POST["totalNumOld"]) ? $_POST["totalNumOld"] : '';
			$data['missItem'] = $missItem;
			$time=date("Y-m-d H:i:s");
			if($missItem == 'true') {
				$sql1 = "INSERT INTO tb_measure_measurepoint (measureId,projectId,measurePointName,missItem,createTime,status) VALUES ('$measureId','$projectId','$measureType','true','$time','初测')";
				$result1 = $conn->query($sql1);
				$data['result1'] = $result1;
			} else {
				$unQualifiedInfo = json_decode($unQualifiedInfo,true);
				$unQualifiedArr= [];
				$qualifiedArr= [];
				$j=0;
				for($i=0;$i<count($unQualifiedInfo);$i++){
					$unQualifiedNum = $unQualifiedInfo[$i]['number'];
					$unQualifiedVal = $unQualifiedInfo[$i]['value'];
					$unQualifiedArr[$i] = $unQualifiedVal==''?"('$measureId','$projectId','$measureType','$unQualifiedNum','0','不合格','$pointStatus','$time','false')":"('$measureId','$projectId','$measureType','$unQualifiedNum','$unQualifiedVal','不合格','$pointStatus','$time','false')";
				}
				$unQualifiedStr = join(",", $unQualifiedArr);
				$sql = "INSERT INTO tb_measure_measurepoint (measureId,projectId,measurePointName,measurePointNumber,pointInitialValue,pointInitialStatus,status,createTime,missItem) VALUES $unQualifiedStr";
				$result = $conn->query($sql);
				$qualifiedNum=intval($qualifiedNum);
				$j= $totalNumOld + $i + 1;
				for($i=0;$i<$qualifiedNum;$i++){ 
					$num = $number.$j;
					$res['number'][$i] = $num;
					$qualifiedArr[$i] = "('$measureId','$projectId','$measureType','$num','hg','合格','$pointStatus','$time','false')";
					$j++;
				}
				$qualifiedStr = join(",", $qualifiedArr);
				$sql1 = "INSERT INTO tb_measure_measurepoint (measureId,projectId,measurePointName,measurePointNumber,pointInitialValue,pointInitialStatus,status,createTime,missItem) VALUES $qualifiedStr";
				$result1 = $conn->query($sql1);
			}
		    if($result1){
				$data['data'] = $res;
		    }else {
				$data['code'] = 0;
			}
		break;
		case 'saveMeasureRetestPoint':
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
			$allQualifiedInfo = isset($_POST["allQualifiedInfo"]) ? $_POST["allQualifiedInfo"] : '';
			$measureType = isset($_POST["measureType"]) ? $_POST["measureType"] : '';
			$itemRange = isset($_POST["itemRange"]) ? $_POST["itemRange"] : '';
			$allQualifiedInfo = json_decode($allQualifiedInfo,true);
			
			for($i=0;$i<count($allQualifiedInfo);$i++){
				$number = $allQualifiedInfo[$i]['number'];
				$reTestVal = $allQualifiedInfo[$i]['retestVal'];
				$status = $allQualifiedInfo[$i]['status'];
				$sql = "UPDATE `tb_measure_measurepoint` SET `pointRetestValue`='$reTestVal',`pointRetestStatus`='$status' WHERE `measureId`='$measureId' AND `projectId`='$projectId' AND `measurepointName`='$measureType' AND `measurepointNumber`='$number' AND `pointInitialStatus`='不合格' ";
				if ($status == '合格') {
					$sql1 = "DELETE FROM `tb_measure_pointinfo` WHERE `measureId` = '$measureId' AND `number` = '$number' AND `measurePointType` = '$measureType' ";
					$result1 = $conn->query($sql1);
				}
				$result[$i] = $conn->query($sql);
				$data['sql'][$i] = $sql;
				$data['result'] = $result;
				if ($result) {
					$data['data'] = $res;
				} else {
					$data['code'] = 0;
					$data['cod'] = 0;
				}
			}
			
		break;
		case 'resetPoint':
			$projectId = $_POST["projectId"];
			$measureId = $_POST["measureId"];
			$measureType = $_POST["measureType"];
			$sql = "DELETE FROM tb_measure_measurepoint WHERE measureId='$measureId' AND projectId='$projectId' AND measurePointName='$measureType'";
			$result = $conn->query($sql);
		    if($result)	{
		    }else {
				$data['code'] = 0;
			}
		break;
		case 'getPointType':
			$projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$pointStatus = isset($_POST["pointStatus"]) ? $_POST["pointStatus"] : '';
			if($pointStatus=="初测"){
				$pointStatus = 'pointInitialStatus';
			}else if($pointStatus=="复测"){
				$pointStatus = 'pointRetestStatus';
			}else if($pointStatus=="终测"){
				$pointStatus = 'finaltestStatus';
			}
			$resData = array();
			$res = array();
			
			$sql = "SELECT measurePointName,count(pointInitialStatus) as num FROM `tb_measure_measurepoint` WHERE missItem='false' AND `measureId`='$measureId' AND `projectId`='$projectId' AND  `".$pointStatus."`='不合格'  GROUP BY measurePointName";
			$result = $conn->query($sql);
			if($result){
			  $j = 0;
			  while($row = $result->fetch_assoc()){
					  $resData['measurePointName'] = $row["measurePointName"];
					  $measurePointName = $row["measurePointName"];
					  $sql1 = "SELECT number FROM `tb_measure_standard` WHERE `inspectContent` =  '$measurePointName'";
					  $result1 = $conn->query($sql1);
					  $resData['num'] = $row["num"];
					  $row1 = $result1->fetch_assoc();
					  $resData['number'] = $row1["number"];
					  $res[$j] = $resData;
					  $j++;
			  }
			}
			for($i=0;$i<count($typeArr);$i++){
				$name = $res[$i]['measurePointName'];
				$sql1 = "SELECT count(number) as judgeNum  FROM `tb_measure_pointinfo` WHERE  `measureId`='$measureId'  AND `measurePointType`='$name'";
				$result1 = $conn->query($sql1);
				 while($row1 = $result1->fetch_assoc()){
			          $judgeNum=$row1["judgeNum"];
			         	if($judgeNum==$res[$i]['num']){
			            	$res[$i]['measurePointName']="null";
			            }
			 	 }
			}
		    if($result)	{
				$data['data'] = $res;
		    }else {
				$data['code'] = 0;
			}
		break;
		case 'getPoint':
			$projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$poiotType = isset($_POST["poiotType"]) ? $_POST["poiotType"] : '';
			$pointStatus = isset($_POST["pointStatus"]) ? $_POST["pointStatus"] : '';
			
			if($pointStatus=="初测"){
				$sqli = "SELECT measurePointName,measurePointNumber,id FROM `tb_measure_measurepoint` WHERE `pointInitialStatus`='不合格' AND `projectId`='$projectId' AND `measureId`='$measureId' AND `measurePointName`='$poiotType' AND `status`='$pointStatus' AND NOT EXISTS ( SELECT * FROM `tb_measure_pointinfo` WHERE `tb_measure_measurepoint`.measurePointNumber = `tb_measure_pointinfo`.number AND `measureId`='$measureId')";
			}else if($pointStatus=="复测"){
				$sqli = "SELECT measurePointName,measurePointNumber,id FROM `tb_measure_measurepoint` WHERE `pointRetestStatus`='不合格' AND `projectId`='$projectId' AND `measureId`='$measureId' AND `measurePointName`='$poiotType' AND `status`='$pointStatus' AND NOT EXISTS ( SELECT * FROM `tb_measure_pointinfo` WHERE `tb_measure_measurepoint`.measurePointNumber = `tb_measure_pointinfo`.number AND `measureId`='$measureId')";
			}else if($pointStatus=="终测"){
				$sqli = "SELECT measurePointName,measurePointNumber,id FROM `tb_measure_measurepoint` WHERE `finaltestStatus`='不合格' AND `projectId`='$projectId' AND `measureId`='$measureId' AND `measurePointName`='$poiotType' AND `status`='$pointStatus' AND NOT EXISTS ( SELECT * FROM `tb_measure_pointinfo` WHERE `tb_measure_measurepoint`.measurePointNumber = `tb_measure_pointinfo`.number AND `measureId`='$measureId')";
			}
			$result = $conn->query($sqli);
			$res = array();
			$resData = array();
			$i=0;
			if($result->num_rows>0)	{
			 	while($row = $result->fetch_assoc()){
					$resData['id'] = $row["id"];
					$resData['measurePointName'] = $row["measurePointName"];
					$resData['measurePointNumber'] = $row["measurePointNumber"];
					$res[$i] = $resData;
			        $i++;
				}
				$data['data'] = $res;
			}else {
				$data['code'] = 0;
			}
		break;
		case 'savePoint':
			// $projectId = $_POST["projectId"];
			// $measureId = $_POST["measureId"];
			// $measureType = $_POST["measureType"];
			
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$por_obj = isset($_POST["por_obj"]) ? $_POST["por_obj"] : '';
			$por_obj = json_decode($por_obj,true);
			foreach($por_obj as $key=>$val){
				$arr = explode("|", $key);
				$pointType = $arr[0];
				$number = $arr[1];
				$sqli = "SELECT * FROM `tb_measure_pointinfo` WHERE `number`='$number' AND measureId='$measureId'";
				$result = $conn->query($sqli);
				if($result->num_rows>0){
					$sql = "UPDATE `tb_measure_pointinfo` SET Xcoordinate='".$val[0]."',Ycoordinate='".$val[1]."',parXcoordinate='".$val[2]."',parYcoordinate='".$val[3]."',number='".$val[4]."' WHERE `measurePointType`='".$pointType."'";
					if ($conn->query($sql) === TRUE) {
						$over = "更新成功！";
					}else{
						$over = "第".($i+1)."条数据保存出错";
						break;
					}
				}else{
			      $judge = $val[4];
			      if($judge!=" "&&$judge!=null){
			      	$sql = "INSERT INTO `tb_measure_pointinfo` (measureId,measurePointType,Xcoordinate,Ycoordinate,parXcoordinate,parYcoordinate,number,status) VALUES ('$measureId','".$pointType."','".$val[0]."','".$val[1]."','".$val[2]."','".$val[3]."','".$val[4]."','save')";
					if ($conn->query($sql) === TRUE) {
						$over = "保存成功！";
					}else{
						$over = "第".($i+1)."条数据保存出错";
						break;
					}
			      }
					
				}
				
			}
			
		    if($result)	{
		    }else {
				$data['code'] = 0;
				$data['cod1e'] = $over;
				
			}
		break;
		case 'savePicSrc':
			require('../../../../base64ToPicFile.php');
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$pointStatus = isset($_POST["status"]) ? $_POST["status"] : '';
			// $picSrc = isset($_POST["picSrc"]) ? $_POST["picSrc"] : '';
			// $data['pic'] = $picSrc;
			$data['img'] = $img;
			// $data['mkdir'] = mkdir('testing',0777,true);
			// $picSrc = base64decode($picSrc);
			if ($pointStatus == '初测') {
				$sqli = "UPDATE `tb_inspectaccept_measure` SET `primaryMeasurePic`= '$img' WHERE `id`= '$measureId' ";
				$result = $conn->query($sqli);	
			} else {
				$sqli = "UPDATE `tb_inspectaccept_measure` SET `measurePointPic`= '$img' WHERE `id`= '$measureId' ";
				$result = $conn->query($sqli);	
			}
				
		    if($result)	{
		    }else {
				$data['code'] = 0;				
			}
		break;
		case 'getSavedPoint':
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';	
			$pointStatus = isset($_POST["pointStatus"]) ? $_POST["pointStatus"] : '';	
			$sql = "SELECT * FROM `tb_measure_pointinfo` WHERE `measureId`='$measureId' GROUP BY `measurePointType`";
			$result = $conn->query($sql);
			if ($pointStatus == '初测') {
				$sql0 = "SELECT * FROM `tb_measure_measurepoint` WHERE `measureId`='$measureId' and `pointInitialStatus` = '不合格'";
			 } else {
				 $sql0 = "SELECT * FROM `tb_measure_measurepoint` WHERE `measureId`='$measureId' and `pointRetestStatus` = '不合格'";
			 }
			$result0 = $conn->query($sql0);
			$AllPointNum = $result0->num_rows;
			$sql0_1 = "SELECT * FROM `tb_measure_pointinfo` WHERE `measureId`='$measureId'";
			$result0_1 = $conn->query($sql0_1);
			$putPointNum = $result0_1->num_rows;
			$data['putPointNum'] = $putPointNum;
			$data['notPointNum'] = $AllPointNum - $putPointNum;
			if($result->num_rows>0){
				$i = 0; 
				while ($row = $result -> fetch_assoc()) {
					$measurePointType = $row['measurePointType'];
					$sql1 = "SELECT number FROM `tb_measure_standard` WHERE `inspectContent` =  '$measurePointType'";
					$result1 = $conn->query($sql1);
					$row1 = $result1 -> fetch_assoc();
					$number = $row1['number'];
					$resData['measurePointType'] = $measurePointType;
					$resData['number'] = $number;
					$sql2 = "SELECT * FROM `tb_measure_pointinfo` WHERE `measureId`='$measureId' AND `measurePointType` = '$measurePointType'";
					$resData['sql'] = $sql2;
					$result2 = $conn->query($sql2);
					$result2num = $result2 -> num_rows;
					$j = 0;
					$resData['sonNumber'] = []; // 清空很重要
					if ($result2 -> num_rows>0) {
						while ($row2 = $result2 -> fetch_assoc()) {
							$resDataSon['num'] = $row2['number'];
							$resDataSon['Xcoordinate'] = $row2["Xcoordinate"];
							$resDataSon['Ycoordinate'] = $row2["Ycoordinate"];
							$resDataSon['parXcoordinate'] = $row2["parXcoordinate"];
							$resDataSon['parYcoordinate'] = $row2["parYcoordinate"];
							$resData['sonNumber'][$j] = $resDataSon;							
							$j++;
						}
					}
					$resData['j'] = $j;
					$res[$i] = $resData;
					$i++;
				}
				$data['i'] = $i;
				$data['sql'] = $sql;
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
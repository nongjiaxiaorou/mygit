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
					$j=0;
					$children = array();
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
						while($row4 = $result4 ->fetch_assoc()){
							$children[$j]['number'] = $row4['measurePointNumber'];
							$children[$j]['initTestVal'] = $row4['pointInitialValue']==''?'0':$row4['pointInitialValue'];
							$children[$j]['retestVal'] = $row4['pointRetestValue']==''?'0':$row4['pointRetestValue'];
							$children[$j]['finalTestVal'] = $row4['pointFinaltestValue']==''?'0':$row4['pointFinaltestValue'];
							$j++;
						}
						if($result3 -> num_rows>0){
							$row3 = $result3 ->fetch_assoc();
							$qualifiedNum = $row3["qualifiedNum"];
						}
						
					}else if($pointStatus=='复测'){
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
						while($row4 = $result4 ->fetch_assoc()){
							$children[$j]['number'] = $row4['measurePointNumber'];
							$children[$j]['initTestVal'] = $row4['pointInitialValue']==''?'0':$row4['pointInitialValue'];
							$children[$j]['retestVal'] = $row4['pointRetestValue']==''?'0':$row4['pointRetestValue'];
							$children[$j]['finalTestVal'] = $row4['pointFinaltestValue']==''?'0':$row4['pointFinaltestValue'];
							$j++;
						}
						if($result3 -> num_rows>0){
							$row3 = $result3 ->fetch_assoc();
							$qualifiedNum = $row3["qualifiedNum"];
						}
					}else if($pointStatus=='终测'){
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
						while($row4 = $result4 ->fetch_assoc()){
							$children[$j]['number'] = $row4['measurePointNumber'];
							$children[$j]['initTestVal'] = $row4['pointInitialValue']==''?'0':$row4['pointInitialValue'];
							$children[$j]['retestVal'] = $row4['pointRetestValue']==''?'0':$row4['pointRetestValue'];
							$children[$j]['finalTestVal'] = $row4['pointFinaltestValue']==''?'0':$row4['pointFinaltestValue'];
							$j++;
						}
						if($result3 -> num_rows>0){
							$row3 = $result3 ->fetch_assoc();
							$qualifiedNum = $row3["qualifiedNum"];
						}
					}
					$sql5 = "SELECT * FROM tb_measure_measurepoint WHERE `measurePointName`='$measurePointName' AND `measureId`='$measureId' AND missItem='true'";
					$result5 = $conn -> query($sql5);
					$row5 = $result5 ->fetch_assoc();
					$resData['id'] = $row1['id'];
					$resData['measureType'] = $row1['inspectContent'];
					$resData['qualifiedBadge'] = $qualifiedNum;
					$resData['unQualifiedBadge'] = $unQualifiedNum;
					$resData['range'] = "[".$row1['minStandardValue'].",".$row1['maxStandardValue']."]";
					$resData['number'] = $row1['number'];
					$resData['missItem'] = $row5['missItem'];
					$resData['children'] = $children;
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
		    }else {
				$data['code'] = 0;
			}
		break;
		case 'getPointStatus':
			$projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			$sql = "SELECT * FROM `tb_measure_measurepoint` WHERE `projectId` = '$projectId' AND `measureId`='$measureId' AND `missItem`='' GROUP BY status";
			$result = $conn->query($sql);
			$res = array();
			$resData = array();
		    $i=0;
		    if($result->num_rows>0)	{
			 	while($row = $result->fetch_assoc()){
					$resData['status'] = $row["status"];
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
			$time=date("Y-m-d H:i:s");
			if($missItem){
				$sql1 = "INSERT INTO tb_measure_measurepoint (measureId,projectId,measurePointName,missItem,createTime) VALUES ('$measureId','$projectId','$measureType','true','$time')";
				$result1 = $conn->query($sql1);
			}else{
				$unQualifiedInfo = json_decode($unQualifiedInfo,true);
				$unQualifiedArr= [];
				$qualifiedArr= [];
				$j=0;
				for($i=0;$i<count($unQualifiedInfo);$i++){
					$unQualifiedNum = $unQualifiedInfo[$i]['number'];
					$unQualifiedVal = $unQualifiedInfo[$i]['value'];
					$unQualifiedArr[$i] = $unQualifiedVal==''?"('$measureId','$projectId','$measureType','$unQualifiedNum','0','不合格','$pointStatus','$time')":"('$measureId','$projectId','$measureType','$unQualifiedNum','$unQualifiedVal','不合格','$pointStatus','$time')";
				}
				$unQualifiedStr = join(",", $unQualifiedArr);
				$sql = "INSERT INTO tb_measure_measurepoint (measureId,projectId,measurePointName,measurePointNumber,pointInitialValue,pointInitialStatus,status,createTime) VALUES $unQualifiedStr";
				$result = $conn->query($sql);
				$j=$i;
				$qualifiedNum=intval($qualifiedNum);
				for($i=0;$i<$qualifiedNum;$i++){ 
					$num = $number.$j;
					$qualifiedArr[$i] = "('$measureId','$projectId','$measureType','$num','hg','合格','$pointStatus','$time')";
					$j++;
				}
				$qualifiedStr = join(",", $qualifiedArr);
				$sql1 = "INSERT INTO tb_measure_measurepoint (measureId,projectId,measurePointName,measurePointNumber,pointInitialValue,pointInitialStatus,status,createTime) VALUES $qualifiedStr";
				$result1 = $conn->query($sql1);
			}
		    if($result1){
				// $data['data'] = $res;
		    }else {
				$data['code'] = 0;
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
			
			$sql = "SELECT measurePointName,count(pointInitialStatus) as num FROM `tb_measure_measurepoint` WHERE missItem='' AND `measureId`='$measureId' AND `projectId`='$projectId' AND  `".$pointStatus."`='不合格'  GROUP BY measurePointName";
			$result = $conn->query($sql);
			if($result){
			  $j = 0;
			  while($row = $result->fetch_assoc()){
					  $resData['measurePointName'] = $row["measurePointName"];
					  $resData['num'] = $row["num"];
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
		case 'getSavedPoint':
			$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
			
			$sql = "SELECT * FROM tb_measure_pointinfo WHERE `measureId`='$measureId'";
			$result = $conn->query($sql);
			$res = array();
			$resData = array();
			$i=0;
			if($result->num_rows>0){
				while ($row = $result -> fetch_assoc()) {
					$resData['Xcoordinate'] = $row["Xcoordinate"];
					$resData['Ycoordinate'] = $row["Ycoordinate"];
					$resData['parXcoordinate'] = $row["parXcoordinate"];
					$resData['parYcoordinate'] = $row["parYcoordinate"];
					$resData['number'] = $row["number"];
					$res[$i] = $resData;
					$i++;
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
<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	
	switch($flag){
		//获取违章项目
		case 'getViolationItem':
			$proTimeStamp = $_POST["proTimeStamp"];
			$sql1 = "SELECT * FROM tb_project WHERE timeStamp='$proTimeStamp'";
			$result1 = $conn->query($sql1);
			$row1 = $result1->fetch_assoc();
			$violationStandard = $row1["violationStandard"];
			$pattern = '/\d+/';
			if (preg_match_all($pattern, $violationStandard ,$arr)){
				$violationStandard = implode("",$arr[0]);
			}
			$numArr = $_POST["numArr"];
			$numArr = explode(',',$numArr);
			$res = array();
			$resData = array();
            $i=0;
            $k=0;
			for($j=0;$j<count($numArr);$j++){
				$val = $numArr[$j];
				if($val!=""){
					$sql2 = "SELECT * FROM `tb_system_database` WHERE `majorCategories`='一般问题' AND number='$val' AND depotId='$violationStandard'";
					$result2 = $conn->query($sql2);
					if($result2->num_rows>0){
						while($row2 = $result2->fetch_assoc()){
							$resData["id"] = $row2["id"];
							$resData["describe"] = $row2["describe"];
							$resData["majorCategories"] = $row2["majorCategories"];
							$resData["number"] = $row2["number"];
							$res["commonlyQuestion"][$i] = $resData;
							$i++;
						}
					}
					$sql3 = "SELECT * FROM `tb_system_database` WHERE `majorCategories`='重大问题' AND number='$numArr[$j]' AND depotId='$violationStandard'";
					$result3 = $conn->query($sql3);
					if($result3->num_rows>0){
						while($row3 = $result3->fetch_assoc()){
							$resData["id"] = $row3["id"];
							$resData["describe"] = $row3["describe"];
							$resData["majorCategories"] = $row3["majorCategories"];
							$resData["number"] = $row3["number"];
							$res["majorQuestion"][$k] = $resData;
							$k++;
						}
					}
					if($i>0||$k>0){
						$data["data"]=$res;
					}else{
						$data["code"] = 0;
					}
				}
				
			}
			
		break;
		//获取搜索的违章项目
		case 'searchViolationItem':
			$proTimeStamp = $_POST["proTimeStamp"];
			$sql1 = "SELECT * FROM tb_project WHERE timeStamp='$proTimeStamp'";
			$result1 = $conn->query($sql1);
			$row1 = $result1->fetch_assoc();
			$violationStandard = $row1["violationStandard"];
			$pattern = '/\d+/';
			if (preg_match_all($pattern, $violationStandard ,$arr)){
				$violationStandard = implode("",$arr[0]);
			}
			$keyWorld = $_POST["keyWorld"];
			$numArr = $_POST["numArr"];
			$numArr = explode(',',$numArr);
			$res = array();
			$resData = array();
            $i=0;
            $k=0;
			for($j=0;$j<count($numArr);$j++){
				$val = $numArr[$j];
				$sql2 = "SELECT * FROM `tb_system_database` WHERE `majorCategories`='一般问题' AND number='$val' AND depotId='$violationStandard' AND `describe` LIKE '%".$keyWorld."%'";
				$result2 = $conn->query($sql2);
				if($result2->num_rows>0){
					while($row2 = $result2->fetch_assoc()){
						$resData["id"] = $row2["id"];
						$resData["describe"] = $row2["describe"];
						$resData["majorCategories"] = $row2["majorCategories"];
						$resData["number"] = $row2["number"];
						$res["commonlyQuestion"][$i] = $resData;
						$i++;
					}
				}
				$sql3 = "SELECT * FROM `tb_system_database` WHERE `majorCategories`='重大问题' AND number='$numArr[$j]' AND depotId='$violationStandard' AND `describe` LIKE '%".$keyWorld."%'";
				$result3 = $conn->query($sql3);
				if($result3->num_rows>0){
					while($row3 = $result3->fetch_assoc()){
						$resData["id"] = $row3["id"];
						$resData["describe"] = $row3["describe"];
						$resData["majorCategories"] = $row3["majorCategories"];
						$resData["number"] = $row3["number"];
						$res["majorQuestion"][$k] = $resData;
						$k++;
					}
				}
			}
			if($i>0||$k>0){
				$data["data"]=$res;
			}else{
				$data["code"] = 0;
			}
		break;
		//新增违章条目
//		case 'addItem':
//			$projectInfo = isset($_POST["projectInfo"]) ? $_POST["projectInfo"] : '';
//			$violationItem = isset($_POST["violationItem"]) ? $_POST["violationItem"] : '';
//			$violationItem = explode(',', $violationItem);
//			
//			$time=getdate();
//			$createTime=$time['year']."-".$time['mon']."-".$time['mday']."/".$time['hours'].":".$time['minutes'].":".$time['seconds'];
//			$projectInfo = json_decode($projectInfo);
//			$projectId = $projectInfo->projectId;
//			$inspectCode = $projectInfo->inspectCode;
//			$projectName = $projectInfo->projectName;
//			$database = $projectInfo->database;
//			$timeStamp = $projectInfo->timeStamp;
//			echo $violationItem;
//			echo $projectInfo;
//			
//	//		print_r($violationItem);
//			
//			//循环插入通知单对应缺陷（违章条目）
////			for($i=0;$i<count($violationItem);$i++){
////				$sql = "INSERT INTO tb_weekly_scene_notice_defect SET `timeStamp` = '$timeStamp',`projectId` = '$projectId',`inspectCode` = '$inspectCode',`projectName` = '$projectName',`defect`='".$violationItem[$i]."',`object` = '$projectName',`database` = '$database',`createTime` = '$createTime',`defectState` = '0'";
////				$result = $conn -> query($sql);
////			}
//	//		print_r($ret_data);
//		break;
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	

?>
<?php
header("Access-Control-Allow-Origin: *");
// 允许任意域名发起的跨域请求
require ("../../../../conn.php");
error_reporting(0);
date_default_timezone_set('PRC');
$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';

switch($flag) {
	//根据所选违章大类获取对应违章条目
	case 'getViolationItem':
		$majorCate = isset($_POST["majorCate"]) ? $_POST["majorCate"] : '';
		$majorCate = explode(',', $majorCate);
		$depotName = isset($_POST["depotName"]) ? $_POST["depotName"] : '';
//		$majorCate = ["一般问题","重大问题"];
//		$depotName = '违章数据库1';
		$cateNum =  count($majorCate);
		if($cateNum> 1){
			$j= 0;
			for($i=0;$i<$cateNum;$i++){
				$sql = "SELECT group_concat(a.`describe` SEPARATOR '||') as items, a.majorCategories FROM tb_system_database AS a , tb_system_database_depot AS b WHERE a.majorCategories = '".$majorCate[$i]."' AND b.id = a.depotId AND depotName = '$depotName'";
//				echo $sql;
				$result = $conn -> query($sql);
				if ($result -> num_rows > 0) {
					while ($row = $result -> fetch_assoc()) {
						$ret_data[$majorCate[$i]]["items"] = $row["items"];
						$j++;
					}
				}
			}
			
		}else{
			$sql = "SELECT group_concat(a.`describe` SEPARATOR '||') as items, a.majorCategories FROM tb_system_database AS a , tb_system_database_depot AS b WHERE a.majorCategories = '".$majorCate[0]."' AND b.id = a.depotId AND depotName = '$depotName'";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$ret_data[$majorCate[0]]["items"] = $row["items"];
					$i++;	
				}
			}
		}
//		print_r($ret_data);
		$conn -> close();
		$json = json_encode($ret_data);
		echo $json;
		break;
	//根据关键字查询条目
	case 'searchViolationItem':
		$majorCate = isset($_POST["majorCate"]) ? $_POST["majorCate"] : '';
		$majorCate = explode(',', $majorCate);
		$depotName = isset($_POST["depotName"]) ? $_POST["depotName"] : '';
		$keyWorld = isset($_POST["keyWorld"]) ? $_POST["keyWorld"] : '';
		
		$cateNum =  count($majorCate);
		if($cateNum> 1){
			$j= 0;
			for($i=0;$i<$cateNum;$i++){
				$sql = "SELECT group_concat(a.`describe` SEPARATOR '||') as items, a.majorCategories FROM tb_system_database AS a , tb_system_database_depot AS b WHERE a.majorCategories = '".$majorCate[$i]."' AND b.id = a.depotId AND depotName = '$depotName' AND a.`describe` LIKE '%".$keyWorld."%'";
//				echo $sql;
				$result = $conn -> query($sql);
				if ($result -> num_rows > 0) {
					while ($row = $result -> fetch_assoc()) {
						$ret_data[$majorCate[$i]]["items"] = $row["items"];
						$j++;
					}
				}
			}
			
		}else{
			$sql = "SELECT group_concat(a.`describe` SEPARATOR '||') as items, a.majorCategories FROM tb_system_database AS a , tb_system_database_depot AS b WHERE a.majorCategories = '".$majorCate[0]."' AND b.id = a.depotId AND depotName = '$depotName' AND a.`describe` LIKE '%".$keyWorld."%'";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$ret_data[$majorCate[0]]["items"] = $row["items"];
					$i++;	
				}
			}
		}
//		print_r($ret_data);
		$conn -> close();
		$json = json_encode($ret_data);
		echo $json;
		break;
	//插入通知单对应新的缺陷记录
	case 'addDefect':
		$formData = isset($_POST["formData"]) ? $_POST["formData"] : '';
		$violationItem = isset($_POST["violationItem"]) ? $_POST["violationItem"] : '';
		$violationItem = explode(',', $violationItem);
		
		$time=getdate();
		$createTime=$time['year']."-".$time['mon']."-".$time['mday']."/".$time['hours'].":".$time['minutes'].":".$time['seconds'];
		$formData = json_decode($formData);
		$projectId = $formData->projectId;
		$inspectCode = $formData->inspectCode;
		$projectName = $formData->projectName;
		$database = $formData->database;
		$timeStamp = $formData->timeStamp;
//		print_r($violationItem);
		
		//循环插入通知单对应缺陷（违章条目）
		for($i=0;$i<count($violationItem);$i++){
			$sql = "INSERT INTO tb_weekly_scene_notice_defect SET `timeStamp` = '$timeStamp',`projectId` = '$projectId',`inspectCode` = '$inspectCode',`projectName` = '$projectName',`defect`='".$violationItem[$i]."',`object` = '$projectName',`database` = '$database',`createTime` = '$createTime',`defectState` = '0'";
			$result = $conn -> query($sql);
		}
//		print_r($ret_data);
		$conn -> close();
		$json = json_encode($ret_data);
		echo $json;
		break;
}
?>
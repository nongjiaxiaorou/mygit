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
		//获取楼栋信息
		case 'getProcedureInfo':
			$projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
			$inspectItem = $_POST["inspectItem"];
			$inspectAcceptId = $_POST["inspectAcceptId"];
			$sql = "SELECT DISTINCT `procedureName` FROM `tb_inspectaccept_procedure` WHERE `inspectItemHead` LIKE '%".$inspectItem."%'";
			$result = $conn->query($sql);
			if($result->num_rows>0){
				$res = array();
				$resData = array();
	            $i=0;
				while($row = $result->fetch_assoc()){
					$resData["procedureName"] = $row["procedureName"];
					$res[$i] = $resData;
					$i++;
				}
				$sql1 = "SELECT DISTINCT inspectProcedure FROM `tb_inspectaccept_notice` WHERE `projectId`='$projectId' AND `measureId`='$inspectAcceptId' AND `state`='已完成'";
				$result1 = $conn->query($sql1);
				if($result->num_rows>0){
					$arr = array();
					$flag=0;
					$j = 5;
					while($row1 = $result1->fetch_assoc()){
						$pos = strpos($row1["inspectProcedure"], "基础");
						if ($pos === false) {
							$arr[$j] = $row1["inspectProcedure"];
							$j++;
						} else {
							$flag=1;
						}
					}
					if($flag){
						$arr[0] = "地基基础工程（天然基础）";
						$arr[1] = "地基基础工程（预应力管桩）";
						$arr[2] = "地基基础工程（钻/冲孔灌注桩）";
						$arr[3] = "地基基础工程（人工挖孔灌注桩）";
						$arr[4] = "地基基础工程（地基与基础安全可靠）";
					}
		//			$res = array_diff($data,$arr); //去除掉已经启动验收的工序
		//			$obj["data"]=array_values($res);
					$data["except"] = array_values($arr);   //去除已验收工序
					$data["data"]=$res;
				}else{
					$data["data"]=$res;
				}
				
				
			}else{
				$data["code"] = 0;
			}
		break;
		//获取检查内容
		case 'getInspectInfo':
			$procedureName = isset($_POST["procedureName"]) ? $_POST["procedureName"] : '';
			$procedureArr = explode(',',$procedureName);
			$res = array();
			$resData = array();
            $i=0;
			for($j=0;$j<count($procedureArr);$j++){
				$sql = "SELECT DISTINCT `inspectItem`,`number` FROM `tb_inspectaccept_procedure` WHERE `procedureName` LIKE '%".$procedureArr[$j]."%' AND NOT ISNULL(number)";
				$result = $conn->query($sql);
				if($result->num_rows>0){
					while($row = $result->fetch_assoc()){
						$resData["inspectItem"] = $row["inspectItem"];
						$resData["number"] = $row["number"];
						$res[$i] = $resData;
						$i++;
					}
				}
				
			}
			if(count($res)>0){
				$data["data"]=$res;
			}else{
				$data["code"] = 0;
			}
		break;
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	

?>
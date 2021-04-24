<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../../conn.php");
	error_reporting(0);
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	
	switch($flag){
		//获取公司
		case 'getCompany':
			$sql = "SELECT DISTINCT tb_system_company.companyName FROM tb_project INNER JOIN tb_system_company ON tb_project.companyId = tb_system_company.id";
			$result = $conn -> query($sql);
			if ($result) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$resData["data"][$i]["companyName"] = $row["companyName"];
				    $i++;
				}
			} else {
				$data['data'] = [];
			}
		break;
		//选择分公司后获取工程
		case 'getComPro':
			$company = isset($_POST["company"]) ? $_POST["company"] : '';
			
			$sql = "SELECT *  FROM `tb_project` where company = '$company' ";
			$result = $conn -> query($sql);
			if ($result) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$resData["data"][$i]["projectName"] = $row["projectName"];
					$resData["data"][$i]["projectId"] = $row["id"];
					$resData["data"][$i]["timeStamp"] = $row["timeStamp"];
					$resData["data"][$i]["violationStandard"] = $row["violationStandard"];
				    $i++;
				}
			} else {
				$data['data'] = [];
			}
		break;
		//获取账号所属分公司
		case 'getComBranch':
			$sql = "SELECT DISTINCT `companyName` FROM `tb_system_company` ";
			$result = $conn -> query($sql);
			if ($result) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$resData["data"][$i]["companyName"] = $row["companyName"];
				    $i++;
				}
			} else {
				$data['data'] = [];
			}
		break;
		//直接获取账号所在工程
		case 'getProject':
			$userInfo = isset($_POST["userInfo"]) ? $_POST["userInfo"] : '';
			$username = explode('/', $userInfo)[0];
			$userId = explode('/', $userInfo)[3];
			
			//在固定职位中
			$sql2 = "SELECT a.proTimeStamp FROM tb_project_fixedpost AS a WHERE (a.proManager = '$userInfo')  OR (a.proChief = '$userInfo')  OR (a.produceManager = '$userInfo')  OR (a.chiefEngineer = '$userInfo')  OR (a.qualityManBranch = '$userInfo')  OR (a.areaLead = '$userInfo')  OR (a.qualityManHead = '$userInfo')  OR (a.technicalLead = '$userInfo')  OR (a.quailtyInspector = '$userInfo')";
			$result2 = $conn->query($sql2);
			$resData = array();
				
			if($result2->num_rows >0){
				$i=0;
				while($row2 = $result2->fetch_assoc()){
					$sql3 = "SELECT * FROM tb_project WHERE `timeStamp`='".$row2['proTimeStamp']."'";
					$result3 = $conn->query($sql3);
					$row3 = $result3 ->fetch_assoc();
				    $resData["data"][$i]["timeStamp"] = $row3["timeStamp"];
					$resData["data"][$i]["projectName"] = $row3["projectName"];
					$resData["data"][$i]["projectId"] = $row3["id"];
					$resData["data"][$i]["proTimeStamp"] = $row3["timeStamp"];
					$resData["data"][$i]["violationStandard"] = $row3["violationStandard"];
					
				    $i++;
				}
				
				$data['data'] = $resData;
			}else{
				$data['data'] = [];
			}
			
			//在自定义管理人员中
			$sql4 = "SELECT * FROM tb_project_administrator where userId = '$userId'";
			$result4 = $conn->query($sql4);
			
			if($result4->num_rows >0){
				$i=0;
				while($row4 = $result4->fetch_assoc()){
					$sql5 = "SELECT * FROM tb_project WHERE `timeStamp`='".$row4['proTimeStamp']."'";
					$result5 = $conn->query($sql5);
					$row5 = $result5 ->fetch_assoc();
			        $resData1["data"][$i]["timeStamp"] = $row5["timeStamp"];
					$resData1["data"][$i]["projectName"] = $row5["projectName"];
					$resData1["data"][$i]["projectId"] = $row3["id"];
					
					$resData1 = array('timeStamp'=>$row5["timeStamp"],'projectName'=>$row5["projectName"],'projectId'=>$row5["id"]);
					array_push($resData['data'],$resData1);
			        $i++;
				}
				
				$data['data'] = $resData;
			}else{
				$data['data'] = [];
			}
		break;
		//选择公司获取对应工程
		case 'getComProject':
			$fgs = $_POST["fgs"];
			$sql = "SELECT * FROM `tb_project` WHERE `company` = '$fgs' ";
			$result = $conn -> query($sql);
			if ($result) {
				while ($row = $result -> fetch_assoc()) {
					$data = $data . '{"projectName":"' . $row["projectName"] . '","projectId":"' . $row["id"] . '","proTimeStamp":"' . $row["timeStamp"] . '"},';
				}
				echo '[' . $data . '{}]';
			} else {
				echo "error";
			}
		break;
	}
	$conn -> close();
	$json = json_encode($resData);
	echo $json;

?>
<?php
	require ("../../../conn.php");
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	$ret_data = '';
	$ret_data1 = '';
	$ret_data["states"] = 'success';
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag){
		//获取所有用户除了超级管理员
		case 'getUser':
			$sql = "SELECT a.`name`, a.phone,a.account,  b.companyName, a.id, b.id AS companyId FROM tb_system_user AS a , tb_system_company AS b WHERE a.companyId = b.id and a.account !='admin' ORDER BY a.id DESC";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["id"] = $row["id"];
					$ret_data["data"][$i]["name"] = $row["name"];
					$ret_data["data"][$i]["phone"] = $row["phone"];
					$ret_data["data"][$i]["account"] = $row["account"];
					$ret_data["data"][$i]["companyName"] = $row["companyName"];
					$ret_data["data"][$i]["companyId"] = $row["companyId"];
					
					$i++;
				}
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
				$ret_data["data"] = [];
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
		//修改基本信息
		case 'changeBaseInfo':
			$timeStamp = isset($_POST["timeStamp"]) ? $_POST["timeStamp"] : '';
			$categories = isset($_POST["categories"]) ? $_POST["categories"] : '';
			$company = isset($_POST["company"]) ? $_POST["company"] : '';
			$province = isset($_POST["province"]) ? $_POST["province"] : '';
			$city = isset($_POST["city"]) ? $_POST["city"] : '';
			$proPosition = isset($_POST["proPosition"]) ? $_POST["proPosition"] : '';
			$architecture = isset($_POST["architecture"]) ? $_POST["architecture"] : '';
			$startTime = isset($_POST["startTime"]) ? $_POST["startTime"] : '';
			$completedTime = isset($_POST["completedTime"]) ? $_POST["completedTime"] : '';
			
			$sql = "SELECT * FROM tb_project WHERE `timeStamp` = '$timeStamp'";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$sql="UPDATE tb_project set categories='$categories',company='$company',province='$province',city='$city',proPosition='$proPosition',architecture='$architecture',startTime='$startTime',completedTime='$completedTime' where `timeStamp` = '$timeStamp'";
				$res = $conn -> query($sql);
				
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
		//获取工程管理人员
		case 'getAdminPerson':
			$timeStamp = isset($_POST["timeStamp"]) ? $_POST["timeStamp"] : '';//工程对应的时间戳	
			
			$sql = "SELECT * FROM tb_project_fixedpost WHERE `protimeStamp` = '$timeStamp'";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data["data"][$i]["proManager"] = $row["proManager"];
					$ret_data["data"][$i]["chiefEngineer"] = $row["chiefEngineer"];
					$ret_data["data"][$i]["produceManager"] = $row["produceManager"];
					
					$ret_data["data"][$i]["proChief"] = $row["proChief"];
					$ret_data["data"][$i]["qualityManBranch"] = $row["qualityManBranch"];
					$ret_data["data"][$i]["areaLead"] = $row["areaLead"];
					
					$ret_data["data"][$i]["qualityManHead"] = $row["qualityManHead"];
					$ret_data["data"][$i]["technicalLead"] = $row["technicalLead"];
					$ret_data["data"][$i]["quailtyInspector"] = $row["quailtyInspector"];
					
					$i++;
				}
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
				$ret_data["data"] = [];
			}
			
			$sql = "SELECT * FROM tb_project_administrator WHERE `protimeStamp` = '$timeStamp'";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$i = 0;
				while ($row = $res -> fetch_assoc()) {
					$ret_data1["data"][$i]["department"] = $row["department"];
					$ret_data1["data"][$i]["post"] = $row["post"];
					$ret_data1["data"][$i]["adminPerson"] = $row["username"].'/'.$row["phone"].'/'.$row["company"];
					$ret_data1["data"][$i]["userId"] = $row["userId"];
//					$ret_data["data"][$i]["username"] = $row["username"];
					
					$i++;
				}
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
				$ret_data["data"] = [];
			}
			$conn -> close();
			$ret_data['nodeList'] = $ret_data1;
			$json = json_encode($ret_data);
			echo $json;
			break;
		//保存管理人员
		case 'saveAdminPerson':
			$nodeList = isset($_POST["nodeList"]) ? $_POST["nodeList"] : '';
			$presonList = isset($_POST["presonList"]) ? $_POST["presonList"] : '';
			$timeStamp = isset($_POST["timeStamp"]) ? $_POST["timeStamp"] : '';//工程对应的时间戳			
						
			$nodeList = json_decode($nodeList);
			$presonList = json_decode($presonList);
				
			$proManager = $presonList->proManager;//项目经理			
			$proChief = $presonList->proChief;//项目总工			
			$produceManager = $presonList->produceManager;//生产经理			
			$chiefEngineer = $presonList->chiefEngineer;//总工程师			
			$qualityManBranch = $presonList->qualityManBranch;//部门经理			
			$areaLead = $presonList->areaLead;//片区负责人			
			$qualityManHead = $presonList->qualityManHead;//质量经理			
			$technicalLead = $presonList->technicalLead;//技术主管			
			$quailtyInspector = $presonList->quailtyInspector;//质量检查员
			
			//将以上人员存进我的工程表
			$sql=" SELECT * FROM tb_project_fixedpost WHERE `protimeStamp` = '$timeStamp'";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {//已经存在则更新
				$sql = "UPDATE tb_project_fixedpost set `proManager` = '$proManager',`proChief` = '$proChief',`produceManager` = '$produceManager',`chiefEngineer` = '$chiefEngineer',`qualityManBranch` = '$qualityManBranch',`areaLead` = '$areaLead',`qualityManHead` = '$qualityManHead',`technicalLead` = '$technicalLead',`quailtyInspector` = '$quailtyInspector' where proTimeStamp = '$timeStamp' ";
				$result = $conn->query($sql);
			}else{
				$sql = "INSERT INTO tb_project_fixedpost set `proManager` = '$proManager',`proChief` = '$proChief',`produceManager` = '$produceManager',`chiefEngineer` = '$chiefEngineer',`qualityManBranch` = '$qualityManBranch',`areaLead` = '$areaLead',`qualityManHead` = '$qualityManHead',`technicalLead` = '$technicalLead',`quailtyInspector` = '$quailtyInspector' , proTimeStamp = '$timeStamp' ";
				$result = $conn->query($sql);
			}
			
			foreach($nodeList as $obj){
				$adminPerson = $obj->adminPerson;//管理人员信息
				$post = $obj->post;//岗位
				$department = $obj->department;//部门
				$userId = $obj->userId;//用户id
				$username = explode('/', $adminPerson)[0];
				$phone = explode('/', $adminPerson)[1];
				$company = explode('/', $adminPerson)[2];
//				print_r(explode('/', $adminPerson)[2]);

				$sql="SELECT * FROM tb_project_administrator where userId = '$userId' and `protimeStamp` = '$timeStamp'";
				$res = $conn->query($sql);

				if($res -> num_rows > 0){//已存在该用户则更新这条数据
					$sql="UPDATE tb_project_administrator set `userId` = '$userId',`username` = '$username',`phone` = '$phone',`department` = '$department',`post` = '$post',`company` = '$company' WHERE `userId`='$userId' ";
					$result = $conn->query($sql);
				}else{//用户不存在则新增进与该工程相关的管理人员
					$sql = "INSERT INTO tb_project_administrator set `proTimeStamp` = '$timeStamp',`userId` = '$userId',`username` = '$username',`phone` = '$phone',`department` = '$department',`post` = '$post',`company` = '$company' ";
					$result = $conn->query($sql);
				}
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
		//删除管理人员
		case 'delPerson':
			$userId = isset($_POST["userId"]) ? $_POST["userId"] : '';
			
			$sql = "SELECT * FROM tb_project_administrator where userId = '$userId'";
			$res = $conn -> query($sql);
			if ($res -> num_rows > 0) {
				$sql = "DELETE  FROM tb_project_administrator  where userId = '$userId'";
				$res = $conn -> query($sql);
				$ret_data["states"] = 'success';
			}else{
				$ret_data["states"] = 'error';
				$ret_data["data"] = [];
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
	}

?>
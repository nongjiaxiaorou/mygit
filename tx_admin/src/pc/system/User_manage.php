<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	
	switch($flag){
		//获取总公司账号
		case 'CompanyAccount':
			$sql = "SELECT B.id, A.companyRank, B.account, B.`password`, B.`name`, B.email, B.department, B.phone, B.quit, B.authority, B.privilegeAccount FROM tb_system_company AS A , tb_system_user AS B WHERE A.id = B.companyId  AND A.companyRank='总公司' AND B.privilegeAccount='1' ORDER BY id DESC";
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
	            $i=0;
	            while($row = $result->fetch_assoc()){
					$resData['index'] = $i+1;
					$resData['id'] = $row['id'];
	                $resData['department'] = $row['department'];
	                $resData['username'] = $row['name'];
	                $resData['phone'] = $row['phone'];
	                $resData['e_mail'] = $row['email'];
	                $resData['account'] = $row['account'];
	                $resData['password'] = $row['password'];
					$resData['state'] = $row['quit']==''?'在职':'停职';
					$resData['authority'] = $row['authority'];
					$res[$i] = $resData;
	                $i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
			break;
		//获取分公司账号
		case 'midCompanyAccount':
			$sql = "SELECT B.id, A.companyRank, B.account, B.`password`, B.`name`, B.email, B.department, B.phone, B.quit, B.authority, B.privilegeAccount FROM tb_system_company AS A , tb_system_user AS B WHERE A.id = B.companyId  AND A.companyRank='分公司' AND B.privilegeAccount='1' ORDER BY id DESC";
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
	            $i=0;
	            while($row = $result->fetch_assoc()){
					$resData['index'] = $i+1;
					$resData['id'] = $row['id'];
	                $resData['department'] = $row['department'];
	                $resData['username'] = $row['name'];
	                $resData['phone'] = $row['phone'];
	                $resData['e_mail'] = $row['email'];
	                $resData['account'] = $row['account'];
	                $resData['password'] = $row['password'];
					$resData['state'] = $row['quit']==''?'在职':'停职';
					$res[$i] = $resData;
	                $i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
			break;
		//获取用户自定义账号
		case 'userDefinedAccount':
			$sql = "SELECT * FROM  tb_system_user WHERE `customAccount`='1' ORDER BY id DESC";
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
	            $i=0;
	            while($row = $result->fetch_assoc()){
					$resData['index'] = $i+1;
					$resData['id'] = $row['id'];
	                $resData['department'] = $row['department'];
	                $resData['username'] = $row['name'];
	                $resData['phone'] = $row['phone'];
	                $resData['e_mail'] = $row['email'];
	                $resData['account'] = $row['account'];
	                $resData['password'] = $row['password'];
					$resData['state'] = $row['quit']==''?'在职':'停职';
					$resData['authority'] = $row['authority'];
					$res[$i] = $resData;
	                $i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
			break;
		//新增用户账号
		case 'addUserAccount':
			$account = isset($_POST["account"])?$_POST["account"]:'';
			$password = isset($_POST["password"])?$_POST["password"]:'';
			$username = isset($_POST["username"])?$_POST["username"]:'';
			$email = isset($_POST["email"])?$_POST["email"]:'';
			$phone = isset($_POST["phone"])?$_POST["phone"]:'';
			$department = isset($_POST["department"])?$_POST["department"]:'';
			$createTime = isset($_POST["createTime"])?$_POST["createTime"]:'';
			$companyId = isset($_POST["companyId"])?$_POST["companyId"]:'';
			$isChecked = isset($_POST["isChecked"])?$_POST["isChecked"]:'';
			if($isChecked==='true'){
				$sql = "INSERT INTO tb_system_user set `account` = '$account',`password` = '$password',`name` = '$username',`email` = '$email',`phone` = '$phone',`department` = '$department',`createTime` = '$createTime',`customAccount` = '0',equipment='电脑',companyId='$companyId',privilegeAccount='1',authority='用户管理|公司集团定义|违章数据库管理|实测实量标准|工程类别定义|系统操作日志'";
			}else{
				$sql = "INSERT INTO tb_system_user set `account` = '$account',`password` = '$password',`name` = '$username',`email` = '$email',`phone` = '$phone',`department` = '$department',`createTime` = '$createTime',`customAccount` = '1',equipment='电脑',companyId='$companyId',privilegeAccount='0'";
			}
			$result = $conn->query($sql);
			if(!$result){
				$data['code'] = 0;
			}
			break;
		//删除账号
		case 'deleteAccount':
			$id = isset($_POST["id"])?$_POST["id"]:'';
			$id_arr = json_decode($id);
			for($i=0;$i<count($id_arr);$i++){
				$sql = "DELETE FROM tb_system_user WHERE `id`='$id_arr[$i]'";
				$result = $conn->query($sql);
			}
			if(!$result){
				$data['code'] = 0;
			}
			break;
		//更新用户账号
		case 'updateUserAccount':
			$id = isset($_POST["id"])?$_POST["id"]:'';
			// $account = isset($_POST["account"])?$_POST["account"]:'';
			$password = isset($_POST["password"])?$_POST["password"]:'';
			$username = isset($_POST["username"])?$_POST["username"]:'';
			$email = isset($_POST["email"])?$_POST["email"]:'';
			$phone = isset($_POST["phone"])?$_POST["phone"]:'';
			$department = isset($_POST["department"])?$_POST["department"]:'';
			$createTime = isset($_POST["createTime"])?$_POST["createTime"]:'';
			$sql = "UPDATE tb_system_user set `password` = '$password',`name` = '$username',`email` = '$email',`phone` = '$phone',`department` = '$department',`createTime` = '$createTime',equipment='电脑' WHERE `id`='$id'";
			$result = $conn->query($sql);
			if(!$result){
				$data['code'] = 0;
			}
			break;
		//获取部门
		case 'getDepartment':
			$sql = "SELECT `id`,`companyName` FROM tb_system_company";
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
			    $i=0;
			    while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
			        $resData['department'] = $row['companyName'];
					$res[$i] = $resData;
			        $i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
			break;
		//
		case 'checkAccountFlag':
			$account = isset($_POST["account"])?$_POST["account"]:'';
			$sql = "SELECT * FROM tb_system_user WHERE `account`='$account'";
			$result = $conn->query($sql);
			if($result->num_rows==0){
				$data['code'] = 0;
			}
			break;
		//更新权限
		case 'updateAuthorityFlag':
			$authority = isset($_POST["authority"])?$_POST["authority"]:'';
			$account = isset($_POST["account"])?$_POST["account"]:'';
			$sql = "UPDATE tb_system_user SET `authority`='$authority' WHERE `account`='$account'";
			$result = $conn->query($sql);
			if(!$result){
				$data['code'] = 0;
			}
			break;
		//搜索获取账号绑定工程
		case 'searchPro':
			$account = isset($_POST["account"])?$_POST["account"]:'';
			
			$sql1 = "SELECT * FROM tb_system_user WHERE `account`='$account'";
			$result1 = $conn->query($sql1);
			$row1 = $result1 ->fetch_assoc();
			$userInfo = $row1['name'].'/'.$row1['phone'].'/'.$row1['department'].'/'.$row1['id'];
			if($result1->num_rows >0){
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
						$resData["data"][$i]["categories"] = $row3["categories"];
						$resData["data"][$i]["company"] = $row3["company"];
						$resData["data"][$i]["startTime"] = $row3["startTime"];
						$resData["data"][$i]["completedTime"] = $row3["completedTime"];
						$resData["data"][$i]["proState"] = $row3["proState"];
						$resData["data"][$i]["isCompleted"] = $row3["isCompleted"];
						$resData["data"][$i]["province"] = $row3["province"];//地区省
						$resData["data"][$i]["city"] = $row3["city"];//地区市
						$resData["data"][$i]["section"] = $row3["section"];//区段
						$resData["data"][$i]["architecture"] = $row3["architecture"];//建筑面积
						$resData["data"][$i]["proPosition"] = $row3["proPosition"];
				        $i++;
					}
					$data['data'] = $resData;
	
				}
			}else{
				$data['data'] = [];
			}
			//在自定义管理人员中
			$sql4 = "SELECT * FROM tb_project_administrator where username = '".$row1['name']."' and phone ='".$row1['phone']."'";
			$result4 = $conn->query($sql4);
			
			if($result4->num_rows >0){
				$i=0;
				while($row4 = $result4->fetch_assoc()){
					$sql5 = "SELECT * FROM tb_project WHERE `timeStamp`='".$row4['proTimeStamp']."'";
					$result5 = $conn->query($sql5);
					$row5 = $result5 ->fetch_assoc();
			        $resData1["data"][$i]["timeStamp"] = $row5["timeStamp"];
					$resData1["data"][$i]["projectName"] = $row5["projectName"];
					$resData1["data"][$i]["categories"] = $row5["categories"];
					$resData1["data"][$i]["company"] = $row5["company"];
					$resData1["data"][$i]["startTime"] = $row5["startTime"];
					$resData1["data"][$i]["completedTime"] = $row5["completedTime"];
					$resData1["data"][$i]["proState"] = $row5["proState"];
					$resData1["data"][$i]["isCompleted"] = $row5["isCompleted"];
					$resData1["data"][$i]["province"] = $row5["province"];//地区省
					$resData1["data"][$i]["city"] = $row5["city"];//地区市
					$resData1["data"][$i]["section"] = $row5["section"];//区段
					$resData1["data"][$i]["architecture"] = $row5["architecture"];//建筑面积
					$resData1["data"][$i]["proPosition"] = $row5["proPosition"];
					
					$resData1 = array('timeStamp'=>$row5["timeStamp"],'projectName'=>$row5["projectName"],'categories'=>$row5["categories"],'company'=>$row5["company"],'startTime'=>$row5["startTime"],'completedTime'=>$row5["completedTime"],'proState'=>$row5["proState"],'isCompleted'=>$row5["isCompleted"],'province'=>$row5["province"],'city'=>$row5["city"],'section'=>$row5["section"],'architecture'=>$row5["architecture"],'proPosition'=>$row5["proPosition"]);
					array_push($resData['data'],$resData1);
			        $i++;
				}
				
				$data['data'] = $resData;
			}else{
				$data['data'] = [];
			}
//			print_r($data["data"]);
			break;
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	
?>
<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$isAdmin = '';
	$account = isset($_POST["account"])?$_POST["account"]:'';
	$password = isset($_POST["password"])?$_POST["password"]:'';
//	$account = 'tx';
//	$password = '123456';
	$ret_data = array();
	switch($flag){
		case 'Login':
			$sql = "SELECT * FROM tb_system_user WHERE `account` = '$account' ";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			if($password==$row["password"])	{
				if($account=='admin'){
					$isAdmin = '1';
					$ret_data['status']='success';
					$ret_data['isAdmin']=$isAdmin;//是否为管理员
					
					$ret_data['userid'] = $row['id'];
					$ret_data['username'] = $row['name'];
					$ret_data['department'] = $row['department'];
					$ret_data['phone'] = $row['phone'];
					$ret_data['account'] = $row['account'];
				}else{
					$sql1 = "SELECT a.id AS userId, a.account, a.`password`, a.`name`, b.companyRank, b.id AS companyId, a.privilegeAccount, a.authority, b.companyName FROM tb_system_user AS a , tb_system_company AS b WHERE a.companyId = b.id and account = '$account' ";//联查
					$result1 = $conn->query($sql1);
					$row1 = $result1->fetch_assoc();
					if($password==$row1["password"])	{
						$ret_data['status']='success';
						$ret_data['isAdmin']=$isAdmin;//是否为管理员
						
						$ret_data['userid'] = $row['id'];
						$ret_data['username'] = $row['name'];
						$ret_data['department'] = $row['department'];
						$ret_data['phone'] = $row['phone'];
						$ret_data['account'] = $row['account'];
						$ret_data['companyId'] = $row['companyId'];
						$ret_data['privilegeAccount'] = $row['privilegeAccount'];//是否为特权账号
						$ret_data['authority'] = $row['authority'];
						
					}else{
						$ret_data['status']='error';
					}
				}
			}
	}
	$data['code']  = 20000;
	$data['data']  = $ret_data;
	
	$json = json_encode($data);
	echo $json;
	$conn->close();	
?>
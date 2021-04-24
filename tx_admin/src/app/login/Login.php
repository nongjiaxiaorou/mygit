<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	if($flag == "Login"){
		$account=$_POST['account'];
		$password=$_POST['password'];
		$sql = "select * from tb_system_user where account='".$account."'";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			if($password==$row["password"])	{
				$jsonresult='success';
				$account=$row["account"];
		   		$phone=$row["phone"];
		   		$id=$row["id"];
		   		$department=$row["department"];
		   		$companyId=$row["companyId"];
		   		$username=$row["name"];
		   		$email=$row["email"];
		   		
		   		$sql1 = "select * from tb_system_company where id='".$companyId."'";//获取权限
		   		$result1 = $conn->query($sql1);
			    $row1 = $result1->fetch_assoc();
			    $companyName=$row1["companyName"];//公司名称
			    $companyRank=$row1["companyRank"];//公司层级
			    $timeStamp=$row1["timeStamp"];//公司时间戳
		   		
			}else{
				$jsonresult='error'; 
			}	
			$json = '{
				"result":"'.$jsonresult.'",
				"email":"'.$email.'",
				"account":"'.$account.'",
				"phone":"'.$phone.'",
				"userId":"'.$id.'",
				"username":"'.$username.'",
				"department":"'.$department.'",
				"companyId":"'.$companyId.'",
				"companyName":"'.$companyName.'",
				"companyRank":"'.$companyRank.'"
			}';
			
			echo $json;	     
		
	}

?>
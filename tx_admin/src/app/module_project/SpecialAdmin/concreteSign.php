<?php
	require ("../../../../conn.php");
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	$date = date("Y-m-d H:i:s");
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	$cardid = isset($_POST["cardid"])?$_POST["cardid"] : '';
	$userid = isset($_POST["userid"])?$_POST["userid"] : '';
	$sign = isset($_POST["sign"])?$_POST["sign"] : '';
	$idea = isset($_POST["idea"])?$_POST["idea"] : '';
	
	switch($flag){
		//质量员签名
		case 'qualityController':
		//检查是不是第一次签名
		$sql = "select * from tb_concrete_sign where cardid = '".$cardid."'";
		$result = $conn->query($sql);
		if($result -> num_rows>0){
			//如果不是第一次签名
			$sql = "update tb_concrete_sign set sign1= '".$sign."',datetime1= '".$date."',idea1= '".$idea."' ,signer1id= '".$userid."' where cardid = '".$cardid."'";
			$res = $conn->query($sql);
			if ($res) {
				$ret_data['status']='success';
			}else{
				$ret_data['status']='error';
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
		}else{
			//第一次签名
			$sql = "INSERT INTO tb_concrete_sign (cardid,sign1,datetime1,idea1,signer1id) VALUES ('$cardid','$sign','$date','$idea','$userid')";
			$res = $conn -> query($sql);
			if ($res) {
				$ret_data['status']='success';
			}else{
				$ret_data['status']='error';
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
		}
		
		
        //生产经理签名
		case 'ProductionManager':
		//检查是不是第一次签名
		$sql = "select * from tb_concrete_sign where cardid = '".$cardid."'";
		$result = $conn->query($sql);
		if($result -> num_rows>0){
			//如果不是第一次签名
			$sql = "update tb_concrete_sign set sign2= '".$sign."',datetime2= '".$date."',idea2= '".$idea."' ,signer2id= '".$userid."' where cardid = '".$cardid."'";
			$res = $conn->query($sql);
			if ($res) {
				$ret_data['status']='success';
			}else{
				$ret_data['status']='error';
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
		}else{
			//第一次签名
			$sql = "INSERT INTO tb_concrete_sign (cardid,sign2,datetime2,idea2,signer2id) VALUES ('$cardid','$sign','$date','$idea','$userid')";
			$res = $conn -> query($sql);
			if ($res) {
				$ret_data['status']='success';
			}else{
				$ret_data['status']='error';
			}
			$conn -> close();
			$json = json_encode($ret_data);
			echo $json;
			break;
		}
	}

?>
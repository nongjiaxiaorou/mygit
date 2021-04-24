<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$token = isset($_POST["token"])?$_POST["token"]:'';
//	$password = isset($_POST["password"])?$_POST["password"]:'';
	
	$ret_data['introduction']  = 'admin2';
	$ret_data['roles']  = 'admin';
	$ret_data['name']  = 'admin';
	$ret_data['avatar']  = 'admin1';
	
	$data['code']  = 20000;
	$data['data']  = $ret_data;
	
	$json = json_encode($data);
	echo $json;
	$conn->close();	
?>
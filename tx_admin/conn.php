<?php	
    header("Access-Control-Allow-Origin:*");
	//连接数据库
//	$servername = "192.168.0.167:3306";
//	$username = "root";
//	$password = "asdf1@34";
//	$dbname = "dfr";	

	// $servername = "112.74.34.150:3306"; // 服务器
	$servername = "127.0.0.1:3306";		// 本地
	$username = "root";
	$password = "123456";
	$dbname = "txzlgl";
	$conn = new mysqli($servername, $username, $password, $dbname);	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}else{
//		 echo "Connected successfully";
	}	
//	$api="http://192.168.0.167:8280";//服务器ip:port
//	print_result();
//	function print_result(){
//		global $api;
//	}
?> 
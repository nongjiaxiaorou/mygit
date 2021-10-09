<?php
	require("../../../../conn.php");
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	$id = isset($_POST["id"]) ? $_POST["id"] : '';
	// $ret_data["picName1"] = '';
	// $ret_data["picName2"] = '';
	$ret_data = '';
	$ret_data["success"] = 'success';
//	$lcsl= $_POST["lcsl"];
//	echo $tzcs;  
//	echo $a[0];
	function microtime_float() 
	{
   		list($usec, $sec) = explode(" ", microtime());
   		return ((float)$usec + (float)$sec* 10000);
	}
	$sjc1= microtime_float();
// 允许上传的图片后缀
$allowedExts = array("gif","jpeg", "jpg", "png","bmp","wmf");
$temp = explode(".", $_FILES["file"]["name"]);
$extension1 = end($temp);     // 获取文件后缀名
$extension=strtolower($extension1);


if ((($_FILES["file"]["type"] == "image/gif")||($_FILES["file"]["type"] == "image/jpeg")||($_FILES["file"]["type"] =="image/png"))&&in_array($extension, $allowedExts))
{
	if ($_FILES["file"]["error"] > 0)
	{
		echo "错误：: " . $_FILES["file"]["error"] . "<br>";
		$ywjsc = "0";
	}
	else
	{
		if (file_exists("upload/" . $_FILES["file"]["name"]))
		{
			echo $_FILES["file"]["name"] . " 文件已经存在。 ";
		}
		else
		{   
			$a="../../../images/pc_pic/floorPic/manualPic/";
		    $dst_file=$sjc1.'.'.$extension1;
		    $dst_file = $a.iconv('utf-8', 'gbk', $dst_file);
			// 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
			move_uploaded_file($_FILES['file']['tmp_name'], $dst_file);
//			echo "文件存储在: " . "upload/" . $_FILES["file"]["name"];
			// $picName= $sjc1;
			switch($flag){
				case 'saveUploadPic':
					$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';

					$status = isset($_POST["importStatus"]) ? $_POST["importStatus"] : '';

					// $i = 0;
					// $picNum = count($floorArr);
					// while($i<$picNum){  
					// 	$build = $floorArr[$i]["build"];
					// 	$section = $floorArr[$i]["section"];
					// 	$floor = $floorArr[$i]["floor"];
					// 	$sql1="SELECT * FROM tb_project_floor_pic WHERE proTimeStamp='$proTimeStamp'  AND build='$build' AND section='$section' and floor='$floor'";
					// 	$result1 = $conn->query($sql1);
					// 	$count1 = mysqli_num_rows($result1);// 函数返回结果集中行的数量
						// $sql1="SELECT status FROM tb_measure_measurepoint where measureId='$id' order by id desc;";
						// $res = $conn -> query($sql1);
						// $row = $res -> fetch_assoc(); 
						// $status = $row["status"];
						$picName = $sjc1.'.'.$extension1;
						
						if($status == '初测') {
							$sql="UPDATE tb_inspectaccept_measure SET manualPrimaryPicName='$picName' WHERE proTimeStamp='$proTimeStamp' AND id='$id'";
                            $result = $conn->query($sql);
                            if ($result) {
                                $ret_data["picName"] = $picName;
                                $ret_data["success"] = 'success';
                            } 
                            else {
                                $ret_data['success'] = 'error';
							}
						}
						else if($status == '复测') {
							$sql="UPDATE tb_inspectaccept_measure SET manualRetestPicName='$picName' WHERE proTimeStamp='$proTimeStamp' AND id='$id'";
                            $result = $conn->query($sql);
                            if ($result) {
                                $ret_data["picName"] = $picName;
                                $ret_data["success"] = 'success';
                            } 
                            else {
                                $ret_data['success'] = 'error';
							}
						}
	                    //echo $sql;
						
							// $sql = "INSERT INTO tb_project_floor_pic SET proTimeStamp='$proTimeStamp',projectName='$projectName',build='$build',floor='$floor',picName='$picName',section='$section'";
							// if($conn->query($sql) === TRUE){
							// 	echo "保存成功";
					    	// }
				
				$json = json_encode($ret_data);
				echo $json;	
				break;
				
			}
		}
	}
}
else
{
	echo "非法的文件格式";
	
}

	$conn->close();
?>
<?php
require("../../../../conn.php");
error_reporting(E_ALL || ~E_NOTICE);
date_default_timezone_set('PRC');
	$flag =$_POST['flag'];
//	$flag ="pic_uload";
	switch($flag){
		case 'saveFilePic':
			$timeStamp =$_POST['timeStamp'];
			$fileCategory =$_POST['fileCategory'];
			$img_path='../../../images/app_pic/programmePic/';
	     	$pic_obj =$_POST['imageBaseList'];  //获取base64数据流
			$obj=json_decode($pic_obj,true);
			$img_name = time();
			$res = '{"status":0,"info":"请上传正确格式的图片"}';		
			$sql1 = "SELECT * FROM tb_weekly_file_notice_filepic  WHERE noticeTimeStamp='$timeStamp'";
//			echo $fileCategory;
			$result1 = $conn->query($sql1);
			if($result1->num_rows>0){
				while($row1=$result1->fetch_assoc()){
					if($fileCategory=='problem'){
						$pic = $row1["problemPic"];
					}else if($fileCategory=='meeting'){
						$pic = $row1["meetingPic"];
					}else if($fileCategory=='summary'){
						$pic = $row1["summaryPic"];
					}else if($fileCategory=='other'){
						$pic = $row1["otherPic"];
					}
					$a = explode("||",$pic);
					for($i=0;$i<count($a)-1;$i++){
						$lj = $img_path.$a[$i];
						if(file_exists($lj)){
							unlink($lj);
						}
					}
				}
			}
			$arr = [];
			for($i=0;$i<count($_FILES);$i++){
				$images_name="";
				if(($_FILES["img".$i]["type"] == "image/gif")||($_FILES["img".$i]["type"] == "image/jpeg")||($_FILES["img".$i]["type"] =="image/png")){
					$temp = explode(".", $_FILES["img".$i]["name"]);
					$extension1 = end($temp);     // 获取文件后缀名
					$extension=strtolower($extension1);				
			        move_uploaded_file($_FILES["img".$i]["tmp_name"],$img_path.$img_name.$i.'.'.$extension);  
			        $images_name  .=  $img_name.$i.'.'.$extension;
					$arr[$i] = $images_name."||";
				}
			}
			$arr = implode($arr);
			if($fileCategory=='problem'){
				$sql2 = "UPDATE `tb_weekly_file_notice_filepic` SET `problemPic`='$arr' WHERE noticeTimeStamp = '$timeStamp'";				
			}else if($fileCategory=='meeting'){
				$sql2 = "UPDATE `tb_weekly_file_notice_filepic` SET `meetingPic`='$arr' WHERE noticeTimeStamp = '$timeStamp'";
			}else if($fileCategory=='summary'){
				$sql2 = "UPDATE `tb_weekly_file_notice_filepic` SET `summaryPic`='$arr' WHERE noticeTimeStamp = '$timeStamp'";
			}else if($fileCategory=='other'){
				$sql2 = "UPDATE `tb_weekly_file_notice_filepic` SET `otherPic`='$arr' WHERE noticeTimeStamp = '$timeStamp'";
			}		
			$result2 = $conn->query($sql2);
			if($result2){
				$res = '{"status":1}';
			}
	      if($pic_obj!="{}"){
				foreach($obj as $key=>$val){
					$images_name = "";
					$images_name =  $img_name.$i.'.'.'jpg';
					$base64 = explode(",", $val);
					$data = base64_decode($base64[1]);
					file_put_contents($img_path.$images_name, $data);
					if($fileCategory=='problem'){
						$sql3 = "UPDATE `tb_weekly_file_notice_filepic` SET `problemPic` = CONCAT(`problemPic`,'$images_name','||') WHERE noticeTimeStamp = '$timeStamp'";
					}else if($fileCategory=='meeting'){
						$sql3 = "UPDATE `tb_weekly_file_notice_filepic` SET `meetingPic` = CONCAT(`meetingPic`,'$images_name','||') WHERE noticeTimeStamp = '$timeStamp'";
					}else if($fileCategory=='summary'){
						$sql3 = "UPDATE `tb_weekly_file_notice_filepic` SET `summaryPic` = CONCAT(`summaryPic`,'$images_name','||') WHERE noticeTimeStamp = '$timeStamp'";
					}else if($fileCategory=='other'){
						$sql3 = "UPDATE `tb_weekly_file_notice_filepic` SET `otherPic` = CONCAT(`otherPic`,'$images_name','||') WHERE noticeTimeStamp = '$timeStamp'";
					}
					$i++;
				    $result3 = $conn->query($sql3);
				    if($result3){
				    	$res = '{"status":1}';
				    }
				}
			}
			echo $res;
		break;
	}
	
$conn->close();
?> 
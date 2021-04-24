<?php
require("../../../../conn.php");
error_reporting(E_ALL || ~E_NOTICE);
date_default_timezone_set('PRC');
	$flag =$_POST['flag'];
//	$flag ="pic_uload";
	switch($flag){
		case 'saveDefectPic':
			$id =$_POST['id']; 
			$img_path='../../../images/app_pic/defectPic/';
	      	$pic_obj =$_POST['imageBaseList'];  //获取base64数据流
			$obj=json_decode($pic_obj,true);
			$img_name = time();
			$res = '{"status":0,"info":"请上传正确格式的图片"}';
			$sql1 = "SELECT * FROM tb_weekly_scene_notice_defect  WHERE id='$id'";
			$result1 = $conn->query($sql1);
			if($result1->num_rows>0){
				while($row1=$result1->fetch_assoc()){
					$pic = $row1["defectPicture"];
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
			$sql2 = "UPDATE  `tb_weekly_scene_notice_defect` SET `defectPicture`='$arr' where id='$id'";
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
					$sql3 = "UPDATE `tb_weekly_scene_notice_defect` SET `defectPicture` = CONCAT(`defectPicture`,'$images_name','||') WHERE  id='$id'";
					$i++;
				    $result3 = $conn->query($sql3);
				    if($result3){
				    	$res = '{"status":1}';
				    }
				}
			}
			echo $res;
			break;
		case 'saveReplyPic':
			$id =$_POST['id']; 
			$img_path='../../../images/app_pic/replyPic/';
	      	$pic_obj =$_POST['imageBaseList'];  //获取base64数据流
			$obj=json_decode($pic_obj,true);
			$img_name = time();
			$res = '{"status":0,"info":"请上传正确格式的图片"}';
			$sql1 = "SELECT * FROM tb_weekly_scene_notice_defect  WHERE id='$id'";	
			$result1 = $conn->query($sql1);
			if($result1->num_rows>0){
				while($row1=$result1->fetch_assoc()){
					$pic = $row1["replyPicture"];
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
			$sql2 = "UPDATE  `tb_weekly_scene_notice_defect` SET `replyPicture`='$arr' WHERE id = '$id'";
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
					$sql3 = "UPDATE `tb_weekly_scene_notice_defect` SET `replyPicture` = CONCAT(`replyPicture`,'$images_name','||') WHERE id = '$id'";
					$i++;
				    $result3 = $conn->query($sql3);
				    if($result3){
				    	$res = '{"status":1}';
				    }
				}
			}
			echo $res;
			break;
		case 'saveFilePic':
			$timeStamp =$_POST['timeStamp'];
			$fileCategory =$_POST['fileCategory'];
			$img_path='../../../images/app_pic/filePic/';
	     	$pic_obj =$_POST['imageBaseList'];  //获取base64数据流
			$obj=json_decode($pic_obj,true);
			$img_name = time();
			$res = '{"status":0,"info":"请上传正确格式的图片"}';		
			$sql1 = "SELECT * FROM tb_weekly_scene_notice_filepic  WHERE noticeTimeStamp='$timeStamp'";
//			echo $fileCategory;
			$result1 = $conn->query($sql1);
			if($result1->num_rows>0){
				while($row1=$result1->fetch_assoc()){
					if($fileCategory=='scene'){
						$pic = $row1["scenePic"];
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
			if($fileCategory=='scene'){
				$sql2 = "UPDATE `tb_weekly_scene_notice_filepic` SET `scenePic`='$arr' WHERE noticeTimeStamp = '$timeStamp'";				
			}else if($fileCategory=='meeting'){
				$sql2 = "UPDATE `tb_weekly_scene_notice_filepic` SET `meetingPic`='$arr' WHERE noticeTimeStamp = '$timeStamp'";
			}else if($fileCategory=='summary'){
				$sql2 = "UPDATE `tb_weekly_scene_notice_filepic` SET `summaryPic`='$arr' WHERE noticeTimeStamp = '$timeStamp'";
			}else if($fileCategory=='other'){
				$sql2 = "UPDATE `tb_weekly_scene_notice_filepic` SET `otherPic`='$arr' WHERE noticeTimeStamp = '$timeStamp'";
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
					if($fileCategory=='scene'){
						$sql3 = "UPDATE `tb_weekly_scene_notice_filepic` SET `scenePic` = CONCAT(`scenePic`,'$images_name','||') WHERE noticeTimeStamp = '$timeStamp'";
					}else if($fileCategory=='meeting'){
						$sql3 = "UPDATE `tb_weekly_scene_notice_filepic` SET `meetingPic` = CONCAT(`meetingPic`,'$images_name','||') WHERE noticeTimeStamp = '$timeStamp'";
					}else if($fileCategory=='summary'){
						$sql3 = "UPDATE `tb_weekly_scene_notice_filepic` SET `summaryPic` = CONCAT(`summaryPic`,'$images_name','||') WHERE noticeTimeStamp = '$timeStamp'";
					}else if($fileCategory=='other'){
						$sql3 = "UPDATE `tb_weekly_scene_notice_filepic` SET `otherPic` = CONCAT(`otherPic`,'$images_name','||') WHERE noticeTimeStamp = '$timeStamp'";
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
		case 'getImageList':
			$id =$_POST['id'];
			$sql1 = "SELECT * FROM tb_weekly_scene_notice_defect WHERE id='$id'";
			$result1 = $conn->query($sql1);
			if($result1->num_rows>0){
				$resData = array();
				$i=0;
				while($row1=$result1->fetch_assoc()){
					$resData["id"][$i] = $row1["id"];
					$resData["defectPicture"][$i] = $row1["defectPicture"];
//					$resData["pic_hf"][$i] = $row1["回复图片"];
					$resData["code"] = 1;
					$i++;
				}
			}else{
				$resData["code"] = 0;
			}
			echo json_encode($resData); 
			break;
	}
	
$conn->close();
?> 
<?php
require("../../../../conn.php");
error_reporting(E_ALL || ~E_NOTICE);
date_default_timezone_set('PRC');
	$flag = $_POST['flag'];

	switch($flag){
		case 'saveScorePic':
			$taskTimeStamp =$_POST['taskTimeStamp'];
			$fileCategory =$_POST['fileCategory'];
			$img_path='../../../images/app_pic/scorePic/';
	     	$pic_obj =$_POST['imageBaseList'];  //获取base64数据流
			$obj=json_decode($pic_obj,true);
			$img_name = time();
			
			$res = '{"status":0,"info":"请上传正确格式的图片"}';		
			$sql1 = "SELECT * FROM tb_quality_inspect_question_pic WHERE taskTimeStamp='$taskTimeStamp'";
			
			$result1 = $conn->query($sql1);
			if($result1->num_rows>0){
				while($row1=$result1->fetch_assoc()){
					if($fileCategory=='quality'){
						$pic = $row1["qualityPic"];
					}else if($fileCategory=='qualityReply'){
						$pic = $row1["qualityReplyPic"];
					}else if($fileCategory=='construct'){
						$pic = $row1["constructPic"];
					}else if($fileCategory=='constructReply'){
						$pic = $row1["constructReplyPic"];
					}else if($fileCategory=='file'){
						$pic = $row1["filePic"];
					}else if($fileCategory=='fileReply'){
						$pic = $row1["fileReplyPic"];
					}
					$a = explode("||",$pic);
					for($i=0;$i<count($a)-1;$i++){
						$lj = $img_path.$a[$i];
						if(file_exists($lj)){
							unlink($lj);
						}
					}
				}
			}else{
				$sql1 = "INSERT INTO tb_quality_inspect_question_pic SET taskTimeStamp = '$taskTimeStamp' ";
			
				$result1 = $conn->query($sql1);
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
			
			if($fileCategory=='quality'){
				$sql2 = "UPDATE `tb_quality_inspect_question_pic` SET `qualityPic`='$arr' WHERE taskTimeStamp = '$taskTimeStamp'";				
			}else if($fileCategory=='qualityReply'){
				$sql2 = "UPDATE `tb_quality_inspect_question_pic` SET `qualityReplyPic`='$arr' WHERE taskTimeStamp = '$taskTimeStamp'";
			}else if($fileCategory=='construct'){
				$sql2 = "UPDATE `tb_quality_inspect_question_pic` SET `constructPic`='$arr' WHERE taskTimeStamp = '$taskTimeStamp'";
			}else if($fileCategory=='constructReply'){
				$sql2 = "UPDATE `tb_quality_inspect_question_pic` SET `constructReplyPic`='$arr' WHERE taskTimeStamp = '$taskTimeStamp'";
			}else if($fileCategory=='file'){
				$sql2 = "UPDATE `tb_quality_inspect_question_pic` SET `filePic`='$arr' WHERE taskTimeStamp = '$taskTimeStamp'";
			}else if($fileCategory=='fileReply'){
				$sql2 = "UPDATE `tb_quality_inspect_question_pic` SET `fileReplyPic`='$arr' WHERE taskTimeStamp = '$taskTimeStamp'";
			}		
			$result2 = $conn->query($sql2);
			if($pic_obj!="{}"){
				foreach($obj as $key=>$val){
					$images_name = "";
					$images_name =  $img_name.$i.'.'.'jpg';
					
					$base64 = explode(",", $val);
					$data = base64_decode($base64[1]);
					file_put_contents($img_path.$images_name, $data);
					
					if($fileCategory=='quality'){
						$sql3 = "UPDATE `tb_quality_inspect_question_pic` SET `qualityPic`= CONCAT(`qualityPic`,'$images_name','||') WHERE taskTimeStamp = '$taskTimeStamp'";				
					}else if($fileCategory=='qualityReply'){
						$sql3 = "UPDATE `tb_quality_inspect_question_pic` SET `qualityReplyPic`= CONCAT(`qualityReplyPic`,'$images_name','||')  WHERE taskTimeStamp = '$taskTimeStamp'";
					}else if($fileCategory=='construct'){
						$sql3 = "UPDATE `tb_quality_inspect_question_pic` SET `constructPic`= CONCAT(`constructPic`,'$images_name','||')  WHERE taskTimeStamp = '$taskTimeStamp'";
					}else if($fileCategory=='constructReply'){
						$sql3 = "UPDATE `tb_quality_inspect_question_pic` SET `constructReplyPic`= CONCAT(`constructReplyPic`,'$images_name','||')  WHERE taskTimeStamp = '$taskTimeStamp'";
					}else if($fileCategory=='file'){
						$sql3 = "UPDATE `tb_quality_inspect_question_pic` SET `filePic`= CONCAT(`filePic`,'$images_name','||')  WHERE taskTimeStamp = '$taskTimeStamp'";
					}else if($fileCategory=='fileReply'){
						$sql3 = "UPDATE `tb_quality_inspect_question_pic` SET `fileReplyPic`= CONCAT(`fileReplyPic`,'$images_name','||')  WHERE taskTimeStamp = '$taskTimeStamp'";
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
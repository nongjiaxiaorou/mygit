<?php
require("../../../../conn.php");
error_reporting(E_ALL || ~E_NOTICE);
	$flag =$_POST['flag'];
	$describe =$_POST['describe'];
	switch($flag){
		case 'poured':
			$id =$_POST['id']; 
			$img_path='../../../images/app_pic/concrete_image/';
	      	$pic_obj =$_POST['imageBaseList'];  //获取base64数据流
			$obj=json_decode($pic_obj,true);
			$img_name = time();
			$res = '{"status":0,"info":"请上传正确格式的图片"}';
			$sql1 = "SELECT * FROM tb_concrete_pour  WHERE id='$id'";
			$result1 = $conn->query($sql1);
			if($result1->num_rows>0){
				while($row1=$result1->fetch_assoc()){
					$pic = $row1["poured_img"];
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
			$sql2 = "UPDATE  `tb_concrete_pour` SET `poured_img`='$arr' where id='$id'";
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
					$sql3 = "UPDATE `tb_concrete_pour` SET `poured_img` = CONCAT(`poured_img`,'$images_name','||') WHERE  id='$id'";
					$i++;
				    $result3 = $conn->query($sql3);
				    if($result3){
				    	$res = '{"status":1}';
				    }
				}
				//浇筑描述
				$sql4 = "UPDATE `tb_concrete_pour` SET `poured_describe` = '$describe' WHERE  id='$id'";
				$result4 = $conn->query($sql4);
			}
			echo $res;
			break;
			
			case 'pouring':
			$id =$_POST['id']; 
			$img_path='../../../images/app_pic/concrete_image/';
	      	$pic_obj =$_POST['imageBaseList'];  //获取base64数据流
			$obj=json_decode($pic_obj,true);
			$img_name = time();
			$res = '{"status":0,"info":"请上传正确格式的图片"}';
			$sql1 = "SELECT * FROM tb_concrete_pour  WHERE id='$id'";
			$result1 = $conn->query($sql1);
			if($result1->num_rows>0){
				while($row1=$result1->fetch_assoc()){
					$pic = $row1["pouring_img"];
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
			$sql2 = "UPDATE  `tb_concrete_pour` SET `pouring_img`='$arr' where id='$id'";
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
					$sql3 = "UPDATE `tb_concrete_pour` SET `pouring_img` = CONCAT(`pouring_img`,'$images_name','||') WHERE  id='$id'";
					$i++;
				    $result3 = $conn->query($sql3);
				    if($result3){
				    	$res = '{"status":1}';
				    }
				}
				//浇筑描述
				$sql4 = "UPDATE `tb_concrete_pour` SET `pouring_describe` = '$describe' WHERE  id='$id'";
				$result4 = $conn->query($sql4);
			}
			echo $res;
			break;
			
			case 'pour':
			$id =$_POST['id']; 
			$img_path='../../../images/app_pic/concrete_image/';
	      	$pic_obj =$_POST['imageBaseList'];  //获取base64数据流
			$obj=json_decode($pic_obj,true);
			$img_name = time();
			$res = '{"status":0,"info":"请上传正确格式的图片"}';
			$sql1 = "SELECT * FROM tb_concrete_pour  WHERE id='$id'";
			$result1 = $conn->query($sql1);
			if($result1->num_rows>0){
				while($row1=$result1->fetch_assoc()){
					$pic = $row1["pour_img"];
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
			$sql2 = "UPDATE  `tb_concrete_pour` SET `pour_img`='$arr' where id='$id'";
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
					$sql3 = "UPDATE `tb_concrete_pour` SET `pour_img` = CONCAT(`pour_img`,'$images_name','||') WHERE  id='$id'";
					$i++;
				    $result3 = $conn->query($sql3);
				    if($result3){
				    	$res = '{"status":1}';
				    }
				}
				//浇筑描述
				$sql4 = "UPDATE `tb_concrete_pour` SET `pour_describe` = '$describe' WHERE  id='$id'";
				$result4 = $conn->query($sql4);
			}
			echo $res;
			break;
			
			
			case 'removal':
			$id =$_POST['id']; 
			$img_path='../../../images/app_pic/concrete_image/';
	      	$pic_obj =$_POST['imageBaseList'];  //获取base64数据流
			$obj=json_decode($pic_obj,true);
			$img_name = time();
			$res = '{"status":0,"info":"请上传正确格式的图片"}';
			$sql1 = "SELECT * FROM tb_concrete_removal  WHERE id='$id'";
			$result1 = $conn->query($sql1);
			if($result1->num_rows>0){
				while($row1=$result1->fetch_assoc()){
					$pic = $row1["removal_img"];
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
			$sql2 = "UPDATE  `tb_concrete_removal` SET `removal_img`='$arr' where id='$id'";
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
					$sql3 = "UPDATE `tb_concrete_removal` SET `removal_img` = CONCAT(`removal_img`,'$images_name','||') WHERE  id='$id'";
					$i++;
				    $result3 = $conn->query($sql3);
				    if($result3){
				    	$res = '{"status":1}';
				    }
				}
				//拆模意见
				$sql4 = "UPDATE `tb_concrete_removal` SET `removal_describe` = '$describe' WHERE  id='$id'";
				$result4 = $conn->query($sql4);
				
				//完成拆模
				$sql5 = "UPDATE `tb_concrete_removal` SET `proState` = '完工' WHERE  id='$id'";
				$result5 = $conn->query($sql5);
			}
			echo $res;
			break;
	
	}
	
$conn->close();
?> 
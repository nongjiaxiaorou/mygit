<?php
	// require ('./conn.php');
	/*
	 * base64图片 在服务器生成图片 并返回路径 
	 */
	header("Access-Control-Allow-Origin: *");
	header('Content-type:text/html;charset=utf-8');
	$base64_image_content = $_POST['base64Pic'];
	//匹配出图片的格式
	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
		$type = $result[2];
		$dir = '../../../../src/images/app_pic/inspectPic/upload/' ;
		if(!file_exists($dir)) {
			mkdir($dir,0777,true);
		}
		$picName = uniqid(). ".{$type}";
		$new_file = $dir . $picName;
		if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
			$img = $picName;
			return $img;
		} else {
			return false;
		}
	} else {
		return false;
	}
?>
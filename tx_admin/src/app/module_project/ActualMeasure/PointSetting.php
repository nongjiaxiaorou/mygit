<?php
	require ("../../../../conn.php");
	error_reporting(E_ALL ^ E_NOTICE);
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	$pointStatus = isset($_POST["pointStatus"]) ? $_POST["pointStatus"] : '';
	$picSrcInit = isset($_POST["picSrcInit"]) ? $_POST["picSrcInit"] : '';
	$measureId = isset($_POST["measureId"]) ? $_POST["measureId"] : '';
	$small_height = isset($_POST["small_height"]) ? $_POST["small_height"] : '';
	$small_width = isset($_POST["small_width"]) ? $_POST["small_width"] : '';
	$phone_width = isset($_POST["phone_width"]) ? $_POST["phone_width"] : '';
	$set_height = isset($_POST["set_height"]) ? $_POST["set_height"] : '';
	date_default_timezone_set('PRC'); //东八时区 echo
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	//生成时间戳
	function msectime() {
   		list($msec, $sec) = explode(' ', microtime());
   		return  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
	}
	//删除文件  $path为绝对路径
	 function delFile($path){
		// chmod($path,0755);
		
		$url=iconv('utf-8','gbk',$path);
		if(PATH_SEPARATOR == ':'){ //linux
			unlink($path);
		}else{  //Windows
		
			unlink($url);
		}
		return TRUE;
	}


	switch($flag){
		case 'getPicSrc':
			$sql = "SELECT * from `tb_inspectaccept_measure` WHERE `id`= '$measureId'";
			$result = $conn->query($sql);
		    if($result)	{
				$row = $result -> fetch_assoc();
				if ($pointStatus == '初测') {
					$data['img'] = $row['primaryMeasurePIC'];
				} else {
					$data['img'] = $row['measurePointPic'];
				}
		    }else {
				$data['code'] = 0;				
			}
		break;
		case 'getpointNum':
			
			if ($pointStatus == '初测') {
				$sql0 = "SELECT * FROM `tb_measure_measurepoint` WHERE `measureId`='$measureId' and `pointInitialStatus` = '不合格'";
			 } else {
				 $sql0 = "SELECT * FROM `tb_measure_measurepoint` WHERE `measureId`='$measureId' and `pointRetestStatus` = '不合格'";
			 }
			
		    if($result0 = $conn->query($sql0))	{
				$AllPointNum = $result0->num_rows;
				$sql0_1 = "SELECT * FROM `tb_measure_pointinfo` WHERE `measureId`='$measureId'";
				$result0_1 = $conn->query($sql0_1);
				$putPointNum = $result0_1->num_rows;
				$data['putPointNum'] = $putPointNum;
				$data['notPointNum'] = $AllPointNum - $putPointNum;
		    }else {
				$data['code'] = 0;				
			}
		break;
		case 'savePoint':
			$por_obj = isset($_POST["por_obj"]) ? $_POST["por_obj"] : '';
			$por_obj = json_decode($por_obj,true);
			
			foreach($por_obj as $key=>$val){
				$arr = explode("|", $key);
				$pointType = $arr[0];
				$number = $arr[1];
				$sqli = "SELECT * FROM `tb_measure_pointinfo` WHERE `number`='$number' AND measureId='$measureId'";
				$result = $conn->query($sqli);
				if($result->num_rows>0){
					$sql = "UPDATE `tb_measure_pointinfo` SET Xcoordinate='".$val[0]."',Ycoordinate='".$val[1]."',parXcoordinate='".$val[2]."',parYcoordinate='".$val[3]."',number='".$val[4]."' WHERE `measurePointType`='".$pointType."'";
					if ($conn->query($sql) === TRUE) {
						$over = "更新成功！";
					}else{
						$over = "第".($i+1)."条数据保存出错";
						break;
					}
				}else{
			      $judge = $val[4];
			      if($judge!=" "&&$judge!=null){
			      	$sql = "INSERT INTO `tb_measure_pointinfo` (measureId,measurePointType,Xcoordinate,Ycoordinate,parXcoordinate,parYcoordinate,number,status) VALUES ('$measureId','".$pointType."','".$val[0]."','".$val[1]."','".$val[2]."','".$val[3]."','".$val[4]."','save')";
					if ($conn->query($sql) === TRUE) {
						$over = "保存成功！";
					}else{
						$over = "第".($i+1)."条数据保存出错";
						break;
					}
			      }
				}
			}
		    if($result)	{
		    }else {
				$data['code'] = 0;
				$data['cod1e'] = $over;
				
			}
		break;
		// 撤销布点
		case 'clearPoint':
			$items = isset($_POST["items"]) ? $_POST["items"] : '';
			$items = json_decode($items);
			$Length = sizeof($items);
			$measureType = isset($_POST["measureType"]) ? $_POST["measureType"] : '';
			for ($i=0; $i<$Length; $i++) {
				$checked = $items[$i]->checked;
				if ($checked == true) {
					$number = $items[$i]->num;
					$data['number'] = $number;
					$sql = "DELETE FROM `tb_measure_pointinfo` WHERE `measureId` = '$measureId' AND `number` = '$number' AND `measurePointType` = '$measureType' ";
					$result = $conn->query($sql);		
					if($result)	{
					}else {
						$data['code'] = 0;				
					}
				}
			}
		break;
		 // 获取已保存的测点
		case 'getSavedPoint':
			// 删除旧图纸
			$sql = "SELECT * from `tb_inspectaccept_measure` WHERE `id`= '$measureId'";
			$data['$sql111'] = $sql;
			$result = $conn->query($sql);
			if($result)	{
				$row = $result -> fetch_assoc();
				if ($pointStatus == '初测') {
					$data['img'] = $row['primaryMeasurePIC'];
					if ($row['primaryMeasurePIC'] != '') {
						$path = '../../../images/app_pic/inspectPic/upload/'.$row['primaryMeasurePIC'];
						delFile($path);
					}
				} else {
					$data['img'] = $row['measurePointPic'];
					if ($row['measurePointPic'] != '') {
						$path = '../../../images/app_pic/inspectPic/upload/'.$row['measurePointPic'];
						delFile($path);
					}
				}
			}else { }
			
			// 获取图片信息
			$srcImageInfo=getimagesize($picSrcInit); 
			$imageWidth=$srcImageInfo[0];
			$imageHeight=$srcImageInfo[1];
			// $data['w'] = $imageWidth;
			// $data['h'] = $imageHeight;
			// 加载原图片
			if($mime = $info['mime']){
				$suffix = explode('/',$mime)[1]; // 图片格式
			}
			if ($suffix == 'jpeg' || 'jpg') {
				$srcImage = imagecreatefromjpeg($picSrcInit);
			} else if ($suffix == 'png') {
				$srcImage = imagecreatefrompng($picSrcInit);
			}
			// 创建画布
			$canvas = imagecreatetruecolor($imageWidth,$imageHeight);
			$white=imagecolorallocate($canvas,255,255,255);
			imagefill($canvas,0,0,$white);
			imagecopy($canvas,$srcImage,0,0,0,0,$imageWidth,$imageHeight);
			$sql = "SELECT * FROM `tb_measure_pointinfo` WHERE `measureId`='$measureId' GROUP BY `measurePointType`";
			$result = $conn->query($sql);
			
			if($result->num_rows>0){
				$i = 0; 
				while ($row = $result -> fetch_assoc()) {
					$measurePointType = $row['measurePointType'];
					$sql1 = "SELECT number FROM `tb_measure_standard` WHERE `inspectContent` =  '$measurePointType'";
					$result1 = $conn->query($sql1);
					$row1 = $result1 -> fetch_assoc();
					$number = $row1['number'];
					$resData['measurePointType'] = $measurePointType;
					$resData['number'] = $number;
					$sql2 = "SELECT * FROM `tb_measure_pointinfo` WHERE `measureId`='$measureId' AND `measurePointType` = '$measurePointType'";
					$resData['sql'] = $sql2;
					$result2 = $conn->query($sql2);
					$result2num = $result2 -> num_rows;
					$j = 0;
					$resData['sonNumber'] = []; // 清空很重要
					if ($result2 -> num_rows>0) {
						while ($row2 = $result2 -> fetch_assoc()) {
							$num= $row2['number'];
							$Xcoordinate = $row2["Xcoordinate"];
							$Ycoordinate = $row2["Ycoordinate"];
							$parXcoordinate = $row2["parXcoordinate"];
							$parYcoordinate = $row2["parYcoordinate"];
							$resDataSon['num'] = $num;
							$resDataSon['Xcoordinate'] = $Xcoordinate;
							$resDataSon['Ycoordinate'] = $Ycoordinate;
							$resDataSon['parXcoordinate'] = $parXcoordinate;
							$resDataSon['parYcoordinate'] = $parYcoordinate;
							$resData['sonNumber'][$j] = $resDataSon; 
							// 画点
							$savePointX = $parXcoordinate + ($Xcoordinate*$small_width/340) ;// 点的坐标
							$savePointY = $parYcoordinate + ($Ycoordinate*$small_height/240);
							$PointX = $savePointX*$imageWidth/$phone_width;
							$PointY = $savePointY*$imageHeight/$set_height;
							$red = imagecolorallocate($canvas, 255, 0, 0);
							imagefilledellipse ($canvas,$PointX ,$PointY ,5,5,$red);
							imagestring($canvas,3,$PointX ,$PointY ,$num,$red);
							$j++;
						}
					}
					// $resData['j'] = $j;
					$res[$i] = $resData;
					$i++;
				}
				// $data['i'] = $i;
				// $data['sql'] = $sql;
				$data['data'] = $res;
			}else {
				$data['code'] = 0;
			}
			// $data['$canvas'] = $red;
			if ($suffix == 'jpeg' || 'jpg') {
				header("content-type:image/jpeg");
				$imgTimeStamp = msectime().'.jpeg';
				 $img =  imagejpeg($canvas,'../../../images/app_pic/inspectPic/upload/'.$imgTimeStamp ,100);
			} else if ($suffix == 'png') {
				header("content-type:image/png");
				$imgTimeStamp = msectime().'.png';
				 $img =  imagepng($canvas,'../../../images/app_pic/inspectPic/upload/'.$imgTimeStamp ,100);
			}
			 $data['$img'] = $img;
			 $data['$imgTimeStamp'] = $imgTimeStamp;
			if ($pointStatus == '初测') {
				$sqli = "UPDATE `tb_inspectaccept_measure` SET `primaryMeasurePic`= '$imgTimeStamp'  WHERE `id`= '$measureId' ";
				$data['sql1234'] = $sqli;
				$resulti = $conn->query($sqli);	
			} else {
				$sqli = "UPDATE `tb_inspectaccept_measure` SET `measurePointPic`= '$imgTimeStamp' WHERE `id`= '$measureId' ";
				$resulti = $conn->query($sqli);	
			}
			imagedestroy($canvas);
		break;
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	
	

?>
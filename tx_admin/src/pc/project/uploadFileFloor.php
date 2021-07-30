<?php
	require("../../../conn.php");
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
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
$extension=strtolower($extension1); // 把字符串转换为小写


function numToWord($num){	
		$chnNumChar = array('零', '一', '二', '三', '四', '五', '六', '七', '八', '九');
		$chiUni = array('','十', '百', '千', '万', '亿', '十', '百', '千');
		$chnUnitChar = array('', '十', '百', '千');
		$strIns = '';
		$chnStr = '';
		$unitPos = 0;
		$zero = true;
		while($num > 0) {
			$v = $num % 10;
			if($v === 0) {
				if(!$zero) {
					$zero = true;
					$chnStr = $chnNumChar[$v] + $chnStr;
					echo $chnStr;
				}
			} else {
				$zero = false;
				$strIns = $chnNumChar[$v];
				$strIns .= $chnUnitChar[$unitPos];
				$chnStr = $strIns . $chnStr;
			}
			$unitPos++;
			$num = floor($num / 10);
		}
		return $chnStr;
	
	}
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
			$a="../../images/pc_pic/floorPic/";
		    $dst_file=$sjc1.'.'.$extension1;
		    $dst_file = $a.iconv('utf-8', 'gbk', $dst_file);
			// 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
			move_uploaded_file($_FILES['file']['tmp_name'], $dst_file);
//			echo "文件存储在: " . "upload/" . $_FILES["file"]["name"];
			$picName= $sjc1.'.'.$extension1;
			switch($flag){
				case 'saveUploadPic':
					$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';
					$projectName = isset($_POST["projectName"]) ? $_POST["projectName"] : '';
					$floorData = isset($_POST["floorData"]) ? $_POST["floorData"] : '';
					$floorArr = json_decode($floorData,true);
					$i = 0;
					$picNum = count($floorArr);
					while($i<$picNum){  
						$build = $floorArr[$i]["build"];
						$section = $floorArr[$i]["section"];
						$floor = $floorArr[$i]["floor"];
						$sql1="SELECT * FROM tb_project_floor_pic WHERE proTimeStamp='$proTimeStamp'  AND build='$build' AND section='$section' and floor='$floor'";
						$result1 = $conn->query($sql1);
						$count1 = mysqli_num_rows($result1);// 函数返回结果集中行的数量
						if($count1){
							$sql2="UPDATE tb_project_floor_pic SET picName='$picName' WHERE projectName='$projectName'  AND build='$build' AND section='$section' and floor='$floor'";
							$result2 = $conn->query($sql2);
	//						echo $sql2;
						}else{
							$sql = "INSERT INTO tb_project_floor_pic SET proTimeStamp='$proTimeStamp',projectName='$projectName',build='$build',floor='$floor',picName='$picName',section='$section'";
							if($conn->query($sql) === TRUE){
								echo "保存成功";
					    	}
					 	} 
					   $i++;
					}
				break;
				case 'saveBuildUploadPic':
					$sjc= $_POST["sjc"];
					$i = 0;
					$dh_all= $_POST["dh_all"];
					$aa=explode("||",$dh_all);
					$dhsl = count($aa)-1;
					while($i<$dhsl){  
						$sql1="SELECT * FROM 工程楼层图纸 WHERE 工程名称='$gcmc'  AND 栋号='$aa[$i]' AND 区段='$qd'";
						$result1 = $conn->query($sql1);
						$count1 = mysqli_num_rows($result1);// 函数返回结果集中行的数量
						if($count1){
							$sql2="DELETE FROM `工程楼层图纸` WHERE 工程名称 = '$gcmc' AND 栋号 = '$aa[$i]' AND 区段 = '$qd'";
							$result2 = $conn->query($sql2);
						}
						$sql3="SELECT `地下层数`,`地上层数` FROM 工程楼层信息 WHERE 工程名称='$gcmc'  AND 栋号='$aa[$i]' AND 区段='$qd'";
						$result3 = $conn->query($sql3);
						$row3 = $result3->fetch_assoc();
						$under_floor = $row3["地下层数"];
						$on_floor = $row3["地上层数"];
						$under = array();
						$on = array();
						for($j=0;$j<$under_floor;$j++){
							$under[$j] = "负".numToWord($j+1)."层";
							$sql4="INSERT INTO 工程楼层图纸 (工程时间戳,工程名称,区段,栋号,层数,图纸) VALUES ('$sjc','$gcmc','$qd','$aa[$i]','$under[$j]','$tz')";
							$result4 = $conn->query($sql4);
						}
						for($j=0;$j<$on_floor;$j++){
							$on[$j] = numToWord($j+1)."层";
							$sql4="INSERT INTO 工程楼层图纸 (工程时间戳,工程名称,区段,栋号,层数,图纸) VALUES ('$sjc','$gcmc','$qd','$aa[$i]','$on[$j]','$tz')";
							$result4 = $conn->query($sql4);
						}
					   $i++;
					}
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
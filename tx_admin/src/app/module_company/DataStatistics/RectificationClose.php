<?php
	require ("../../../../conn.php");
	error_reporting(E_ALL ^ E_NOTICE);
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
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
	
	switch($flag){
		//获取整改闭合数据
		case 'getRectificationAllData':
			$startTime = isset($_POST["startTime"]) ? $_POST["startTime"] : '';
			$endTime = isset($_POST["endTime"]) ? $_POST["endTime"] : '';
			$companyLevel = isset($_POST["companyLevel"]) ? $_POST["companyLevel"] : '';
			// $qsrq= $_POST["qsrq"];
			// $jzrq= $_POST["jzrq"];
			if($startTime==''&&$endTime==''){
				$str1 = "";
			}else{
				$str1 = "AND `rectificationDate` >='$startTime' AND `rectificationDate` <='$endTime'";
			}
			if($companyLevel=='总公司'){
				// $gs= $_POST["gs"];
				// $xm= $_POST["xm"];
				// if($gs==''){
				if($startTime==''&&$endTime==''){
					$str2 = "";
				}else{
					$str2 = "WHERE `rectificationDate` >='$startTime' AND `rectificationDate` <='$endTime'";
				}
				$sql = "SELECT company,id  FROM `view_rectificationclose` ".$str2." GROUP BY company";
				// }else{
				// 	$sql = "SELECT DISTINCT  所属公司  FROM `整改闭合率` WHERE `所属公司` = '$gs' AND ".$str1." ";
				// }   
			//	echo $sql;
				$result = $conn->query($sql);
				if($result){
					$res = array();
					$resData = array();
					$i=0;
					while($row=$result->fetch_assoc()){
						$companyName= $row['company'];
			//			echo $gsmc;
						//获取该公司项目总数
						$sql1 = "SELECT Count(id) as num  FROM `tb_project` WHERE `company` = '$companyName' ";
						$result1 = $conn -> query($sql1);
						$row1 = $result1 -> fetch_assoc();
			//			echo $row1['num'];
							
						//整改闭合率
						$sql2 = "SELECT count(`projectId`) AS sum FROM `view_rectificationclose` WHERE `state` = '已完成' and `company` = '$companyName' ".$str1." ";
						$result2 = $conn -> query($sql2);
						$row2 = $result2 -> fetch_assoc();
						//累计整改数
						$sql3 = "SELECT count(`projectId`) AS sum1 FROM `view_rectificationclose` where `company` = '$companyName'".$str1." ";
						$result3 = $conn -> query($sql3);
						$row3 = $result3 -> fetch_assoc();
						if($row2["sum"]=='0'){
							$parcent='0';
						}else{
							$parcent = round($row2["sum"] / $row3["sum1"] * 100);
						}
						
						$result3 = $conn -> query($sql3);
						$row3 = $result3 -> fetch_assoc();
						$resData['id'] = $row['id'];
						$resData['title'] = $row['company'];
						$resData['projectNum'] = $row['num'];
						$resData['rectificationNum'] = $row3['sum1'];
						$resData['unRectificationNum'] = $row2['sum'];
						$resData['rectificationCloseRate'] = $parcent;
						$res[$i] = $resData;
						$i++;
					}
					$data['data'] = $res;
				}
				
			}else{
				$sql = "SELECT projectName,id  FROM `view_rectificationclose` where company = '$companyLevel'".$str1." GROUP BY projectName";
				$result = $conn->query($sql);
				if($result){
					$res = array();
					$resData = array();
					$i=0;
					while($row=$result->fetch_assoc()){
						$projectName= $row['projectName'];
						
						//整改闭合率
						$sql2 = "SELECT count(`projectId`) AS sum FROM `view_rectificationclose` WHERE `state` = '已完成' and `projectName` = '$projectName' ".$str1." ";
						$result2 = $conn -> query($sql2);
						$row2 = $result2 -> fetch_assoc();
						//累计整改数
						$sql3 = "SELECT count(`projectId`) AS sum1 FROM `view_rectificationclose` where `projectName` = '$projectName' ".$str1."  ";
						$result3 = $conn -> query($sql3);
						$row3 = $result3 -> fetch_assoc();
						if($row2["sum"]=='0'){
							$parcent='0';
						}else{
							$parcent = round($row2["sum"] / $row3["sum1"] * 100);
						}
					
						$result3 = $conn -> query($sql3);
						$row3 = $result3 -> fetch_assoc();
						$resData['id'] = $row['id'];
						$resData['title'] = $row['projectName'];
						$resData['rectificationNum'] = $row3['sum1'];
						$resData['unRectificationNum'] = $row2['sum'];
						$resData['rectificationCloseRate'] = $parcent;
						$res[$i] = $resData;
						$i++;
					}
					$data['data'] = $res;
				}
				
			}
		break;
		case 'getRectificationAllDataSel':
			$startTime = isset($_POST["startTime"]) ? $_POST["startTime"] : '';
			$endTime = isset($_POST["endTime"]) ? $_POST["endTime"] : '';
			$companyLevel = isset($_POST["companyLevel"]) ? $_POST["companyLevel"] : '';
			// $qsrq= $_POST["qsrq"];
			// $jzrq= $_POST["jzrq"];
			if($startTime==''&&$endTime==''){
				$str1 = "";
			}else{
				$str1 = "AND `rectificationDate` >='$startTime' AND `rectificationDate` <='$endTime'";
			}
			if($companyLevel=='总公司'){
				// $gs= $_POST["gs"];
				// $xm= $_POST["xm"];
				// if($gs==''){
				$str2 = substr($str1,3);
				$sql = "SELECT company,id  FROM `view_rectificationclose` WHERE ".$str2." GROUP BY company";
				// }else{
				// 	$sql = "SELECT DISTINCT  所属公司  FROM `整改闭合率` WHERE `所属公司` = '$gs' AND ".$str1." ";
				// }   
			//	echo $sql;
				$result = $conn->query($sql);
				if($result){
					$res = array();
					$resData = array();
					$i=0;
					while($row=$result->fetch_assoc()){
						$companyName= $row['company'];
			//			echo $gsmc;
						//获取该公司项目总数
						$sql1 = "SELECT Count(id) as num  FROM `tb_project` WHERE `company` = '$companyName' ";
						$result1 = $conn -> query($sql1);
						$row1 = $result1 -> fetch_assoc();
			//			echo $row1['num'];
							
						//整改闭合率
						$sql2 = "SELECT count(`projectId`) AS sum FROM `view_rectificationclose` WHERE `state` = '已完成' and `company` = '$companyName' ".$str1." ";
						$result2 = $conn -> query($sql2);
						$row2 = $result2 -> fetch_assoc();
						//累计整改数
						$sql3 = "SELECT count(`projectIdid`) AS sum1 FROM `view_rectificationclose` where `company` = '$companyName'".$str1." ";
						$result3 = $conn -> query($sql3);
						$row3 = $result3 -> fetch_assoc();
						if($row2["sum"]=='0'){
							$parcent='0';
						}else{
							$parcent = round($row2["sum"] / $row3["sum1"] * 100);
						}
						
						$result3 = $conn -> query($sql3);
						$row3 = $result3 -> fetch_assoc();
						$resData['id'] = $row['id'];
						$resData['title'] = $row['company'];
						$resData['projectNum'] = $row['num'];
						$resData['rectificationNum'] = $row3['sum1'];
						$resData['unRectificationNum'] = $row2['sum'];
						$resData['rectificationCloseRate'] = $parcent;
						$res[$i] = $resData;
						$i++;
					}
					$data['data'] = $res;
				}
				
			}else{
				$sql = "SELECT projectName,id  FROM `view_rectificationclose` where company = '$companyLevel'".$str1." GROUP BY projectName";
				$result = $conn->query($sql);
				if($result){
					$res = array();
					$resData = array();
					$i=0;
					while($row=$result->fetch_assoc()){
						$projectName= $row['projectName'];
						
						//整改闭合率
						$sql2 = "SELECT count(`projectId`) AS sum FROM `view_rectificationclose` WHERE `state` = '已完成' and `projectName` = '$projectName' ".$str1." ";
						$result2 = $conn -> query($sql2);
						$row2 = $result2 -> fetch_assoc();
						//累计整改数
						$sql3 = "SELECT count(`projectId`) AS sum1 FROM `view_rectificationclose` where `projectName` = '$projectName' ".$str1."  ";
						$result3 = $conn -> query($sql3);
						$row3 = $result3 -> fetch_assoc();
						if($row2["sum"]=='0'){
							$parcent='0';
						}else{
							$parcent = round($row2["sum"] / $row3["sum1"] * 100);
						}
					
						$result3 = $conn -> query($sql3);
						$row3 = $result3 -> fetch_assoc();
						$resData['id'] = $row['id'];
						$resData['title'] = $row['projectName'];
						$resData['rectificationNum'] = $row3['sum1'];
						$resData['unRectificationNum'] = $row2['sum'];
						$resData['rectificationCloseRate'] = $parcent;
						$res[$i] = $resData;
						$i++;
					}
					$data['data'] = $res;
				}else{
					$data['code'] = 0;
				}
				
			}
		break;
		case 'getCompany':
			$companyLevel = isset($_POST["companyLevel"]) ? $_POST["companyLevel"] : '';
			if($companyLevel=='总公司'){
				$sql = "SELECT companyName,id FROM `tb_system_company`";
				$result = $conn->query($sql);
				if($result){
					$res = array();
					$resData = array();
					$i=0;
					while($row=$result->fetch_assoc()){
						$resData['id'] = $row['id'];
						$resData['companyName'] = $row['companyName'];
						$res[$i] = $resData;
						$i++;
					}
					$data['data'] = $res;
				}
			}else{
					$data['code'] = 0;
			}
		break;
		case 'getRectificationContent':
			$company = isset($_POST["company"]) ? $_POST["company"] : '';
			$sql = "SELECT projectName,id FROM `view_rectificationclose` WHERE `company` = '$company' GROUP BY projectName";
			$result = $conn->query($sql);
			if($result){
				$res = array();
				$resData = array();
				$i=0;
				while($row=$result->fetch_assoc()){
					$projectName= $row['projectName'];
					//整改闭合率
					$sql2 = "SELECT count(`projectId`) AS sum FROM `view_rectificationclose` WHERE `state` = '已完成' and `projectName` = '$projectName' ";
					$result2 = $conn -> query($sql2);
					$row2 = $result2 -> fetch_assoc();
					//累计整改数
					$sql3 = "SELECT count(`projectId`) AS sum1 FROM `view_rectificationclose` where `projectName` = '$projectName'";
					$result3 = $conn -> query($sql3);
					$row3 = $result3 -> fetch_assoc();
					if($row2["sum"]=='0'){
						$parcent='0';
					}else{
						$parcent = round($row2["sum"] / $row3["sum1"] * 100);
					}
				
					$result3 = $conn -> query($sql3);
					$row3 = $result3 -> fetch_assoc();
					
					$resData['id'] = $row['id'];
					$resData['title'] = $row['projectName'];
					$resData['rectificationNum'] = $row3['sum1'];
					$resData['unRectificationNum'] = $row2['sum'];
					$resData['rectificationCloseRate'] = $parcent;
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}
		break;
		case 'getRectificationSelDate':
			$company = isset($_POST["company"]) ? $_POST["company"] : '';
			$startTime = isset($_POST["startTime"]) ? $_POST["startTime"] : '';
			$endTime = isset($_POST["endTime"]) ? $_POST["endTime"] : '';
			if($startTime==''&&$endTime==''){
				$str1 = "";
			}else{
				$str1 = "AND `rectificationDate` >='$startTime' AND `rectificationDate` <='$endTime'";
			}
			$sql = "SELECT projectName,id FROM `view_rectificationclose` WHERE `company` = '$company' ".$str1." GROUP BY projectName";
			$result = $conn->query($sql);
			if($result){
				$res = array();
				$resData = array();
				$i=0;
				while($row=$result->fetch_assoc()){
					$projectName= $row['projectName'];
					//整改闭合率
					$sql2 = "SELECT count(`projectId`) AS sum FROM `view_rectificationclose` WHERE `state` = '已完成' and `projectName` = '$projectName' ".$str1." ";
					$result2 = $conn -> query($sql2);
					$row2 = $result2 -> fetch_assoc();
					//累计整改数
					$sql3 = "SELECT count(`projectId`) AS sum1 FROM `view_rectificationclose` where `projectName` = '$projectName' ".$str1." ";
					$result3 = $conn -> query($sql3);
					$row3 = $result3 -> fetch_assoc();
					if($row2["sum"]=='0'){
						$parcent='0';
					}else{
						$parcent = round($row2["sum"] / $row3["sum1"] * 100);
					}
				
					$result3 = $conn -> query($sql3);
					$row3 = $result3 -> fetch_assoc();
					
					$resData['id'] = $row['id'];
					$resData['title'] = $row['projectName'];
					$resData['rectificationNum'] = $row3['sum1'];
					$resData['unRectificationNum'] = $row2['sum'];
					$resData['rectificationCloseRate'] = $parcent;
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}
		break;
		case 'getProject':
			$company = isset($_POST["company"]) ? $_POST["company"] : '';
			$sql = "SELECT projectName,id FROM `tb_project` WHERE company='$company'";
			$result = $conn->query($sql);
			if($result){
				$res = array();
				$resData = array();
				$i=0;
				while($row=$result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['projectName'] = $row['projectName'];
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
		break;
		//获取重大隐患
		case 'getMajorHiddenDangerAllData':
			$startTime = isset($_POST["startTime"]) ? $_POST["startTime"] : '';
			$endTime = isset($_POST["endTime"]) ? $_POST["endTime"] : '';
			$companyLevel = isset($_POST["companyLevel"]) ? $_POST["companyLevel"] : '';
			$tomorrow = date('Y-m-d', strtotime('+1 days'));
			// $qsrq= $_POST["qsrq"];
			// $jzrq= $_POST["jzrq"];
			if($startTime==''&&$endTime==''){
				$str1 = "";
			}else{
				$str1 = "AND `rectificationDate` >='$startTime' AND `rectificationDate` <='$endTime'";
			}
			if($companyLevel=='总公司'){
				// $gs= $_POST["gs"];
				// $xm= $_POST["xm"];
				// if($gs==''){
				if($startTime==''&&$endTime==''){
					$str2 = "";
				}else{
					$str2 = "WHERE `rectificationDate` >='$startTime' AND `rectificationDate` <='$endTime'";
				}
				$sql = "SELECT company,id  FROM `view_hiddendanger` ".$str2." GROUP BY company";
				// }else{
				// 	$sql = "SELECT DISTINCT  所属公司  FROM `整改闭合率` WHERE `所属公司` = '$gs' AND ".$str1." ";
				// }   
			//	echo $sql;
				$result = $conn->query($sql);
				if($result){
					$res = array();
					$resData = array();
					$i=0;
					while($row=$result->fetch_assoc()){
						$companyName= $row['company'];
			//			echo $gsmc;
						//获取该公司项目总数
						$sql1 = "SELECT Count(id) as num  FROM `tb_project` WHERE `company` = '$companyName' ";
						$result1 = $conn -> query($sql1);
						$row1 = $result1 -> fetch_assoc();
			//			echo $row1['num'];
							
						//整改闭合率
						$sql2 = "SELECT count(`projectId`) AS sum FROM `view_hiddendanger` WHERE `state` = '已完成' AND `company` = '$companyName' AND itemType = '重大问题' ".$str1." ";
						$result2 = $conn -> query($sql2);
						$row2 = $result2 -> fetch_assoc();
						//累计整改数
						$sql3 = "SELECT count(`projectId`) AS sum1 FROM `view_hiddendanger` where `company` = '$companyName' AND itemType = '重大问题' ".$str1." ";
						$result3 = $conn -> query($sql3);
						$row3 = $result3 -> fetch_assoc();
						if($row2["sum"]=='0'){
							$parcent='0';
						}else{
							$parcent = round($row2["sum"] / $row3["sum1"] * 100);
						}
						//当日数
						$sql4 = "SELECT count(`projectId`) AS sum2 FROM `view_hiddendanger` WHERE rectificationDate >='$today' and rectificationDate<'$tomorrow' and itemType = '重大问题'  ";
						$result4 = $conn -> query($sql4);
						$row4 = $result4 -> fetch_assoc();
						
						$result3 = $conn -> query($sql3);
						$row3 = $result3 -> fetch_assoc();
						$resData['id'] = $row['id'];
						$resData['title'] = $row['company'];
						$resData['projectNum'] = $row['num'];
						$resData['rectificationNum'] = $row4['sum2'];
						$resData['totalNum'] = $row3['sum1'];
						$resData['rectificationCloseRate'] = $parcent;
						$res[$i] = $resData;
						$i++;
					}
					$data['data'] = $res;
				}
				
			}else{
				$sql = "SELECT projectName,id  FROM `view_hiddendanger` where company = '$companyLevel'".$str1." GROUP BY projectName";
				$result = $conn->query($sql);
				if($result){
					$res = array();
					$resData = array();
					$i=0;
					while($row=$result->fetch_assoc()){
						$projectName= $row['projectName'];
						
						//整改闭合率
						$sql2 = "SELECT count(`projectId`) AS sum FROM `view_hiddendanger` WHERE `state` = '已完成' AND itemType = '重大问题' AND `projectName` = '$projectName' ".$str1." ";
						$result2 = $conn -> query($sql2);
						$row2 = $result2 -> fetch_assoc();
						//累计整改数
						$sql3 = "SELECT count(`projectId`) AS sum1 FROM `view_hiddendanger` WHERE `projectName` = '$projectName' AND itemType = '重大问题' ".$str1."  ";
						$result3 = $conn -> query($sql3);
						$row3 = $result3 -> fetch_assoc();
						if($row2["sum"]=='0'){
							$parcent='0';
						}else{
							$parcent = round($row2["sum"] / $row3["sum1"] * 100);
						}
					
						$result3 = $conn -> query($sql3);
						$row3 = $result3 -> fetch_assoc();
						//当日数
						$sql4 = "SELECT count(`projectId`) AS sum2 FROM `view_hiddendanger` WHERE rectificationDate >='$today' and rectificationDate<'$tomorrow' and itemType = '重大问题'  ";
						$result4 = $conn -> query($sql4);
						$row4 = $result4 -> fetch_assoc();
						
						$resData['id'] = $row['id'];
						$resData['title'] = $row['projectName'];
						$resData['projectNum'] = $row['num'];
						$resData['rectificationNum'] = $row4['sum2'];
						$resData['totalNum'] = $row3['sum1'];
						$resData['rectificationCloseRate'] = $parcent;
						$i++;
					}
					$data['data'] = $res;
				}
				
			}
		break;
		case 'getMajorHiddenDangerContent':
			$company = isset($_POST["company"]) ? $_POST["company"] : '';
			$sql = "SELECT projectName,id FROM `view_hiddendanger` WHERE `company` = '$company' GROUP BY projectName";
			$result = $conn->query($sql);
			if($result){
				$res = array();
				$resData = array();
				$i=0;
				while($row=$result->fetch_assoc()){
					$projectName= $row['projectName'];
					//整改闭合率
					$sql2 = "SELECT count(`projectId`) AS sum FROM `view_hiddendanger` WHERE `state` = '已完成' AND itemType = '重大问题' AND `projectName` = '$projectName' ";
					$result2 = $conn -> query($sql2);
					$row2 = $result2 -> fetch_assoc();
					//累计整改数
					$sql3 = "SELECT count(`projectId`) AS sum1 FROM `view_hiddendanger` WHERE `projectName` = '$projectName' AND itemType = '重大问题'";
					$result3 = $conn -> query($sql3);
					$row3 = $result3 -> fetch_assoc();
					if($row2["sum"]=='0'){
						$parcent='0';
					}else{
						$parcent = round($row2["sum"] / $row3["sum1"] * 100);
					}
					//当日数
					$sql4 = "SELECT count(`projectId`) AS sum2 FROM `view_hiddendanger` WHERE rectificationDate >='$today' and rectificationDate<'$tomorrow' and itemType = '重大问题'  ";
					$result4 = $conn -> query($sql4);
					$row4 = $result4 -> fetch_assoc();
					
					$resData['id'] = $row['id'];
					$resData['title'] = $row['projectName'];
					$resData['projectNum'] = $row['num'];
					$resData['rectificationNum'] = $row4['sum2'];
					$resData['totalNum'] = $row3['sum1'];
					$resData['rectificationCloseRate'] = $parcent;
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}
		break;
		case 'getMajorHiddenSelDate':
			$company = isset($_POST["company"]) ? $_POST["company"] : '';
			$startTime = isset($_POST["startTime"]) ? $_POST["startTime"] : '';
			$endTime = isset($_POST["endTime"]) ? $_POST["endTime"] : '';
			if($startTime==''&&$endTime==''){
				$str1 = "";
			}else{
				$str1 = "AND `rectificationDate` >='$startTime' AND `rectificationDate` <='$endTime'";
			}
			$sql = "SELECT projectName,id FROM `view_hiddendanger` WHERE `company` = '$company'".$str1." GROUP BY projectName";
			$result = $conn->query($sql);
			if($result){
				$res = array();
				$resData = array();
				$i=0;
				while($row=$result->fetch_assoc()){
					$projectName= $row['projectName'];
					//整改闭合率
					$sql2 = "SELECT count(`projectId`) AS sum FROM `view_hiddendanger` WHERE `state` = '已完成' AND itemType = '重大问题' AND `projectName` = '$projectName'".$str1." ";
					$result2 = $conn -> query($sql2);
					$row2 = $result2 -> fetch_assoc();
					//累计整改数
					$sql3 = "SELECT count(`projectId`) AS sum1 FROM `view_hiddendanger` WHERE `projectName` = '$projectName' AND itemType = '重大问题'".$str1." ";
					$result3 = $conn -> query($sql3);
					$row3 = $result3 -> fetch_assoc();
					if($row2["sum"]=='0'){
						$parcent='0';
					}else{
						$parcent = round($row2["sum"] / $row3["sum1"] * 100);
					}
					//当日数
					$sql4 = "SELECT count(`projectId`) AS sum2 FROM `view_hiddendanger` WHERE rectificationDate >='$today' and rectificationDate<'$tomorrow' and itemType = '重大问题'  ";
					$result4 = $conn -> query($sql4);
					$row4 = $result4 -> fetch_assoc();
					
					$resData['id'] = $row['id'];
					$resData['title'] = $row['projectName'];
					$resData['projectNum'] = $row['num'];
					$resData['rectificationNum'] = $row4['sum2'];
					$resData['totalNum'] = $row3['sum1'];
					$resData['rectificationCloseRate'] = $parcent;
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}
		break;
		//获取一般隐患
		case 'getCommonHiddenDangerAllData':
			$startTime = isset($_POST["startTime"]) ? $_POST["startTime"] : '';
			$endTime = isset($_POST["endTime"]) ? $_POST["endTime"] : '';
			$companyLevel = isset($_POST["companyLevel"]) ? $_POST["companyLevel"] : '';
			$tomorrow = date('Y-m-d', strtotime('+1 days'));
			// $qsrq= $_POST["qsrq"];
			// $jzrq= $_POST["jzrq"];
			if($startTime==''&&$endTime==''){
				$str1 = "";
			}else{
				$str1 = "AND `rectificationDate` >='$startTime' AND `rectificationDate` <='$endTime'";
			}
			if($companyLevel=='总公司'){
				// $gs= $_POST["gs"];
				// $xm= $_POST["xm"];
				// if($gs==''){
				if($startTime==''&&$endTime==''){
					$str2 = "";
				}else{
					$str2 = "WHERE `rectificationDate` >='$startTime' AND `rectificationDate` <='$endTime'";
				}
				$sql = "SELECT company,id  FROM `view_hiddendanger` ".$str2." GROUP BY company";
				// }else{
				// 	$sql = "SELECT DISTINCT  所属公司  FROM `整改闭合率` WHERE `所属公司` = '$gs' AND ".$str1." ";
				// }   
			//	echo $sql;
				$result = $conn->query($sql);
				if($result){
					$res = array();
					$resData = array();
					$i=0;
					while($row=$result->fetch_assoc()){
						$companyName= $row['company'];
			//			echo $gsmc;
						//获取该公司项目总数
						$sql1 = "SELECT Count(id) as num  FROM `tb_project` WHERE `company` = '$companyName' ";
						$result1 = $conn -> query($sql1);
						$row1 = $result1 -> fetch_assoc();
			//			echo $row1['num'];
							
						//整改闭合率
						$sql2 = "SELECT count(`projectId`) AS sum FROM `view_hiddendanger` WHERE `state` = '已完成' AND `company` = '$companyName' AND itemType = '一般问题' ".$str1." ";
						$result2 = $conn -> query($sql2);
						$row2 = $result2 -> fetch_assoc();
						//累计整改数
						$sql3 = "SELECT count(`projectId`) AS sum1 FROM `view_hiddendanger` where `company` = '$companyName' AND itemType = '一般问题' ".$str1." ";
						$result3 = $conn -> query($sql3);
						$row3 = $result3 -> fetch_assoc();
						if($row2["sum"]=='0'){
							$parcent='0';
						}else{
							$parcent = round($row2["sum"] / $row3["sum1"] * 100);
						}
						//当日数
						$sql4 = "SELECT count(`projectId`) AS sum2 FROM `view_hiddendanger` WHERE rectificationDate >='$today' and rectificationDate<'$tomorrow' and itemType = '一般问题'  ";
						$result4 = $conn -> query($sql4);
						$row4 = $result4 -> fetch_assoc();
						
						$result3 = $conn -> query($sql3);
						$row3 = $result3 -> fetch_assoc();
						$resData['id'] = $row['id'];
						$resData['title'] = $row['company'];
						$resData['projectNum'] = $row['num'];
						$resData['rectificationNum'] = $row4['sum2'];
						$resData['totalNum'] = $row3['sum1'];
						$resData['rectificationCloseRate'] = $parcent;
						$res[$i] = $resData;
						$i++;
					}
					$data['data'] = $res;
				}
				
			}else{
				$sql = "SELECT projectName,id  FROM `view_hiddendanger` where company = '$companyLevel'".$str1." GROUP BY projectName";
				$result = $conn->query($sql);
				if($result){
					$res = array();
					$resData = array();
					$i=0;
					while($row=$result->fetch_assoc()){
						$projectName= $row['projectName'];
						
						//整改闭合率
						$sql2 = "SELECT count(`projectId`) AS sum FROM `view_hiddendanger` WHERE `state` = '已完成' AND itemType = '一般问题' AND `projectName` = '$projectName' ".$str1." ";
						$result2 = $conn -> query($sql2);
						$row2 = $result2 -> fetch_assoc();
						//累计整改数
						$sql3 = "SELECT count(`projectId`) AS sum1 FROM `view_hiddendanger` WHERE `projectName` = '$projectName' AND itemType = '一般问题' ".$str1."  ";
						$result3 = $conn -> query($sql3);
						$row3 = $result3 -> fetch_assoc();
						if($row2["sum"]=='0'){
							$parcent='0';
						}else{
							$parcent = round($row2["sum"] / $row3["sum1"] * 100);
						}
					
						$result3 = $conn -> query($sql3);
						$row3 = $result3 -> fetch_assoc();
						//当日数
						$sql4 = "SELECT count(`projectId`) AS sum2 FROM `view_hiddendanger` WHERE rectificationDate >='$today' and rectificationDate<'$tomorrow' and itemType = '一般问题'  ";
						$result4 = $conn -> query($sql4);
						$row4 = $result4 -> fetch_assoc();
						
						$resData['id'] = $row['id'];
						$resData['title'] = $row['projectName'];
						$resData['projectNum'] = $row['num'];
						$resData['rectificationNum'] = $row4['sum2'];
						$resData['totalNum'] = $row3['sum1'];
						$resData['rectificationCloseRate'] = $parcent;
						$i++;
					}
					$data['data'] = $res;
				}
				
			}
		break;
		case 'getCommonHiddenDangerContent':
			$company = isset($_POST["company"]) ? $_POST["company"] : '';
			$sql = "SELECT projectName,id FROM `view_hiddendanger` WHERE `company` = '$company' GROUP BY projectName";
			$result = $conn->query($sql);
			if($result){
				$res = array();
				$resData = array();
				$i=0;
				while($row=$result->fetch_assoc()){
					$projectName= $row['projectName'];
					//整改闭合率
					$sql2 = "SELECT count(`projectId`) AS sum FROM `view_hiddendanger` WHERE `state` = '已完成' AND itemType = '一般问题' AND `projectName` = '$projectName' ";
					$result2 = $conn -> query($sql2);
					$row2 = $result2 -> fetch_assoc();
					//累计整改数
					$sql3 = "SELECT count(`projectId`) AS sum1 FROM `view_hiddendanger` WHERE `projectName` = '$projectName' AND itemType = '一般问题'";
					$result3 = $conn -> query($sql3);
					$row3 = $result3 -> fetch_assoc();
					if($row2["sum"]=='0'){
						$parcent='0';
					}else{
						$parcent = round($row2["sum"] / $row3["sum1"] * 100);
					}
					//当日数
					$sql4 = "SELECT count(`projectId`) AS sum2 FROM `view_hiddendanger` WHERE rectificationDate >='$today' and rectificationDate<'$tomorrow' and itemType = '一般问题'  ";
					$result4 = $conn -> query($sql4);
					$row4 = $result4 -> fetch_assoc();
					
					$resData['id'] = $row['id'];
					$resData['title'] = $row['projectName'];
					$resData['projectNum'] = $row['num'];
					$resData['rectificationNum'] = $row4['sum2'];
					$resData['totalNum'] = $row3['sum1'];
					$resData['rectificationCloseRate'] = $parcent;
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}
		break;
		case 'getCommonHiddenSelDate':
			$company = isset($_POST["company"]) ? $_POST["company"] : '';
			$startTime = isset($_POST["startTime"]) ? $_POST["startTime"] : '';
			$endTime = isset($_POST["endTime"]) ? $_POST["endTime"] : '';
			if($startTime==''&&$endTime==''){
				$str1 = "";
			}else{
				$str1 = "AND `rectificationDate` >='$startTime' AND `rectificationDate` <='$endTime'";
			}
			$sql = "SELECT projectName,id FROM `view_hiddendanger` WHERE `company` = '$company'".$str1." GROUP BY projectName";
			$result = $conn->query($sql);
			if($result){
				$res = array();
				$resData = array();
				$i=0;
				while($row=$result->fetch_assoc()){
					$projectName= $row['projectName'];
					//整改闭合率
					$sql2 = "SELECT count(`projectId`) AS sum FROM `view_hiddendanger` WHERE `state` = '已完成' AND itemType = '一般问题' AND `projectName` = '$projectName'".$str1." ";
					$result2 = $conn -> query($sql2);
					$row2 = $result2 -> fetch_assoc();
					//累计整改数
					$sql3 = "SELECT count(`projectId`) AS sum1 FROM `view_hiddendanger` WHERE `projectName` = '$projectName' AND itemType = '一般问题'".$str1." ";
					$result3 = $conn -> query($sql3);
					$row3 = $result3 -> fetch_assoc();
					if($row2["sum"]=='0'){
						$parcent='0';
					}else{
						$parcent = round($row2["sum"] / $row3["sum1"] * 100);
					}
					//当日数
					$sql4 = "SELECT count(`projectId`) AS sum2 FROM `view_hiddendanger` WHERE rectificationDate >='$today' and rectificationDate<'$tomorrow' and itemType = '一般问题'  ";
					$result4 = $conn -> query($sql4);
					$row4 = $result4 -> fetch_assoc();
					
					$resData['id'] = $row['id'];
					$resData['title'] = $row['projectName'];
					$resData['projectNum'] = $row['num'];
					$resData['rectificationNum'] = $row4['sum2'];
					$resData['totalNum'] = $row3['sum1'];
					$resData['rectificationCloseRate'] = $parcent;
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}
		break;
		
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	
	

?>
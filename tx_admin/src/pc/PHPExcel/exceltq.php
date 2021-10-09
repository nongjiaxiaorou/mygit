<?php
	require ('../../../conn.php');
	/*读取excel文件，并进行相应处理*/
	//	$fileName = "../uploadExcel/".'111'.".xls";
	error_reporting(E_ALL & ~E_NOTICE);
	$fileName = "../PHPExcel-1.8/" . $upload_file;
	$value = array();
	if (!file_exists($fileName)) {
		exit("文件" . $fileName . "不存在");
	}
	
	$startTime = time();
	//返回当前时间的Unix 时间戳
	require_once '../PHPExcel-1.8/Classes/PHPExcel/IOFactory.php';
	//引入PHPExcel文件库
	$objPHPExcel = PHPExcel_IOFactory::load($fileName);
	//获取sheet表格数目
	$sheetCount = $objPHPExcel -> getSheetCount();
	//默认选中sheet0表
	$sheetSelected = 0;
	$objPHPExcel -> setActiveSheetIndex($sheetSelected);
	//获取表格行数
	$rowCount = $objPHPExcel -> getActiveSheet() -> getHighestRow();
	//获取表格列数
	$columnCount = $objPHPExcel -> getActiveSheet() -> getHighestColumn();
	
		echo "<div>Sheet Count : ".$sheetCount."　　行数： ".$rowCount."　　列数：".$columnCount."</div>";
	$dataArr = array();
	/* 循环读取每个单元格的数据 */
	
	//行数循环
	$flag = $_POST["flag"];
	switch($flag) {
		//分包商表
		case "saveSubcontractor":
			$timeStamp = $_POST["timeStamp"];
			$createtime = date("Y-m-d H:i:s");
			for ($row = 2; $row <= $rowCount; $row++) {
				//列数循环 , 列数是以A列开始
				for ($column = 'A'; $column <= $columnCount; $column++) {
					$dataArr[] = $objPHPExcel -> getActiveSheet() -> getCell($column . $row) -> getValue();
					$array[$column . $row] = $objPHPExcel -> getActiveSheet() -> getCell($column . $row) -> getValue();
					//将表格的所有数据存成一个关联数组
					//echo $column.$row.":".$objPHPExcel->getActiveSheet()->getCell($column.$row)->getValue()."<br />";
				}
				//echo "<br/>消耗的内存为：".(memory_get_peak_usage(true) / 1024 / 1024)."M";
				$endTime = time();
				//echo "<div>解析完后，当前的时间为：".date("Y-m-d H:i:s")."总共消耗的时间为：".(($endTime - $startTime))."秒</div>";
				//var_dump($dataArr);
				$dataArr = NULL;
			}
			for ($i = 3; $i < "61"; $i++) {
				$fbsName = "B" . $i;
				$fbsName = $array[$fbsName];
				if ($fbsName == "") {
					break;
				} else {
					$sql = "INSERT INTO tb_project_subcontractor SET projectTimestamp='$timeStamp',subcontractor='$fbsName',createTime='$createtime'";
					$result = $conn -> query($sql);
				}
			}
			unlink($fileName);//删除文件
			break;
		//导入工程
		case 'importProject':
			for ($row = 2; $row <= $rowCount; $row++) {
				//列数循环 , 列数是以A列开始
				for ($column = 'A'; $column <= $columnCount; $column++) {
					$dataArr[] = $objPHPExcel -> getActiveSheet() -> getCell($column . $row) -> getValue();
					$array[$column . $row] = $objPHPExcel -> getActiveSheet() -> getCell($column . $row) -> getValue();
				}
			}
//			print_r($dataArr);
			for ($i = 2; $i <= $rowCount; $i++) {
				$projectName = "A" . $i;
				$projectName = $array[$projectName];
				$categories = "B" . $i;
				$categories = $array[$categories];
				$company = "C" . $i;
				$company = $array[$company];
				$province = "D" . $i;
				$province = $array[$province];
				$city = "E" . $i;
				$city = $array[$city];
				$proPosition = "F" . $i;
				$proPosition = $array[$proPosition];
				$architecture = "G" . $i;
				$architecture = $array[$architecture];
				$startTime = "H" . $i;
				$startTime = $array[$startTime];
				$completedTime = "I" . $i;
				$completedTime = $array[$completedTime];
				$startTime = date('Y/m/d',PHPExcel_Shared_Date::ExcelToPHP($startTime));
				$completedTime = date('Y/m/d',PHPExcel_Shared_Date::ExcelToPHP($completedTime));
				if ($projectName == "") {
					break;
				} else {
					$sql= "SELECT * FROM tb_system_company WHERE `companyName` = '$company' ";
					$result = $conn -> query($sql);
					$row = $result->fetch_assoc();
					$sql = "INSERT INTO tb_project SET projectName='$projectName',categories='$categories',companyId='".$row['id']."',company='$company',province='$province',city='$city',proPosition='$proPosition',architecture='$architecture',startTime='$startTime',completedTime='$completedTime'";
					$result = $conn -> query($sql);
				}
			}
			unlink($fileName);//删除文件
			break;

			//导入实测实量信息
			case 'MeasuringPoint':
				for ($row = 2; $row <= $rowCount; $row++) {
					//列数循环 , 列数是以A列开始
					for ($column = 'A'; $column <= $columnCount; $column++) {
						$dataArr[] = $objPHPExcel -> getActiveSheet() -> getCell($column . $row) -> getValue();
						$array[$column . $row] = $objPHPExcel -> getActiveSheet() -> getCell($column . $row) -> getValue();
					}
				}
				$id = isset($_POST["id"])?$_POST["id"] : '';
				// print_r($dataArr);
				for ($i = 2; $i <= $rowCount; $i++) {
					$measurePointName = "A" . $i;
					$measurePointName = $array[$measurePointName];
					$measurePointNumber = "B" . $i;
					$measurePointNumber = $array[$measurePointNumber];
					$pointInitialValue = "C" . $i;
					$pointInitialValue = $array[$pointInitialValue];
					$pointInitialStatus = "D" . $i;
					$pointInitialStatus = $array[$pointInitialStatus];
					$pointRetestValue = "E" . $i;
					$pointRetestValue = $array[$pointRetestValue];
					$pointRetestStatus = "F" . $i;
					$pointRetestStatus = $array[$pointRetestStatus];
					$pointFinaltestValue = "G" . $i;
					$pointFinaltestValue = $array[$pointFinaltestValue];
					$finaltestStatus = "H" . $i;
					$finaltestStatus = $array[$finaltestStatus];
					
					if ($measurePointName == "") {
						break;
					} else {						
						$sql = "INSERT INTO tb_measure_measurepoint (measurePointName,measurePointNumber,pointInitialValue,pointInitialStatus,pointRetestValue,pointRetestStatus,pointFinaltestValue,finaltestStatus,measureId,status) VALUES ('$measurePointName','$measurePointNumber','$pointInitialValue','$pointInitialStatus','$pointRetestValue','$pointRetestStatus','$pointFinaltestValue','$finaltestStatus','$id','终测')";
						$result = $conn -> query($sql);
					}
				}print_r($measurePointName);
				unlink($fileName);//删除文件
	}
?>
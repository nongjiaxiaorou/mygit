<?php
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	require ("../../../conn.php");
	error_reporting(0);
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag) {
		//获取检查项目定义
		case 'getDefineData':
			$taskId = isset($_POST["taskId"]) ? $_POST["taskId"] : '';
			
			$sql = "SELECT * FROM tb_quality_inspect_items WHERE taskId = '$taskId' ORDER BY tb_quality_inspect_items.id ASC";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$ret_data["data"][$i]['id'] = $row["id"];
					$ret_data["data"][$i]["inspectQues"] = $row["inspectQues"];
					$ret_data["data"][$i]["fullMarks"] = $row["fullMarks"];
					$ret_data["data"][$i]["inspectCate"] = $row["inspectCate"];
					$ret_data["data"][$i]["deductTableCate"] = $row["deductTableCate"];
					
					$i++;
					$ret_data["states"] = 'success';
				}
			}else{
				$ret_data['data'] = [];
				$ret_data["states"] = 'error';
			}
		break;
		//删除检查项目
		case 'deleteInsItem':
			$id = isset($_POST["id"]) ? $_POST["id"] : '';
			
			$sql = "SELECT * FROM tb_quality_inspect_items WHERE id = '$id' ";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$sql = "DELETE from tb_quality_inspect_items WHERE id = '$id' ";
				$result = $conn -> query($sql);
			}else{
				$ret_data['data'] = [];
				$ret_data["states"] = 'error';
			}
		break;
	}
	$conn -> close();
	$json = json_encode($ret_data);
	echo $json;
?>
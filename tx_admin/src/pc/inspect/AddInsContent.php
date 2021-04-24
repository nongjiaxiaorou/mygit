<?php
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	require ("../../../conn.php");
	error_reporting(0);
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	
	switch($flag) {
		//异步获取扣分表大类
		case 'getTableCate':
			
			$sql = "SELECT DISTINCT a.deductTableCate, a.weight FROM tb_quality_summary_table AS a GROUP BY a.deductTableCate ORDER BY a.id ASC";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$ret_data["data"][$i]['deductTableCate'] = $row["deductTableCate"];
					$ret_data["data"][$i]["weight"] = $row["weight"];
					
					$i++;
					$ret_data["states"] = 'success';
				}
			}else{
				$ret_data['data'] = [];
				$ret_data["states"] = 'error';
			}
		break;
		//根据扣分表大类获取巡查类型
		case 'getInspectCate':
			$deductTableCate = isset($_POST["deductTableCate"])?$_POST["deductTableCate"]:'';
			
			$sql = "SELECT DISTINCT a.itemsCate FROM tb_quality_summary_table AS a WHERE a.deductTableCate = '$deductTableCate' GROUP BY a.itemsCate ORDER BY a.id ASC";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$ret_data["data"][$i]['itemsCate'] = $row["itemsCate"];
					
					$i++;
					$ret_data["states"] = 'success';
				}
			}else{
				$ret_data['data'] = [];
				$ret_data["states"] = 'error';
			}
		break;
		//根据巡查类型获取检查问题
		case 'getInspectQues':
			$itemsCate = isset($_POST["itemsCate"])?$_POST["itemsCate"]:'';
			
			$sql = "SELECT * FROM tb_quality_summary_table AS a WHERE a.itemsCate = '$itemsCate' ORDER BY a.id ASC";
			$result = $conn -> query($sql);
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$ret_data["data"][$i]['id'] = $row["id"];
					$ret_data["data"][$i]['itemsCate'] = $row["itemsCate"];
					$ret_data["data"][$i]['probDescribe'] = $row["probDescribe"];
					$ret_data["data"][$i]['fullMarks'] = $row["fullMarks"];
					$ret_data["data"][$i]['deductTableCate'] = $row["deductTableCate"];
					$ret_data["data"][$i]['weight'] = $row["weight"];
					
					$i++;
					$ret_data["states"] = 'success';
				}
			}else{
				$ret_data['data'] = [];
				$ret_data["states"] = 'error';
			}
		break;
		//保存检查问题
		case 'saveInsItems':
			$formData = isset($_POST["formData"])?$_POST["formData"]:'';
			$taskId = isset($_POST["taskId"])?$_POST["taskId"]:'';
			$formData = json_decode($formData);
			$deductTableCate = $formData->deductTableCate;
			$inspectCate = $formData->inspectCate;
			$inspectQues = $formData->inspectQues;
			array_shift($inspectQues);//删除  '选项0'
			foreach($inspectQues  as $obj){
				$question = explode('||', $obj)[0];
				$fullMarks = explode('||', $obj)[1];
				
				$sql="SELECT * FROM tb_quality_inspect_items where taskId = '$taskId' and `inspectCate` = '$inspectCate' and `inspectQues` = '$question'";
				$res = $conn->query($sql);
				if($res -> num_rows > 0){
					
				}else{
					$sql = "INSERT INTO tb_quality_inspect_items set `taskId` = '$taskId',`inspectCate` = '$inspectCate',`inspectQues` = '$question',`deductTableCate` = '$deductTableCate',fullMarks = '$fullMarks' ";
					$result = $conn->query($sql);
					$ret_data["states"] = 'success';
				}
			}
		break;
	}
	$conn -> close();
	$json = json_encode($ret_data);
	echo $json;
?>
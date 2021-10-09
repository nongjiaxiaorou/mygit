<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../conn.php");
	date_default_timezone_set('PRC');
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	
	switch($flag){
		//新增区段
		case 'addSection':
			$sectionNum = isset($_POST["sectionNum"])?$_POST["sectionNum"]:'';//新增区段数量
			$proTimeStamp = isset($_POST["proTimeStamp"])?$_POST["proTimeStamp"]:'';
			$projectName = isset($_POST["projectName"])?$_POST["projectName"]:'';

			$i = 1;
				while($i <= $sectionNum){
					$sql = "INSERT INTO tb_project_floor_information SET timeStamp = '$proTimeStamp',projectName = '$projectName' ";
					$result = $conn -> query($sql);
					$i++;
				}
			
			$conn -> close();
		break;
		//判断该工程该区段是否存在
		case 'checkSection':
			$proTimeStamp = isset($_POST["proTimeStamp"])?$_POST["proTimeStamp"]:'';
			$section = isset($_POST["section"])?$_POST["section"]:'';

			$sql = "SELECT * FROM tb_project_floor_information WHERE timeStamp = '$proTimeStamp' AND section = '$section' ORDER BY id ASC";
			$result = $conn -> query($sql);
			$data = array();
			if ($result -> num_rows > 0) {
				$data['code'] = 0;//存在
			}else{
				$data['code'] = 1;//不存在 可以定义这个区段的楼栋
			}
			$conn -> close();
			$json = json_encode($data);
			echo $json;
		break;
		//获取区段
		case 'getSection':
			$proTimeStamp = isset($_POST["proTimeStamp"])?$_POST["proTimeStamp"]:'';

			$sql = "SELECT * FROM tb_project_floor_information WHERE timeStamp = '$proTimeStamp' GROUP BY section ORDER BY id ASC";
			$result = $conn -> query($sql);
			$resData['getSection'] = $sql;
			$resData = array();
			if ($result -> num_rows > 0) {
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$resData[$i]["section"] = $row["section"];
					$resData[$i]["projectName"] = $row["projectName"];
					$resData[$i]["proTimeStamp"] = $row["timeStamp"];
					$i++;	
				}
			}
			$conn -> close();
			$json = json_encode($resData);
			echo $json;
		break;
		//删除区段
		case 'deleteSection':
			$section = isset($_POST["section"])?$_POST["section"]:'';

			$sql = "SELECT * FROM tb_project_floor_information WHERE section = '$section'";
			$result = $conn -> query($sql);
			$resData = array();
			if ($result -> num_rows > 0) {
				$sql = "DELETE FROM tb_project_floor_information WHERE section = '$section'";
				$result = $conn -> query($sql);
			}
			$conn -> close();
			$json = json_encode($resData);
			echo $json;
		break;
	}
	
?>
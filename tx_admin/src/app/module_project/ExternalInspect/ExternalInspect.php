<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
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
		//获取外部检查任务
		case 'getExternalTask':
			$userId = isset($_POST["userId"]) ? $_POST["userId"] : '';
			$sql = "SELECT * FROM tb_msg_external_inspect  WHERE msgReceiver='$userId'";
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
			$i=0;
            if($result->num_rows>0)	{
    	 		while($row=$result->fetch_assoc()){
    	 			$resData['id'] = $row["id"];
					$resData['taskTimeStamp'] = $row["taskTimeStamp"];
					$resData['projectId'] = $row["projectId"];
					$resData['inspectObj'] = $row["inspectObj"];
					$resData['companyRank'] = $row["companyRank"];
					$resData['inspectName'] = $row["inspectName"];
					$resData['groupLeader'] = $row["groupLeader"];
					$resData['deputyLeader'] = $row["deputyLeader"];
					$resData['actualMarks'] = $row["actualMarks"];
					$resData['correctMarks'] = $row["correctMarks"];
					$resData['rectifyState'] = $row["rectifyState"];
    	 			$res[$i] = $resData;
    	 		}
				$data['data'] = $res;
            }else {
				$data['code'] = 0;
			}
		break;
		//完成检查评分任务单
		case 'completeTaskNotice':
			$selectcard = isset($_POST["selectcard"]) ? $_POST["selectcard"] : '';
			
			$time=date("Y-m-d H:i:s");
			
			$selectcard = json_decode($selectcard);
			foreach($selectcard as $taskTimeStamp){
				$sql = "SELECT * FROM tb_msg_external_inspect WHERE `taskTimeStamp` = '$taskTimeStamp' ";
				$result = $conn -> query($sql);
				if ($result -> num_rows > 0) {
					$sql1 = "UPDATE tb_quality_inspect_task SET signState = '已确认' WHERE `timeStamp` = '$taskTimeStamp' ";
					$result1 = $conn -> query($sql1);
					$sql2 = "UPDATE tb_msg_external_inspect SET rectifyState = '已完成' WHERE `taskTimeStamp` = '$taskTimeStamp' ";
					$result2 = $conn -> query($sql2);
				}else {
					$data['code'] = 0;
				}
			}
		break;
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	

?>
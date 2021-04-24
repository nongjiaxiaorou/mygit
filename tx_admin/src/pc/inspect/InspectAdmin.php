<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$ret_data = array();
	switch($flag){
		//获取巡查小组信息
		case 'getInsTask':

			$sql="select timeStamp,`inspectObj`,`groupId`,`issueTime`,`correctMarks`,(@rowNum:=@rowNum+1) as rank from `tb_quality_inspect_task`,(select (@rowNum :=0) ) b order by tb_quality_inspect_task.correctMarks desc"; 
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
				$i = 0;
				while ($row = $result -> fetch_assoc()) {
					$sql1="SELECT * FROM `tb_quality_group_member` WHERE groupID = '".$row["groupId"]."'"; 
					$result1 = $conn->query($sql1);
					$row1 = $result1 -> fetch_assoc();
					$ret_data["data"][$i]["taskTimeStamp"] = $row["timeStamp"];
					$ret_data["data"][$i]["inspectObj"] = $row["inspectObj"];
					$ret_data["data"][$i]["groupId"] = $row["groupId"];
					$ret_data["data"][$i]["groupName"] = $row1["groupName"];
					$ret_data["data"][$i]["issueTime"] = $row["issueTime"];
					$ret_data["data"][$i]["rank"] = $row["rank"];
					$ret_data["data"][$i]["correctMarks"] = $row["correctMarks"];
					$ret_data["state"] = 'success';
					$i++;
				}
			}else{
				$ret_data["state"] = 'error';
			}
		break;
	}
	
	$json = json_encode($ret_data);
	echo $json;
	$conn->close();	
?>
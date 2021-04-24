<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	
	switch($flag){
		//获取详情信息
		case 'getDetailInfo':
			$inspectCardId = isset($_POST["inspectCardId"]) ? $_POST["inspectCardId"] : '';
			$res = array();
			$resData = array();
            $i=0;
            $sql = "SELECT * FROM tb_inspectaccept_notice WHERE id='$inspectCardId'";
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
            $i=0;
            if($result->num_rows>0)	{
            	$row = $result->fetch_assoc();
    	 		$resData['id'] = $row['id'];
				$resData['noticeNumber'] = $row['noticeNumber'];
				$resData['inspectLevel'] = $row['inspectLevel'];
				$resData['inspectPosition'] = $row['inspectPosition'];
				$resData['inspectDate'] = $row['inspectDate'];
				$resData['endDate'] = $row['endDate'];
				$resData['inspectPersonName'] = $row['inspectPersonName'];
				$resData['number'] = $row['number'];
				$resData['inspectProcedure'] = $row['inspectProcedure'];
				$resData['inspectItemHead'] = $row['inspectItemHead'];
				$res = $resData;
				$data['data'] = $res;
            }else {
				$data['code'] = 0;
			}
		break;
		//获取条目详情信息
		case 'getDetailItemInfo':
			$noticeTimeStamp = isset($_POST["noticeTimeStamp"]) ? $_POST["noticeTimeStamp"] : '';
			$res = array();
			$resData = array();
            $i=0;
            $sql = "SELECT * FROM tb_inspectaccept_violationitem WHERE noticeTimeStamp='$noticeTimeStamp'";
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
            $i=0;
            if($result->num_rows>0)	{
            	while($row = $result->fetch_assoc()){
	    	 		$resData['id'] = $row['id'];
					$resData['title'] = $row['itemType'];
					$resData['itemDescribe'] = $row['violationContent'];
					$resData['number'] = $row['number'];
					$resData['picName'] = $row['picFile'];
					$res[$i] = $resData;
	                $i++;
				}
				$data['data'] = $res;
            }else {
				$data['code'] = 0;
			}
		break;
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	

?>
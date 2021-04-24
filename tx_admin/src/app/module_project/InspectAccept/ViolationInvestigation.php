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
		//获取违章项目
		case 'getViolationItem':
			$noticeTimeStamp = isset($_POST["noticeTimeStamp"]) ? $_POST["noticeTimeStamp"] : '';
			$sql = "SELECT * FROM tb_inspectaccept_violationitem WHERE noticeTimeStamp='$noticeTimeStamp'";
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
            $i=0;
            if($result->num_rows>0)	{
        	 	while($row = $result->fetch_assoc()){
        	 		$resData['id'] = $row['id'];
					$resData['itemType'] = $row['itemType'];
					$resData['violationContent'] = $row['violationContent'];
					$resData['picName'] = $row['picFile'];
					$resData['rectificationStatus'] = $row['rectificationStatus'];
					$res[$i] = $resData;
	                $i++;
				}
				$data['data'] = $res;
            }else {
				$data['code'] = 0;
			}
		break;
		//获取整改参数
		case 'getRectificationParam':
			$noticeCardTimeStamp = isset($_POST["noticeCardTimeStamp"]) ? $_POST["noticeCardTimeStamp"] : '';
			$sql = "SELECT * FROM tb_inspectaccept_notice WHERE noticeTimeStamp='$noticeCardTimeStamp'";
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
		    $i=0;
		    if($result->num_rows>0)	{
			 	while($row = $result->fetch_assoc()){
			 		$resData['notice']['id'] = $row['id'];
					$resData['notice']['inspectDate'] = $row['inspectDate'];
					$resData['notice']['inspectItemHead'] = $row['inspectItemHead'];
					$resData['notice']['inspectPersonName'] = $row['inspectPersonName'];
					$resData['notice']['inspectSendPerson'] = $row['inspectSendPerson'];
					$resData['notice']['noticeTimeStamp'] = $row['noticeTimeStamp'];
					$resData['notice']['noticeNumber'] = $row['noticeNumber'];
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
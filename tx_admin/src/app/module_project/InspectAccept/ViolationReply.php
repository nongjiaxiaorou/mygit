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
		//保存违章图片及回复内容
		case 'saveReplyPic':
			$img_path='../../../images/app_pic/inspectReplyPic/';
			$pic_obj = isset($_POST["imageBaseList"]) ? $_POST["imageBaseList"] : ''; //获取base64数据流
			$violationId = isset($_POST["violationId"]) ? $_POST["violationId"] : '';
			$obj=json_decode($pic_obj,true);
			$img_name = msectime();
//			$res = '{"status":0,"info":"请上传正确格式的图片"}';		
			$sql1 = "SELECT * FROM tb_inspectaccept_violationitem  WHERE id='$violationId' AND replyPicFile!=''";
//			echo $fileCategory;
			$result1 = $conn->query($sql1);
			if($result1->num_rows>0){
				while($row1=$result1->fetch_assoc()){
					$pic = $row1["replyPicFile"];
					$a = explode("||",$pic);
					for($i=0;$i<count($a)-1;$i++){
						$lj = $img_path.$a[$i];
						if(file_exists($lj)){
							unlink($lj);
						}
					}
				}
				$sql2 = "UPDATE `tb_inspectaccept_violationitem` SET `replyPicFile`='' WHERE id = '$violationId'";
				$conn->query($sql2);
			}
			$i=0;
			if($pic_obj!="{}"){
				foreach($obj as $key=>$val){
					$images_name = "";
					$images_name =  $img_name.$i.'.'.'jpg';
					$base64 = explode(",", $val);
					$data_base = base64_decode($base64[1]);
					file_put_contents($img_path.$images_name, $data_base);
					$pic_info = $images_name."->"."暂无回复内容";
					$sql3 = "UPDATE `tb_inspectaccept_violationitem` SET `replyPicFile` = CONCAT(`replyPicFile`,'$pic_info','||') WHERE id = '$violationId'";
					$i++;
				    $result3 = $conn->query($sql3);
				    if(!$result3){
				    	$data['code'] = 0;
				    }
				}
			}
		break;
		//获取违章图片及内容
		case 'getReplyPic':
			$violationId = isset($_POST["violationId"]) ? $_POST["violationId"] : '';
			$sql = "SELECT * FROM tb_inspectaccept_violationitem  WHERE id='$violationId' AND replyPicFile!=''";
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
            if($result->num_rows>0)	{
            	$row=$result->fetch_assoc();
    	 		$replyPicArr = explode("||",$row['replyPicFile']);
    	 		for($i=0;$i<count($replyPicArr)-1;$i++){
    	 			$PicContentArr = explode("->",$replyPicArr[$i]);
    	 			$resData['picName'] = $PicContentArr[0];
    	 			$resData['content'] = $PicContentArr[1];
    	 			$res[$i] = $resData;
    	 		}
				$data['data'] = $res;
            }else {
				$data['code'] = 0;
			}
		break;
		//修改回复内容
		case 'changeReplyContent':
			$violationId = isset($_POST["violationId"]) ? $_POST["violationId"] : '';
			$itemList = isset($_POST["itemList"]) ? $_POST["itemList"] : '';
			$itemList=json_decode($itemList,true);
			$sql = "SELECT * FROM tb_inspectaccept_violationitem  WHERE id='$violationId' AND replyPicFile!=''";
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
            if($result->num_rows>0)	{
            	$row=$result->fetch_assoc();
    	 		$replyPicArr = explode("||",$row['replyPicFile']);
    	 		//先清空后将数据处理后重新赋值
    	 		$sql2 = "UPDATE `tb_inspectaccept_violationitem` SET `replyPicFile` = '' WHERE id = '$violationId'";
	 			$conn -> query($sql2);
    	 		for($i=0;$i<count($replyPicArr)-1;$i++){
    	 			$content = $itemList[$i]["content"];
    	 			$PicContentArr = explode("->",$replyPicArr[$i]);
    	 			$picNameStr = $PicContentArr[0];
    	 			$contentStr = $content;
    	 			$PicContentStr = $picNameStr."->".$contentStr;
    	 			$sql1 = "UPDATE `tb_inspectaccept_violationitem` SET `replyPicFile` = CONCAT(`replyPicFile`,'$PicContentStr','||') WHERE id = '$violationId'";
    	 			$conn -> query($sql1);
    	 		}
            }else {
				$data['code'] = 0;
			}
		break;
		//更新违章条目状态
		case 'updateViolationItemState':
			$violationId = isset($_POST["violationId"]) ? $_POST["violationId"] : '';
			$state = isset($_POST["state"]) ? $_POST["state"] : '';
			$sql = "SELECT * FROM tb_inspectaccept_violationitem  WHERE id='$violationId'";
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
		    if($result->num_rows>0)	{
				$sql1 = "UPDATE tb_inspectaccept_violationitem SET rectificationStatus='$state' WHERE id='$violationId'";
				$result = $conn -> query($sql1);
		    }else {
				$data['code'] = 0;
			}
		break;
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	

?>
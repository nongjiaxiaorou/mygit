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
		//保存验收图片及回复内容
		case 'saveAcceptPic':
			$img_path='../../../images/app_pic/acceptPic/';
			$pic_obj = isset($_POST["imageBaseList"]) ? $_POST["imageBaseList"] : ''; //获取base64数据流
			$acceptTimeStamp = isset($_POST["acceptTimeStamp"]) ? $_POST["acceptTimeStamp"] : '';
			$procedure = isset($_POST["procedure"]) ? $_POST["procedure"] : '';
			$obj=json_decode($pic_obj,true);
			$img_name = msectime();
//			$res = '{"status":0,"info":"请上传正确格式的图片"}';		
			$sql1 = "SELECT * FROM tb_inspectaccept_acceptPic  WHERE acceptTimeStamp='$acceptTimeStamp'";
//			echo $fileCategory;
			$result1 = $conn->query($sql1);
			if($result1->num_rows>0){
				while($row1=$result1->fetch_assoc()){
					$pic = $row1["acceptPic"];
					$lj = $img_path.$pic;
					if(file_exists($lj)){
						unlink($lj);
					}
				}
				$sql2 = "DELETE FROM tb_inspectaccept_acceptPic WHERE `acceptTimeStamp`='$acceptTimeStamp'";
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
					$sql3 = "INSERT `tb_inspectaccept_acceptPic` SET acceptTimeStamp= '$acceptTimeStamp',acceptProcedure= '$procedure',acceptPic= '$images_name',acceptPicDescribe= '暂无验收回复内容'";
					$i++;
				    $result3 = $conn->query($sql3);
				    if(!$result3){
				    	$data['code'] = 0;
				    }
				}
			}
		break;
		//获取验收图片及内容
		case 'getAcceptPic':
			$acceptTimeStamp = isset($_POST["acceptTimeStamp"]) ? $_POST["acceptTimeStamp"] : '';
			$sql = "SELECT * FROM tb_inspectaccept_acceptPic  WHERE acceptTimeStamp='$acceptTimeStamp'";
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
			$i=0;
            if($result->num_rows>0)	{
    	 		while($row=$result->fetch_assoc()){
    	 			$resData['id'] = $row["id"];
    	 			$resData['picName'] = $row["acceptPic"];
    	 			$resData['content'] = $row["acceptPicDescribe"];
    	 			$res[$i] = $resData;
    	 		}
				$data['data'] = $res;
            }else {
				$data['code'] = 0;
			}
		break;
		//修改回复内容
		case 'changeAcceptContent':
			$acceptTimeStamp = isset($_POST["acceptTimeStamp"]) ? $_POST["acceptTimeStamp"] : '';
			$itemList = isset($_POST["itemList"]) ? $_POST["itemList"] : '';
			$itemList=json_decode($itemList,true);
			$sql = "SELECT * FROM tb_inspectaccept_acceptPic  WHERE acceptTimeStamp='$acceptTimeStamp'";
			$result = $conn -> query($sql);
			$res = array();
			$resData = array();
            if($result->num_rows>0)	{
            	$i=0;
            	while($row=$result->fetch_assoc()){
            		$acceptPicId = $itemList[$i]["id"];
            		$content = $itemList[$i]["content"];
		 			$sql1 = "UPDATE `tb_inspectaccept_acceptPic` SET `acceptPicDescribe` = '$content' WHERE id = '$acceptPicId'";
		 			$conn -> query($sql1);
		 		}
            }else {
				$data['code'] = 0;
			}
		break;
	}
	$json = json_encode($data);
	echo $json;
	$conn->close();	

?>
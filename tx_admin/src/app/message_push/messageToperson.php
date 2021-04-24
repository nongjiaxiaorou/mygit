<?php
	error_reporting(E_ALL || ~E_NOTICE); //显示除去 E_NOTICE 之外的所有错误信息
	//消息推送Demo
	header("Content-Type: text/html; charset=utf-8");
	require_once(dirname(__FILE__) . '/' . 'IGt.Push.php');
	require_once(dirname(__FILE__) . '/' . 'IGt.Push.php');
	require_once(dirname(__FILE__) . '/' . 'igetui/IGt.AppMessage.php');
	require_once(dirname(__FILE__) . '/' . 'igetui/IGt.APNPayload.php');
	require_once(dirname(__FILE__) . '/' . 'igetui/template/IGt.BaseTemplate.php');
	require_once(dirname(__FILE__) . '/' . 'IGt.Batch.php');
	require_once(dirname(__FILE__) . '/' . 'igetui/utils/AppConditions.php');
	require("../../../conn.php");
	
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	$selectPerson = isset($_POST["selectPerson"]) ? $_POST["selectPerson"] : '';
	$cardId = isset($_POST["cardId"]) ? $_POST["cardId"] : '';
	$cardTimeStamp = isset($_POST["cardTimeStamp"]) ? $_POST["cardTimeStamp"] : '';
	if($cardId){
		$str = "cardId='$cardId' ";
	}else{
		$str = "cardTimeStamp='$cardTimeStamp' ";
	}
	$CID = array();
	switch($flag){
		case 'concrete':
			$sql1="SELECT * FROM tb_msg_notice WHERE ".$str." ";
			$result1 = $conn->query($sql1);
			$row1=$result1->fetch_assoc();
			$content=$row1["content"];
			$title=$row1["title"];
		break;	
	}
	$Person = explode(",",$selectPerson);
	$receiverIdArr = [];
	$i=0;
	foreach($Person as $receiver){
		$receiver = explode('|', $receiver);
		$receiverIdArr[$i] = $receiver[3];
		$i++;
	}
	$selectPerson = implode(",",$arr);
//	echo $selectPerson;
	 $sql = "select CID from `tb_system_user` where `phone` in ($selectPerson) AND `CID` !='' ";
	 $result = $conn->query($sql);
	 $CID = array();
	 if($result->num_rows>0){
	 	$i=0;
	 	while($row=$result->fetch_assoc()){
	 		$CID[$i] = $row["CID"];
	 		$i++;
	 	}
		
	 }
	//http的域名
	//现网demo
	define('APPKEY','ntX852Y91y6DSbORCeuHN6');
	define('APPID','gW4r6VbomW85dWWymOPVV5');
	define('MASTERSECRET','82E7qWWMz08jHNrgv2Z0H3');
	define('HOST',"http://sdk.open.api.igexin.com/apiex.htm");


	pushMessageToSingle($CID,$title,$content);

	//单推接口案例
	function pushMessageToSingle($CID,$title,$content){
		$igt = new IGeTui(HOST,APPKEY,MASTERSECRET);

		//消息模版：
		// 4.NotyPopLoadTemplate：通知弹框下载功能模板
		$template = IGtNotificationTemplateDemo($title,$content);

		//定义"SingleMessage"
		$message = new IGtSingleMessage();

		$message->set_isOffline(true);//是否离线
		$message->set_offlineExpireTime(3600*72*1000);//离线时间
		$message->set_data($template);//设置推送消息类型
		//$message->set_PushNetWorkType(0);//设置是否根据WIFI推送消息，2为4G/3G/2G，1为wifi推送，0为不限制推送
		//接收方
		$target = new IGtTarget();
		$target->set_appId(APPID);
		//    $target->set_alias(Alias);
		for($i=0;$i<count($CID);$i++){
			$target->set_clientId($CID[$i]);
			try {
				$rep = $igt->pushMessageToSingle($message, $target);
				// echo json_encode($rep);
				var_dump($rep);
				// echo ("<br><br>");
			
			}catch(RequestException $e){
				$requstId =e.getRequestId();
				//失败时重发
				$rep = $igt->pushMessageToSingle($message, $target,$requstId);
				echo json_encode($rep);
				// var_dump($rep);
				// echo ("<br><br>");
			}
		}
	}

	function IGtNotificationTemplateDemo($title,$content){
		$template =  new IGtNotificationTemplate();
		$template->set_appId(APPID);                   //应用appid
		$template->set_appkey(APPKEY);                 //应用appkey
		$template->set_transmissionType(1);            //透传消息类型
		$template->set_transmissionContent("");//透传内容
		// STEP3：设置推送标题、推送内容
		$template->set_title($title);      //通知栏标题
		$template->set_text($content);     //通知栏内容 
		$template->set_logo("");                       //通知栏logo
		$template->set_logoURL("");                    //通知栏logo链接
		//STEP4：设置响铃、震动等推送效果
		$template->set_isRing(true);                   //是否响铃
		$template->set_isVibrate(true);                //是否震动
		$template->set_isClearable(true);              //通知栏是否可清除
		// echo $title.$content;
		return $template;
	}


	//群推接口案例
	function pushMessageToApp(){
		$igt = new IGeTui(HOST,APPKEY,MASTERSECRET);
		// STEP2：选择通知模板
		//定义透传模板，设置透传内容，和收到消息是否立即启动启用
		$template = IGtNotificationTemplateDemo();
		// STEP5：定义"AppMessage"类型消息对象，设置消息内容模板、发送的目标App列表、是否支持离线发送、以及离线消息有效期(单位毫秒)
		$message = new IGtAppMessage();
		$message->set_isOffline(true);
		$message->set_offlineExpireTime(10 * 60 * 1000);//离线时间单位为毫秒，例，两个小时离线为3600*1000*2
		$message->set_data($template);

		$appIdList=array(APPID);
		$phoneTypeList=array('ANDROID');
		$provinceList=array('浙江');
		$tagList=array('haha');

		$message->set_appIdList($appIdList);
		//$message->set_conditions($cdt->getCondition());
		// STEP6：执行推送
		$rep = $igt->pushMessageToApp($message,"任务组名");
		// echo "<pre/>";
	 //    var_dump($rep);
	 //    echo ("<br><br>");
	}
?>

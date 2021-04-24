<?php
	require_once 'conn.php';
	error_reporting(0);
	date_default_timezone_set('PRC');
	$time=date("Y-m-d H:i:s");
	
	///////////////////////////////////////////////////问题隐患//////////////////////////////////////////////////////////////
	$sql = "SELECT * FROM view_hiddendanger as a WHERE state = '整改中' ORDER BY a.id DESC";
	$result = $conn -> query($sql);
	if ($result -> num_rows > 0) {
		$i=0;
		while ($row = $result -> fetch_assoc()) {
			$a = substr($row["rectificationDate"],0,10);//下发时间t
			$b = date("Y-m-d",time());//今天
			$c = date("Y-m-d",strtotime("$a +2 day"));//t+2d
			$d = date("Y-m-d",strtotime("$a +4 day"));//t+4d
			$e = date("Y-m-d",strtotime("$a +6 day"));//t+6d
			$f = date("Y-m-d",strtotime("$a +9 day"));//t+9d
//			echo $c.'//'.$d.'//'.$e.'//'.$f;
			//预警给生产经理
			if(strtotime($b)==strtotime($c)){
				echo '生产经理'.$c;
				$sql1 = "INSERT INTO tb_msg_warning SET company = '".$row['company']."',projectName = '".$row['projectName']."',inspectPosition = '".$row['inspectPosition']."',noticeNumber = '".$row['noticeNumber']."',endDate = '".$row['endDate']."',violationContent = '".$row['violationContent']."',warningTime = '$time',warningPost = '生产经理',warningCate = '问题隐患' ";
				$result1 = $conn -> query($sql1);
			}
//			//预警给项目总工
			if(strtotime($b)==strtotime($d)){
				echo '项目总工'.$d;
				$sql2 = "INSERT INTO tb_msg_warning SET company = '".$row['company']."',projectName = '".$row['projectName']."',inspectPosition = '".$row['inspectPosition']."',noticeNumber = '".$row['noticeNumber']."',endDate = '".$row['endDate']."',violationContent = '".$row['violationContent']."',warningTime = '$time',warningPost = '项目总工',warningCate = '问题隐患' ";
				$result2 = $conn -> query($sql2);
			}
//			//预警给项目经理
			if(strtotime($b)==strtotime($e)){
				echo '项目经理'.$e;
				$sql3 = "INSERT INTO tb_msg_warning SET company = '".$row['company']."',projectName = '".$row['projectName']."',inspectPosition = '".$row['inspectPosition']."',noticeNumber = '".$row['noticeNumber']."',endDate = '".$row['endDate']."',violationContent = '".$row['violationContent']."',warningTime = '$time',warningPost = '项目经理',warningCate = '问题隐患'";
				$result3 = $conn -> query($sql3);
			}
//			//预警给分公司总工
			if(strtotime($b)==strtotime($f)){
				echo '分公司总工'.$f;
				$sql4 = "INSERT INTO tb_msg_warning SET company = '".$row['company']."',projectName = '".$row['projectName']."',inspectPosition = '".$row['inspectPosition']."',noticeNumber = '".$row['noticeNumber']."',endDate = '".$row['endDate']."',violationContent = '".$row['violationContent']."',warningTime = '$time',warningPost = '分公司总工',warningCate = '问题隐患' ";
				$result4 = $conn -> query($sql4);
			}
			$i++;
			$ret_data["states"] = 'success';
		}
	}else{
		$ret_data['data'] = [];
		$ret_data["states"] = 'error';
	}
	///////////////////////////////////////////////////整改闭合率 //////////////////////////////////////////////////////
	//循环所有工程id
	$sql = "SELECT DISTINCT projectId FROM view_rectificationclose as a ORDER BY a.id DESC";
	$result = $conn -> query($sql);
	if ($result -> num_rows > 0) {
		$i=0;
		while ($row = $result -> fetch_assoc()) {
			$a = date("Y-m-d",time());//取今天日期					
			$b =  substr($a,-2);//只取 日
			$c = "01";
			$d = "15";
			if($b==$c||$b==$d){//等于每个月的1号或者15号时
				//计算该工程的已完成（闭合）的问题隐患个数
				$sql1 = "SELECT Count(a.id) AS sum1 FROM view_rectificationclose as a WHERE projectId = '".$row['projectId']."' AND state = '已完成' ORDER BY a.id DESC";
				$result1 = $conn -> query($sql1);
				$row1 = $result1->fetch_assoc();
						
				//再计算该工程的所有（不管是否闭合）的问题隐患个数
				$sql2 = "SELECT Count(a.id) AS sum2,company,projectName FROM view_rectificationclose as a WHERE projectId = '".$row['projectId']."' ORDER BY a.id DESC";
				$result2 = $conn -> query($sql2);
				$row2 = $result2->fetch_assoc();
						
				//计算整改闭合率（即已完成隐患条数占总隐患条数的比例）
				$parcent =  round($row1["sum1"]/$row2["sum2"]*100);
				
				//预警给分公司部门经理/总工
				if($parcent<70){
//					echo '分公司部门经理/总工'.$parcent;
					$sql = "INSERT INTO tb_msg_warning SET company = '".$row2['company']."',projectName = '".$row2['projectName']."',rectifyRate = '$parcent',warningTime = '$time',warningPost = '分公司部门经理',warningCate = '整改闭合率' ";
					$result = $conn -> query($sql);
					
					$sql = "INSERT INTO tb_msg_warning SET company = '".$row2['company']."',projectName = '".$row2['projectName']."',rectifyRate = '$parcent',warningTime = '$time',warningPost = '总工',warningCate = '整改闭合率' ";
					$result = $conn -> query($sql);
				}
				//预警给总公司片区经理/部门经理
				if($parcent<60){
//					echo '总公司片区经理/部门经理'.$parcent;
					$sql = "INSERT INTO tb_msg_warning SET company = '".$row2['company']."',projectName = '".$row2['projectName']."',rectifyRate = '$parcent',warningTime = '$time',warningPost = '总公司片区经理',warningCate = '整改闭合率' ";
					$result = $conn -> query($sql);
					
					$sql = "INSERT INTO tb_msg_warning SET company = '".$row2['company']."',projectName = '".$row2['projectName']."',rectifyRate = '$parcent',warningTime = '$time',warningPost = '部门经理',warningCate = '整改闭合率' ";
					$result = $conn -> query($sql);
				}
			}
			$i++;
			$ret_data["states"] = 'success';
		}
	}else{
		$ret_data['data'] = [];
		$ret_data["states"] = 'error';
	}		
	////////////////////////////////////////////////////每周巡检次数预警///////////////////////////////////////////////////////
	$date = new DateTime();
	$date->modify('this week');
	$first_day_of_week = $date->format('Y-m-d');//周头
	$date->modify('this week +6 days');
	$end_day_of_week = $date->format('Y-m-d');//周尾
			
	//现场质量周巡检
	$sql = "SELECT * FROM tb_weekly_scene_notice WHERE createTime >= '$first_day_of_week' AND createTime <= '$end_day_of_week'";
	$result = $conn -> query($sql);
	if ($result -> num_rows > 0) {//有周巡检
		//预警给项目经理/项目总工
		$sql = "INSERT INTO tb_msg_warning SET projectName = '".$row['projectName']."',warningTime = '$time',warningPost = '项目经理',warningCate = '现场质量周巡检' ";
		$result = $conn -> query($sql);
				
		$sql = "INSERT INTO tb_msg_warning SET projectName = '".$row['projectName']."',warningTime = '$time',warningPost = '项目总工',warningCate = '现场质量周巡检' ";
		$result = $conn -> query($sql);	
	}else{//无周巡检
//		echo '无现场质量周巡检';
	}
			
	//方案资料周巡检
	$sql = "SELECT * FROM tb_weekly_file_notice WHERE createTime >= '$first_day_of_week' AND createTime <= '$end_day_of_week'";
	$result = $conn -> query($sql);
	if ($result -> num_rows > 0) {//有周巡检
		//预警给项目经理/项目总工
		$sql = "INSERT INTO tb_msg_warning SET projectName = '".$row['projectName']."',warningTime = '$time',warningPost = '项目经理',warningCate = '方案资料周巡检' ";
		$result = $conn -> query($sql);
				
		$sql = "INSERT INTO tb_msg_warning SET projectName = '".$row['projectName']."',warningTime = '$time',warningPost = '项目总工',warningCate = '方案资料周巡检' ";
		$result = $conn -> query($sql);		
	}else{//无周巡检
//		echo '无方案资料周巡检';
	}
 
?>
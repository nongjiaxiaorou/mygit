<?php
	require ("../../../conn.php");
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
	$flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
	);
	
	switch($flag){
		case 'getSectionInfo':
			$timeStamp = isset($_POST["timeStamp"]) ? $_POST["timeStamp"] : '';
			$sql = "SELECT 	*, GROUP_CONCAT(category SEPARATOR '&') AS categoryMerge FROM 	tb_project_floor_information WHERE `timeStamp` = '$timeStamp' GROUP BY `section` ORDER BY id ASC";
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
				$i=0;
				while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['label'] = $row['section'];
					$resData['value'] = $row['section'];
					$resData['category'] = $row['category'];
					$categoryMerge = $row['categoryMerge'];
					$childData = array();
					if(strpos($categoryMerge, '&')!== false){
						$category = explode("&",$categoryMerge);
						$category_arr = array_unique($category); //数组去重
						for($j=0;$j<count($category_arr);$j++){
							$item = array();
							$item['value'] = $category_arr[$j];
							$item['label'] = $category_arr[$j];
							$childData[$j] = $item;
						}
					}else{
						$item = array();
						$item['value'] = $categoryMerge;
						$item['label'] = $categoryMerge;
						$childData[0] = $item;
					}
					$resData['children'] = $childData;
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
		break;
		case 'getBuildInfo':
			$timeStamp = isset($_POST["timeStamp"]) ? $_POST["timeStamp"] : '';
			$sectionSel = isset($_POST["sectionSel"]) ? $_POST["sectionSel"] : '';
			$sectionSelArr = explode(",",$sectionSel);
			$sql = "SELECT * FROM tb_project_floor_information WHERE `timeStamp`='$timeStamp' AND `category`='$sectionSelArr[1]' AND `section`='$sectionSelArr[0]'";
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
				$i=0;
				while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['section'] = $row['section'];
					$resData['build'] = $row['build'];
					$resData['unitName'] = $row['unitName'];
					$resData['undergroundNumber'] = $row['undergroundNumber'];
					$resData['abovegroundNumber'] = $row['abovegroundNumber'];
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
		break;
		//更新栋号名称
		case 'updateBuildInfo':
			$buildId = isset($_POST["buildId"]) ? $_POST["buildId"] : '';
			$newBuild = isset($_POST["newBuild"]) ? $_POST["newBuild"] : '';
			$timeStamp = isset($_POST["timeStamp"]) ? $_POST["timeStamp"] : '';
			$sectionSel = isset($_POST["sectionSel"]) ? $_POST["sectionSel"] : '';
			$sectionSelArr = explode(",",$sectionSel);
			$sql1 = "SELECT * FROM tb_project_floor_information WHERE `timeStamp`='$timeStamp' AND `build`='$newBuild' AND `section`='$sectionSelArr[0]'";
			$result1 = $conn -> query($sql1);
			if($result1->num_rows==0){
				$sql = "UPDATE tb_project_floor_information SET build='$newBuild' WHERE id='$buildId'";
				$res = $conn -> query($sql);
			}else{
				$data['code'] = 0;
			}
			
		break;
		case 'getStandardTable':
			$timeStamp = isset($_POST["timeStamp"]) ? $_POST["timeStamp"] : '';
			$sql = "SELECT * FROM tb_project WHERE timeStamp='$timeStamp'";
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$row = $result->fetch_assoc();
				$data['data'] = $row["standard"];
			}else{
				$data['code'] = 0;
			}
		break;
		case 'getPicInfo':
			$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';
			$section = isset($_POST["section"]) ? $_POST["section"] : '';
			$build = isset($_POST["build"]) ? $_POST["build"] : '';
			$sql = "SELECT * FROM tb_project_floor_pic WHERE proTimeStamp='$proTimeStamp' AND build='$build' AND section='$section'";
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
				$i=0;
				while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['picName'] = $row['picName'];
					$resData['floor'] = $row['floor'];
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
		break;
		//新增楼栋
		case 'addNewBuild':
			$timeStamp = isset($_POST["protimeStamp"]) ? $_POST["protimeStamp"] : '';
			$projectName = isset($_POST["projectName"]) ? $_POST["projectName"] : '';
			$build = isset($_POST["build"]) ? $_POST["build"] : '';
			$undergroundNumber = isset($_POST["undergroundNumber"]) ? $_POST["undergroundNumber"] : '';
			$abovegroundNumer = isset($_POST["abovegroundNumer"]) ? $_POST["abovegroundNumer"] : '';
			$unit = isset($_POST["unit"]) ? $_POST["unit"] : '';
			$sectionData = isset($_POST["sectionData"]) ? $_POST["sectionData"] : '';
			$sectionSelArr = explode(",",$sectionData);
			$sql1 = "SELECT * FROM tb_project_floor_information WHERE `timeStamp`='$timeStamp' AND `build`='$build' AND `section`='$sectionSelArr[0]'";
			$result1 = $conn -> query($sql1);
			if($result1->num_rows==0){
				$sql = "INSERT tb_project_floor_information SET timeStamp='$timeStamp',projectName='$projectName',build='$build',section='$sectionSelArr[0]',category='$sectionSelArr[1]',unitName='$unit',abovegroundNumber='$abovegroundNumer',undergroundNumber='$undergroundNumber'";
				$res = $conn -> query($sql);
			}else{
				$data['code'] = 0;
			}
			
		break;
		//更新单元名称
		case 'updateUnitInfo':
			$buildId = isset($_POST["buildId"]) ? $_POST["buildId"] : '';
			$newUnit = isset($_POST["newUnit"]) ? $_POST["newUnit"] : '';
			$sql1 = "SELECT * FROM tb_project_floor_information WHERE `id`='$buildId' AND unitName='$newUnit'";
			$result1 = $conn -> query($sql1);
			if($result1->num_rows==0){
				$sql = "UPDATE tb_project_floor_information SET unitName='$newUnit' WHERE id='$buildId'";
				$res = $conn -> query($sql);
			}else{
				$data['code'] = 0;
			}
			
		break;
		case 'getUnitInfo':
			$buildId = isset($_POST["buildId"]) ? $_POST["buildId"] : '';
			$sql = "SELECT * FROM tb_project_floor_information WHERE `id`='$buildId'";
			$result = $conn->query($sql);
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
				$i=0;
				while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['unitName'] = $row['unitName'];
					$res[$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = 0;
			}
		break;
		//获取工程管理人员
		case 'getAllPerson':
			$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';//工程对应的时间戳	
			$sql = "SELECT * FROM tb_project_administrator WHERE `protimeStamp` = '$proTimeStamp'";
			$result = $conn -> query($sql);
			if ($result->num_rows > 0) {
				$res = array();
				$resData = array();
				$i=0;
				while ($row = $result -> fetch_assoc()) {
					$sql1 = "SELECT * FROM tb_project_build_person WHERE `userId` = '".$row['userId']."'";
					$result1 = $conn -> query($sql1);
					if ($result1->num_rows>0) {
						$row1 = $result1 -> fetch_assoc();
						$resData['id'] = $row['id'];
						$resData['userId'] = $row['userId'];
						$resData['username'] = $row['username'];
						$resData['phone'] = $row['phone'];
						$resData['label'] = $row['username'].'/'.$row['phone'];
						$resData['value'] = $row['username'].'/'.$row['phone'].'/'.$row['userId'].'/'.$row1['id'];
						$resData['selValue'] = $row['username'].'/'.$row['phone'].'/'.$row['userId'].'/'.$row1['id'];
						$post = $row['post'];
					}else{
						$row1 = $result1 -> fetch_assoc();
						$resData['id'] = $row['id'];
						$resData['userId'] = $row['userId'];
						$resData['username'] = $row['username'];
						$resData['phone'] = $row['phone'];
						$resData['label'] = $row['username'].'/'.$row['phone'];
						$resData['value'] = $row['username'].'/'.$row['phone'].'/'.$row['userId'].'/'.$row1['id'];
						$resData['selValue'] = '';
						$post = $row['post'];
					}
					$res[$post][$i] = $resData;
					$i++;
				}
				$data['data'] = $res;
			}else{
				$data['code'] = $result1->num_rows;
			}
		break;
		//删除楼栋管理人员
		case 'deleteBuildPerson':
			$personId = isset($_POST["personId"]) ? $_POST["personId"] : '';
			$sql1 = "SELECT * FROM tb_project_build_person WHERE `id`='$personId'";
			$result1 = $conn -> query($sql1);
			if($result1->num_rows>0){
				$sqlDelete = "DELETE FROM tb_project_build_person WHERE id='$personId'";
				$conn -> query($sqlDelete);
			}else{
				$data['code'] = 0;
			}
			
		break;
		case 'getBuildPerson':
			$buildId = isset($_POST["buildId"]) ? $_POST["buildId"] : '';
			$timeStamp = isset($_POST["timeStamp"]) ? $_POST["timeStamp"] : '';
			$sql = "SELECT * FROM `tb_project_build_person` WHERE `buildId`='$buildId' AND `timeStamp` = '$timeStamp'";
			$result = $conn->query($sql);
			$data['sql'] = $sql;
			$data['result'] = $result->num_rows;
			if($result->num_rows>0)	{
				$res = array();
				$resData = array();
				$i=0;
				$j=0;
				$k=0;
				while($row = $result->fetch_assoc()){
					$resData['id'] = $row['id'];
					$resData['userId'] = $row['userId'];
					$resData['username'] = $row['username'];
					$resData['phone'] = $row['phone'];
					$resData['label'] = $row['username'].'/'.$row['phone'];
					$resData['value'] = $row['username'].'/'.$row['phone'].'/'.$row['userId'].'/'.$row['id'];
					$resData['selValue'] = $row['username'].'/'.$row['phone'].'/'.$row['userId'].'/'.$row['id'];
					$post = $row['post'];
					if($post=="栋号长"){
						$res[$post][$i] = $resData;
						$i++;
					}else if($post=="质量员"){
						$res[$post][$j] = $resData;
						$j++;
					}else if($post=="施工员"){
						$res[$post][$k] = $resData;
						$k++;
					}
				}
				$data['code'] = $result->num_rows;
				$data['data'] = $res;
			}else{
				$data['code'] = $result->num_rows;
				
			}
		break;
		//添加栋号管理人员
		case 'addNewBuildPerson':
			$leaderNodeList = isset($_POST["leaderNodeList"]) ? $_POST["leaderNodeList"] : '';
			$qualityOptions = isset($_POST["qualityOptions"]) ? $_POST["qualityOptions"] : '';
			$constructionOptions = isset($_POST["constructionOptions"]) ? $_POST["constructionOptions"] : '';
			$buildId = isset($_POST["buildId"]) ? $_POST["buildId"] : '';
			$proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';
			$leaderNodeList = json_decode($leaderNodeList,true);
			$qualityOptions = json_decode($qualityOptions,true);
			$constructionOptions = json_decode($constructionOptions,true);
			$sqlDelete = "DELETE FROM tb_project_build_person WHERE buildId='$buildId'";
			$conn -> query($sqlDelete);
			for($i=0;$i<count($leaderNodeList);$i++){
				$selArr = explode("/",$leaderNodeList[$i]["selValue"]);
				$post = $leaderNodeList[$i]["post"];
				$sqlInsert = "INSERT INTO tb_project_build_person SET userId='$selArr[2]',buildId='$buildId',timeStamp='$proTimeStamp',post='$post',username='$selArr[0]',phone='$selArr[1]'";
				$result = $conn -> query($sqlInsert);
			}
			for($i=0;$i<count($qualityOptions);$i++){
				$selArr = explode("/",$qualityOptions[$i]["selValue"]);
				$post = $qualityOptions[$i]["post"];
				$sqlInsert = "INSERT INTO tb_project_build_person SET userId='$selArr[2]',buildId='$buildId',timeStamp='$proTimeStamp',post='$post',username='$selArr[0]',phone='$selArr[1]'";
				$result = $conn -> query($sqlInsert);
			}
			for($i=0;$i<count($constructionOptions);$i++){
				$selArr = explode("/",$constructionOptions[$i]["selValue"]);
				$post = $constructionOptions[$i]["post"];
				$sqlInsert = "INSERT INTO tb_project_build_person SET userId='$selArr[2]',buildId='$buildId',timeStamp='$proTimeStamp',post='$post',username='$selArr[0]',phone='$selArr[1]'";
				$result = $conn -> query($sqlInsert);
			}
		break;
	}
	
	$conn -> close();
	$json = json_encode($data);
	echo $json;

?>
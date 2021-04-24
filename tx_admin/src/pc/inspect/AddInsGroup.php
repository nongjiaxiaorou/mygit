<?php
	header("Access-Control-Allow-Origin: *"); // 允许任意域名发起的跨域请求
	require("../../../conn.php");
	$flag = isset($_POST["flag"])?$_POST["flag"]:'';
	
	switch($flag){
		//新建小组
		case 'addGroup':
			$formDate = isset($_POST["formDate"])?$_POST["formDate"]:'';
			$groupLeaderValue = isset($_POST["groupLeaderValue"])?$_POST["groupLeaderValue"]:'';
			$groupLeaderValue = explode('/', $groupLeaderValue);
			$leaderName = $groupLeaderValue[0];
			$leaderPhone = $groupLeaderValue[1];
			$leaderCompany = $groupLeaderValue[2];
			$leaderId = $groupLeaderValue[3];
			$leaderAccount = $groupLeaderValue[4];
			
			$formDate = json_decode($formDate);
			$groupName = $formDate->groupName;
			$groupDeputy = $formDate->groupDeputy;
			$groupMember = $formDate->groupMember;
			
			
			$sql="SELECT * FROM tb_quality_group WHERE groupName = '$groupName' "; 
			$result = $conn->query($sql);
			if ($result->num_rows == 0) {
				//先插入小组
				$sql="INSERT INTO tb_quality_group SET groupName = '$groupName',groupCreatorId = '$leaderId'"; 
				$result = $conn->query($sql);
				$sql="SELECT * FROM tb_quality_group WHERE groupName = '$groupName' "; 
				$result = $conn->query($sql);
				$row = $result->fetch_assoc();
				if ($result->num_rows > 0) {
					$groupID = $row['id'];
					//插入组长
					$sql = "INSERT INTO tb_quality_group_member SET `creatorID` = '$leaderId',`userID` = '$leaderId',`name` = '$leaderName',`phnoeNumber` = '$leaderPhone',`company` = '$leaderCompany',`jobSign` = '0',`class` = '$leaderAccount',`groupName` = '$groupName',`groupID` = '$groupID',`groupPost` = '2' ";
					$result = $conn->query($sql);
					
					//插入副组长
					for($i=0;$i<count($groupDeputy);$i++){
						$groupDeputy[$i] = explode('/', $groupDeputy[$i]);
						$userName = $groupDeputy[$i][0];
						$userPhone = $groupDeputy[$i][1];
						$company = $groupDeputy[$i][2];
						$userId = $groupDeputy[$i][3];
						$account = $groupDeputy[$i][4];
						$sql = "INSERT INTO tb_quality_group_member SET `creatorID` = '$leaderId',`userID` = '$userId',`name` = '$userName',`phnoeNumber` = '$userPhone',`company` = '$company',`jobSign` = '0',`class` = '$account',`groupName` = '$groupName',`groupID` = '$groupID',`groupPost` = '1' ";
						$result = $conn->query($sql);
					}
					
					//插入组员
					for($i=0;$i<count($groupMember);$i++){
						$groupMember[$i] = explode('/', $groupMember[$i]);
						$userName = $groupMember[$i][0];
						$userPhone = $groupMember[$i][1];
						$company = $groupMember[$i][2];
						$userId = $groupMember[$i][3];
						$account = $groupMember[$i][4];
						$sql = "INSERT INTO tb_quality_group_member SET `creatorID` = '$leaderId',`userID` = '$userId',`name` = '$userName',`phnoeNumber` = '$userPhone',`company` = '$company',`jobSign` = '0',`class` = '$account',`groupName` = '$groupName',`groupID` = '$groupID',`groupPost` = '0' ";
						$result = $conn->query($sql);
					}
				}
			}else{
				$jsonresult = 'failed';
			}
		break;
	}
	
//	$json = json_encode($data);
//	echo $json;
	$conn->close();	
?>
<?php
	require ("../../../conn.php");
	header("Access-Control-Allow-Origin: *");
	// 允许任意域名发起的跨域请求
    $flag = isset($_POST["flag"]) ? $_POST["flag"] : '';
    date_default_timezone_set('PRC'); //东八时区 
	$data = array(
		"code"=>1,
		"msg"=>"",
		"data"=>[]
    );
    $projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
    $buildInfo = isset($_POST["buildInfo"]) ? $_POST["buildInfo"] : '';
    // $data['projectId'] = $projectId;
    // $data['buildInfo'] = $buildInfo;
    
    // $date = date();
	switch ($flag) {
        // 获取一般隐患图片
        case 'getUsualProImg' :
            $sql = "SELECT * FROM `tb_inspectaccept_violationitem` WHERE `itemType`='一般问题' AND `projectId`='$projectId' AND `buildInfo`='$buildInfo'";
            $data['sql'] = $sql;

            $result = $conn -> query($sql);
            if ($result->num_rows>0) {
                $resData = array();
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $resData[$i]['violation'] = $row['violationContent'];
                    $resData[$i]['picFile'] = $row['picFile'];
                    $i++;
                }   
                $data['data'] = $resData;             
            }
        break;
        // 获取重大隐患图片
        case 'getBigProImg' :
            $sql = "SELECT * FROM `tb_inspectaccept_violationitem` WHERE `itemType`='重大问题' AND `projectId`='$projectId' AND `buildInfo`='$buildInfo'";
            $result = $conn -> query($sql);
            echo $conn->error;
            if ($result->num_rows>0) {
                $resData = array();
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $resData[$i]['violation'] = $row['violationContent'];
                    $resData[$i]['picFile'] = $row['picFile'];
                    $i++;
                }   
                $data['data'] = $resData;             
            }
        break;
        // 获取一般逾期隐患图片
        case 'getUsualProLakeImg' :
            $today = date("Y-m-d H:i:s");
            $sql = "SELECT * FROM `tb_inspectaccept_violationitem` WHERE  `projectId`='$projectId' AND `itemType`='一般问题'AND `buildInfo`='$buildInfo' AND `endDate`<'$today'";
            $result = $conn -> query($sql);
            if ($result->num_rows>0) {
                $resData = array();
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $resData[$i]['violation'] = $row['violationContent'];
                    $resData[$i]['picFile'] = $row['picFile'];
                    $i++;
                }   
                $data['data'] = $resData;             
            }
        break;
        // 获取重大逾期隐患图片
        case 'getBigProLakeImg' :
            $today = date("Y-m-d");
            $sql = "SELECT * FROM `tb_inspectaccept_violationitem` WHERE  `projectId`='$projectId' AND `itemType`='重大问题'AND `buildInfo`='$buildInfo' AND `endDate`<'$today'";
            $result = $conn -> query($sql);
            if ($result->num_rows>0) {
                $resData = array();
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $resData[$i]['violation'] = $row['violationContent'];
                    $resData[$i]['picFile'] = $row['picFile'];
                    $i++;
                }   
                $data['data'] = $resData;             
            }
        break;
        // 获取项目合格率
        case 'getProQualifiedPecent' :
            $projectId = isset($_POST["projectId"]) ? $_POST["projectId"] : '';
            $proTimeStamp = isset($_POST["proTimeStamp"]) ? $_POST["proTimeStamp"] : '';
            $section = isset($_POST["section"]) ? $_POST["section"] : '';
            $build = isset($_POST["build"]) ? $_POST["build"] : '';
            $data['projectId'] = $projectId;
            $data['build'] = $build;
            $sql = "SELECT * FROM `tb_inspectaccept_measure` WHERE `proTimeStamp`='$proTimeStamp' AND `build`='$build' AND `measureType`='实测实量' GROUP BY inspectItem";
            $result = $conn -> query($sql);
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $inspectItem = $row['inspectItem'];
                $data['data'][$i]['inspectItem'] = $inspectItem;
                $data['data'][$i]['index'] = $i;
                $sql0 = "SELECT * FROM `tb_measure_standard` WHERE `inspectItem` = '$inspectItem'";
                $result0 = $conn -> query($sql0);                
                $j = 0;
                while ($row0 = $result0 -> fetch_assoc()) {
                    $inspectContent = $row0['inspectContent'];
                    $subItem['inspectContent'] = $inspectContent;
                    $sql1 = "SELECT COUNT(b.id) AS totalNum, b.missItem AS missItem, b.status AS status FROM `tb_inspectaccept_measure` AS a, `tb_measure_measurepoint` AS b WHERE  a.build = '$build' AND a.proTimeStamp = '$proTimeStamp' AND a.id = b.measureId AND b.measurePointName = '$inspectContent' AND b.missItem = 'false' ";
                    $sql2 = "SELECT COUNT(b.id) AS qualityNum FROM `tb_inspectaccept_measure` AS a, `tb_measure_measurepoint` AS b WHERE  a.build = '$build' AND a.proTimeStamp = '$proTimeStamp' AND a.id = b.measureId AND b.measurePointName = '$inspectContent'  AND b.finaltestStatus = '合格' AND b.missItem = 'false'";
                    $result1 = $conn -> query($sql1);
                    $result2 = $conn -> query($sql2);
                    $row1 = $result1 -> fetch_assoc();
                    $row2 = $result2 -> fetch_assoc();
                    $data['missItem'] = $row1['missItem'];
                    $data['status'] = $row1['status'];
                    if ($row1['status'] == '终测') {
                        $totalNum = $row1['totalNum'];
                        $qualityNum = $row2['qualityNum'];
                        if ($totalNum == 0 || $qualityNum == 0) {
                            $subItem['missItem'] = true;
                            // $qualifiedPersentage = '缺项'; 
                            $qualifiedPersentage = round($qualityNum / $totalNum *100, 2)  . '%';

                        } else {
                            $subItem['missItem'] = false;
                            $qualifiedPersentage = round($qualityNum / $totalNum *100, 2)  . '%';
                        }
                        // if ($row1['status'] == 'false' || '') {
                        //     $subItem['missItem'] = false;
                        //     $qualifiedPersentage = round($qualityNum / $totalNum *100, 2)  . '%';
                        // } else {
                        //     $subItem['missItem'] = true;
                        //     $qualifiedPersentage = '缺项';                           
                        // }
                        $subItem['qualityNum'] = $qualityNum;
                        $subItem['totalNum'] = $totalNum;
                        $subItem['qualifiedPersentage'] = $qualifiedPersentage;
                        $data['data'][$i]['subItem'][$j] = $subItem;
                    } 
                    $j++;
                }
                $i++;
            }
            
        break;
    }

	
	$conn -> close();
	$json = json_encode($data);
	echo $json;

?>
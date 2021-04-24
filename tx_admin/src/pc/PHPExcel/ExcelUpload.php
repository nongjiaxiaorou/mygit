<?php
	require('../../../conn.php');
	header("Access-Control-Allow-Origin: *"); // 允许所有来源访问
	header("Access-Control-Allow-Headers:Content-Type,Access-Token");
	if ($_FILES["file"]["error"] > 0)
	{
		// 判断文件上传是否成功，0即为成功
		echo "错误：: " . $_FILES["file"]["error"] . "<br>";
	}
	else
	{
//		echo "上传文件名: " . $_FILES["file"]["name"] . "<br>";
//		echo "文件类型: " . $_FILES["file"]["type"] . "<br>";
//		echo "文件大小: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
//		echo "文件临时存储的位置: " . $_FILES["file"]["tmp_name"] . "<br>";
		// 判断当期目录下的 upload 目录是否存在该文件
		// 如果没有 upload 目录，你需要创建它，upload 目录权限为 777
	//	if (file_exists("../uploadExcel/" . $_FILES["file"]["name"]))
	//	{
		//	echo $_FILES["file"]["name"] . " 文件已经存在。 ";
		//}
		//else
		//{
			$now=time();
			$endname=explode('.',$_FILES["file"]["name"]);//文件name切割
			$end=end($endname);//获取文件后缀
			$ext=strtolower($end); //转化为小写
//			$upload_file=$now.substr(trim(json_encode($_FILES["file"]["name"]),'"'),-3);//完整的文件名称
			$upload_file=$now.'.'.$ext;//完整的文件名称
			$destination="../PHPExcel-1.8/".$upload_file;//路径加名字
			move_uploaded_file($_FILES["file"]["tmp_name"] ,$destination);//移动文件
  
			require('exceltq.php');//引入读取excel内容php文件
	//	}
	}
?>
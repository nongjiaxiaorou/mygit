<?php
	header("Access-Control-Allow-Origin: *");
	$appid="__UNI__AC942BA";
	$version="1.0.0";	//app版本号
	$note="同欣质量系统";	//app描述
	$appurl="http://157.122.159.8:8085/gdty_PC/tyapp_admin/release/gdty.apk";
	$json = '{"appid":"'.$appid.'",
				"version":"'.$version.'",
				"note":"'.$note.'",
				"appurl":"'.$appurl.'"
			}';
	
	echo $json; 

?> 
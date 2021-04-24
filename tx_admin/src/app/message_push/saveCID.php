<?php
require("../../../conn.php");

$CID = $_POST["cid"];
$userId = $_POST["userId"];
$sql = "SELECT CID FROM `tb_system_user` WHERE id='$userId'";
$result = $conn->query($sql);
if($result->num_rows>0){
	$row = $result->fetch_assoc();
	if($row["CID"]!=$CID){
		$sqli = "UPDATE `tb_system_user` SET CID='$CID' WHERE id='$userId'";
		$conn->query($sqli);
	}
}
?>
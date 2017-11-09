<?php
@session_start(); 
require_once "./MAIN.php";
$Connect_Status = MAIN::CONNECT();
//error_reporting(0);
$DB = $Connect_Status["DB"];
sleep(0);
$_POST = json_decode(file_get_contents('php://input'), true);

$TYPES = isset($_POST["TYPES"]) ? $_POST["TYPES"] : "";

$arr["ERROR"] = true;
if($TYPES=="LOGIN"){
	$USERNAME = isset($_POST["USERNAME"]) ? $_POST["USERNAME"] : "";
	$PASSWORD = isset($_POST["PASSWORD"]) ? $_POST["PASSWORD"] : "";
	$PASSWORD = md5($PASSWORD);

	$Query = mysqli_query($DB,
		"SELECT user.ID,user.PIC,user.FNAME,user.LNAME,user.OFFICE,user.TEL,user.LEVEL,prefix.PREFIX
		FROM user
		LEFT JOIN prefix
		ON user.PREFIX_ID=prefix.PREFIX_ID
		WHERE user.USERNAME='{$USERNAME}' AND user.PASSWORD='{$PASSWORD}'
	");
	$Fetch = mysqli_fetch_assoc($Query);
	if($Fetch["ID"]){
		if($Fetch["LEVEL"]==99){
			$USERLEVEL = "admin";
		}
		else{
			$USERLEVEL = "user";
		}
		$fullname = $Fetch["PREFIX"].$Fetch["FNAME"].' '.$Fetch["LNAME"];
		$arr["ERROR"] = false;
		$arr["MSG"] = "Login Success";		
		$arr["type"] = "success";
		$arr["DATA"] = [
			"ID"=>$Fetch["ID"],			
			"FULLNAME"=>$fullname,
			"PIC_"=> MAIN::profileExists($Fetch["PIC"],"../../../img/profiles/"),
			"OFFICE"=>$Fetch["OFFICE"],
			"USERLEVEL"=>$USERLEVEL,
			"LEVEL"=>$Fetch["LEVEL"]
		];	
	}
	else{
		$arr["ERROR"] = true;
		$arr["MSG"] = "Username หรือ Password ไม่ถูกต้อง!";
		$arr["type"] = "error";
	}
	echo json_encode($arr);
}
else{
	$arr["ERROR"] = true;
	$arr["MSG"] = "Cannot Show Database!";
	$arr["type"] = "error";
	echo json_encode($arr);
}
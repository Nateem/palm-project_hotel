<?php 
require_once "./MAIN.php";
$Connect_Status = MAIN::CONNECT();
//error_reporting(0);
 $DB = $Connect_Status["DB"];

$_POST = json_decode(file_get_contents('php://input'), true);
//sleep(1);
$TYPES = isset($_POST["TYPES"]) ? $_POST["TYPES"] : "";
$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
$user_ID = $CURRENT_DATA["ID"];
$arr["ERROR"] = true;
$temp = MAIN::CreateTempSession(2);
if($TYPES=="AUTO_BOOKING"){
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";
	$DATERANG = isset($FORM_DATA['DATERANG']) ? $FORM_DATA['DATERANG'] : "";
	$POST_E_START = $DATERANG["startDate"];
	$POST_E_END = $DATERANG["endDate"];
	$E_START = DATE("Y-m-d 12:00:00",strtotime($POST_E_START));
	$E_END =  DATE("Y-m-d 11:59:00",strtotime($POST_E_END));
	$E_BETWEEN = (int) (((strtotime($E_END)-strtotime($E_START))/60)/60)/24;

	$ROOM_TYPE = isset($FORM_DATA['ROOM_TYPE']) ? $FORM_DATA['ROOM_TYPE'] : "";
	$NUM_ROOM = isset($FORM_DATA['NUM_ROOM']) ? $FORM_DATA['NUM_ROOM'] : "";

	try {
		
	} catch (Exception $e) {
		
	}

	echo json_encode($arr);
}
else if($TYPES=="SELECT_room_type"){
	try {
		$Query = mysqli_query($DB,
			"SELECT *
			FROM room_type
			ORDER BY PRICE ASC
			");
		$DATA = [];
		while($Fetch = mysqli_fetch_assoc($Query)){
			$DATA[]=$Fetch;
		}
		$arr["ERROR"] = false;
		$arr["MSG"] = "Select room_type Success.";
		$arr["DATA"] = $DATA;
		$arr["TYPE"] = "success";	
	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "MySql Error!";
		$arr["TYPE"] = "error";	
	}	
	echo json_encode($arr);
}
else if($TYPES=="SELECT_room_type_WHERE"){
	$ID = isset($_POST["ID"]) ? $_POST["ID"] : "";
	try {
		$Query = mysqli_query($DB,
			"SELECT *
			FROM room_type
			WHERE ID = '{$ID}'
			");

		$Fetch = mysqli_fetch_assoc($Query);
		
		$arr["ERROR"] = false;
		$arr["MSG"] = "Select room_type Success.";
		$arr["DATA"] = $Fetch;
		$arr["TYPE"] = "success";	
	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "MySql Error!";
		$arr["TYPE"] = "error";	
	}	
	echo json_encode($arr);
}
else{
	$arr["ERROR"] = true;
	$arr["MSG"] = "Cannot Show Database!";
	$arr["TYPE"] = "error";	
	echo json_encode($arr);
}
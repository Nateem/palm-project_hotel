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
if($TYPES=="checkOutAfterCheckIn"){
	$BILLCODE = isset($_POST["BILLCODE"]) ? $_POST["BILLCODE"] : "";
	try {
		mysqli_query($DB,
			"UPDATE events_detail
			SET STATUS='2',CHK_OUT='1',CHK_OUT_DATETIME=NOW()
			WHERE BILLCODE='{$BILLCODE}'
			");
		mysqli_query($DB,
			"UPDATE events
			SET UPDATED=NOW()
			WHERE BILLCODE='{$BILLCODE}'
			");
		$arr["ERROR"] = false;
		$arr["DATA"] = $BILLCODE;
		$arr["MSG"] = "Check-Out Success.";
		$arr["TYPE"] = "success";
	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "Mysql Error!";
		$arr["TYPE"] = "error";	
	} 	
 	echo json_encode($arr);
}
else if($TYPES=="UpdateBeforCheckout"){
	$BILLCODE = isset($_POST["BILLCODE"]) ? $_POST["BILLCODE"] : "";
	$PAY_TOTAL = isset($_POST["PAY_TOTAL"]) ? $_POST["PAY_TOTAL"] : "";
	$SUMTOTAL = isset($_POST["SUMTOTAL"]) ? $_POST["SUMTOTAL"] : "";
	$OWE = isset($_POST["OWE"]) ? $_POST["OWE"] : "";
	$CASH_CHANGE = isset($_POST["CASH_CHANGE"]) ? $_POST["CASH_CHANGE"] : "";
	$OTHER_PRICE = isset($_POST["OTHER_PRICE"]) ? $_POST["OTHER_PRICE"] : "";
	$CURDATE = isset($_POST["TYPES"]) ? $_POST["TYPES"] : "";
	try {
		mysqli_query($DB,
			"UPDATE events
			SET PAY='{$PAY_TOTAL}',PRICE_SUM_TOTAL='{$SUMTOTAL}',OWE='{$OWE}',CASH_CHANGE='{$CASH_CHANGE}',OTHER_PRICE='{$OTHER_PRICE}'
			WHERE BILLCODE='{$BILLCODE}'
			");
		$arr["ERROR"] = false;
		$arr["MSG"] = "Update Success.";
		$arr["TYPE"] = "success";
	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "Mysql Error!";
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
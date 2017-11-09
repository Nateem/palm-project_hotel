<?php 
require_once "./MAIN.php";
$Connect_Status = MAIN::CONNECT();
//error_reporting(0);
 $DB = $Connect_Status["DB"];

$_POST = json_decode(file_get_contents('php://input'), true);

$TYPES = isset($_POST["TYPES"]) ? $_POST["TYPES"] : "";
$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
$user_ID = $CURRENT_DATA["ID"];
$arr["ERROR"] = true;
if($TYPES=="SELECT_checkout"){
	try {
		$Query = mysqli_query($DB,
			"SELECT events.ID,events.BILLCODE,events.E_START,events.E_END,events.customer_ID,events.CHECKOUT,events.UPDATED,events.CREATED
			FROM events 			
			ORDER BY events.E_START DESC
			");
		$DATA = [];
		$n=0;
		while ($Fetch = mysqli_fetch_assoc($Query)) {
			$customer_ID = $Fetch['customer_ID'];
			$Query1 = mysqli_query($DB,
				"SELECT customer.FNAME,customer.LNAME,prefix.PREFIX
				FROM customer
				LEFT JOIN prefix
				ON customer.PREFIX_ID=prefix.PREFIX_ID
				WHERE customer.ID='{$customer_ID}'				
				");
			$Fetch1 = mysqli_fetch_assoc($Query1);

			$ID = $Fetch['ID'];
			$E_START = $Fetch['E_START'];
			$E_END = $Fetch['E_END'];
			$FULLNAME = $Fetch1['PREFIX'].$Fetch1['FNAME'].' '.$Fetch1['LNAME'];
			$CREATED = $Fetch['CREATED'];

			$DATA[]=$Fetch;
			$DATA[$n]+=[
				"E_START"=>MAIN::formatDATE($E_START),
				"E_END"=>MAIN::formatDATE($E_END),
				"FULLNAME"=>$FULLNAME,
				"CREATED"=>$CREATED,
			];
			$n++;
		}
		$arr["ERROR"] = false;
		$arr["MSG"] = "Select Success.";
		$arr["DATA"] = $DATA;
		$arr["TYPE"] = "success";

	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "Mysql Error!";
		$arr["TYPE"] = "error";
		$arr["Exception"]=$e;
	}
	echo json_encode($arr);
}
else if($TYPES=="SELECT_checkout_detail"){
	$ID = isset($_POST["ID"]) ? $_POST["ID"] : "";
	try {
		$Query = mysqli_query($DB,
			"SELECT events_detail.BILLCODE,events_detail.room_data_CODE,events_detail.PRICE,events_detail.STATUS,events_detail.CHK_IN,events_detail.CHK_IN_DATETIME,events_detail.CHK_OUT,events_detail.CHK_OUT_DATETIME
			FROM events
			LEFT JOIN events_detail
			ON events.BILLCODE=events_detail.BILLCODE
			WHERE events.ID='{$ID}'
			");
		$DATA = [];
		$n=0;
		while ($Fetch=mysqli_fetch_assoc($Query)) {
			$room_data_CODE = $Fetch["room_data_CODE"];
			$Query1 = mysqli_query($DB,
				"SELECT room_data.NAME AS ROOM_NAME,room_type.NAME_TYPE
				FROM room_data
				LEFT JOIN room_type
				ON room_data.room_type_ID=room_type.ID
				WHERE room_data.CODE='{$room_data_CODE}'
				");
			$Fetch1=mysqli_fetch_assoc($Query1);
			$ROOM_NAME=$Fetch1["ROOM_NAME"];
			$NAME_TYPE=$Fetch1["NAME_TYPE"];
			$DATA[]=$Fetch;
			$DATA[$n]+=[
				"ROOM_NAME"=>$ROOM_NAME,
				"NAME_TYPE"=>$NAME_TYPE,
			];
			$n++;
		}
		$Query2 = mysqli_query($DB,
			"SELECT events.BILLCODE,events.customer_ID,events.E_START,events.E_END,events.E_BETWEEN,events.BEDPLUS1,events.BEDPLUS2,events.PRICE_BEDPLUS,events.OTHER_PRICE,events.PRICE_TOTAL,events.DISCOUNT,events.DEPOSIT,events.PAY,events.OWE,events.CASH_CHANGE,events.NOTE,events.CREATED,customer.FNAME,customer.LNAME
			FROM events
			LEFT JOIN customer
			ON events.customer_ID=customer.ID
			WHERE events.ID='{$ID}'
			");
		$Fetch2 = mysqli_fetch_assoc($Query2);

		$arr["ERROR"] = false;
		$arr["MSG"] = "Select Success";
		$arr["DATA"] = $DATA;
		$arr["CHECKOUT"] = $Fetch2;		
		$arr["TYPE"] = "success";
		
	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "Mysql Error!";
		$arr["TYPE"] = "error";
		$arr["Exception"]=$e;
	}
	echo json_encode($arr);
}
else if($TYPES=="DELETE_checkout"){
	$ID = isset($_POST["ID"]) ? $_POST["ID"] : "";
	try {
		$Query = mysqli_query($DB,
			"SELECT BILLCODE
			FROM events
			WHERE ID = '{$ID}'
			");
		$Fetch = mysqli_fetch_assoc($Query);
		if($Fetch){
			$BILLCODE = $Fetch["BILLCODE"];
			mysqli_query($DB,
				"DELETE 
				FROM events
				WHERE BILLCODE = '{$BILLCODE}'
				");
			mysqli_query($DB,
				"DELETE 
				FROM events_detail
				WHERE BILLCODE = '{$BILLCODE}'
				");
			$arr["ERROR"] = false;
			$arr["MSG"] = "ยกเลิกรายการนี้สำเร็จ";
			$arr["TYPE"] = "success";
		}
		else{
			$arr["ERROR"] = true;
			$arr["MSG"] = "รายการนี้ไม่มีอยู่ หรือถูกลบไปแล้ว!";
			$arr["TYPE"] = "warning";
		}
	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "Mysql Error!";
		$arr["TYPE"] = "error";
		$arr["Exception"]=$e;
	}
	echo json_encode($arr);
}
else{
	$arr["ERROR"] = true;
	$arr["MSG"] = "Cannot Show Database!";
	$arr["TYPE"] = "error";
	echo json_encode($arr);
}
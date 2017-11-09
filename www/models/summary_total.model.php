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

if($TYPES=="DATA_SUMMARY"){
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";
	$ORDERS_TYPE = isset($_POST["ORDERS_TYPE"]) ? $_POST["ORDERS_TYPE"] : "";
	$E_START = isset($FORM_DATA["E_START"]) ? $FORM_DATA["E_START"] : "";
	$E_END = isset($FORM_DATA["E_END"]) ? $FORM_DATA["E_END"] : "";

	$CONV_ORTYP = implode("','", $ORDERS_TYPE);
	try {
		$DATA = [];
		$n=0;
		$Query = mysqli_query($DB,
			"SELECT events.BILLCODE,events.E_START,events.E_END,events.E_BETWEEN,events.PRICE_BEDPLUS,events.DISCOUNT,events.PRICE_TOTAL+events.OTHER_PRICE AS PRICE_TOTAL,events.OTHER_PRICE,events.PRICE_SUM_TOTAL,events.CREATED,orders_type.NAME AS orders_type_NAME,events.customer_ID
			FROM events
			LEFT JOIN orders_type
			ON events.orders_type_ID=orders_type.ID
			WHERE (DATE(events.CREATED) BETWEEN DATE('{$E_START}') AND DATE('{$E_END}'))
			AND events.orders_type_ID IN ('{$CONV_ORTYP}')
			ORDER BY events.CREATED DESC
			");
		while($Fetch=mysqli_fetch_assoc($Query)){
			$DATA[]=$Fetch;
			$BILLCODEloop = $Fetch["BILLCODE"];
			$customer_ID = $Fetch["customer_ID"];
			$Query2 = mysqli_query($DB,
				"SELECT customer.FNAME,customer.LNAME,prefix.PREFIX
				FROM customer
				LEFT JOIN prefix
				ON customer.PREFIX_ID=prefix.PREFIX_ID
				WHERE customer.ID='{$customer_ID}'
				");
			$Fetch2=mysqli_fetch_assoc($Query2);
			$CUSTOMER_NAME = $Fetch2["PREFIX"].$Fetch2["FNAME"]." ".$Fetch2["LNAME"];
			$Query1 = mysqli_query($DB,
				"SELECT events_detail.*
				FROM events_detail
				WHERE events_detail.BILLCODE='{$BILLCODEloop}'
				ORDER BY events_detail.ID ASC
				");
			
			$i=0;
			$DATA2 = [];
			while ($Fetch1=mysqli_fetch_assoc($Query1)) {
				$DATA2[]=$Fetch1;
				$i++;
			}
			$DATA[$n]+=[
				"events_detail"=>$DATA2,
				"CUSTOMER_NAME"=>$CUSTOMER_NAME,
			];
			$n++;
		}

		$arr["ERROR"] = false;
		$arr["MSG"] = "success";
		$arr["DATA"] = $DATA;	
		$arr["TYPE"] = "success";	

	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "My Sql Error.";
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
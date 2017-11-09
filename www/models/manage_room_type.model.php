<?php 
require_once "./MAIN.php";
$Connect_Status = MAIN::CONNECT();
//error_reporting(0);
 $DB = $Connect_Status["DB"];

$_POST = json_decode(file_get_contents('php://input'), true);

$TYPES = isset($_POST["TYPES"]) ? $_POST["TYPES"] : "";

$arr["ERROR"] = true;
if($TYPES=="SELECT_room_type"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";

	$Query = mysqli_query($DB,
		"SELECT room_type.*
		FROM room_type
		ORDER BY room_type.ID ASC
		");
	$DATA = [];
	while ($Fetch=mysqli_fetch_assoc($Query)) {
		$DATA[]=$Fetch;		
	}
	$arr["ERROR"] = false;
	$arr["MSG"] = "Select Success";
	$arr["DATA"] = $DATA;
	echo json_encode($arr);
}
else if($TYPES=="SELECT_room_type_where"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$ID = isset($_POST["ID"]) ? $_POST["ID"] : "";
	$Query = mysqli_query($DB,
		"SELECT room_type.*
		FROM room_type
		WHERE ID='{$ID}'
		");

	$Fetch=mysqli_fetch_assoc($Query);

	$arr["ERROR"] = false;
	$arr["MSG"] = "Select Success";
	$arr["DATA"] = $Fetch;
	echo json_encode($arr);
}
else if($TYPES=="INSERT_room_type"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";
	$user_ID = $CURRENT_DATA["ID"];

	$NAME_TYPE = $FORM_DATA["NAME_TYPE"];
	$SUPPORT = $FORM_DATA["SUPPORT"];
	$BED_SIZE = $FORM_DATA["BED_SIZE"];
	$PRICE = $FORM_DATA["PRICE"];

	mysqli_query($DB,
		"INSERT INTO room_type(NAME_TYPE,SUPPORT,BED_SIZE,PRICE)
		VALUES ('{$NAME_TYPE}','{$SUPPORT}','{$BED_SIZE}','{$PRICE}')
		");
	$arr["ERROR"] = false;
	$arr["MSG"] = "เพิ่มรายการสำเร็จ..";
	$arr["TYPE"] = "success";
	echo json_encode($arr);
}
else if($TYPES=="UPDATE_room_type"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";
	$user_ID = $CURRENT_DATA["ID"];
	$ID = $FORM_DATA["ID"];
	$NAME_TYPE = $FORM_DATA["NAME_TYPE"];
	$SUPPORT = $FORM_DATA["SUPPORT"];
	$BED_SIZE = $FORM_DATA["BED_SIZE"];
	$PRICE = $FORM_DATA["PRICE"];

	mysqli_query($DB,
		"UPDATE room_type
		SET NAME_TYPE='{$NAME_TYPE}',SUPPORT='{$SUPPORT}',BED_SIZE='{$BED_SIZE}',PRICE='{$PRICE}'
		WHERE ID='{$ID}'
		");

	$arr["ERROR"] = false;
	$arr["MSG"] = "แก้ไขรายการสำเร็จ..";
	$arr["TYPE"] = "success";
	echo json_encode($arr);
}
else if($TYPES=="DELETE_room_type"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";

	$user_ID = $CURRENT_DATA["ID"];

	$ID = isset($_POST["ID"]) ? $_POST["ID"] : "";
	$Query = mysqli_query($DB,
		"SELECT room_type.NAME_TYPE
		FROM room_type
		WHERE ID='{$ID}'
		");
	$Fetch = mysqli_fetch_assoc($Query);
	if($Fetch){
		mysqli_query($DB,
			"DELETE FROM room_type
			WHERE ID='{$ID}'
			");

		$arr["ERROR"] = false;
		$arr["MSG"] = "ลบรายการสำเร็จ..";
		$arr["TYPE"] = "success";
	}
	else{
		$arr["ERROR"] = true;
		$arr["MSG"] = "ไม่พบรายการที่เลือก..";
		$arr["TYPE"] = "error";
	}
	
	echo json_encode($arr);
}
else{
	$arr["ERROR"] = true;
	$arr["MSG"] = "Cannot Show Database!";
	echo json_encode($arr);
}
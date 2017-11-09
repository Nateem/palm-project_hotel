<?php 
require_once "./MAIN.php";
$Connect_Status = MAIN::CONNECT();
//error_reporting(0);
 $DB = $Connect_Status["DB"];

$_POST = json_decode(file_get_contents('php://input'), true);

$TYPES = isset($_POST["TYPES"]) ? $_POST["TYPES"] : "";

$arr["ERROR"] = true;
if($TYPES=="SELECT_room_data"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";

	$Query = mysqli_query($DB,
		"SELECT room_data.*,room_type.NAME_TYPE
		FROM room_data
		LEFT JOIN room_type
		ON room_data.room_type_ID=room_type.ID
		ORDER BY room_data.ID ASC
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
else if($TYPES=="SELECT_room_data_where"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$ID = isset($_POST["ID"]) ? $_POST["ID"] : "";
	$Query = mysqli_query($DB,
		"SELECT room_data.*
		FROM room_data
		WHERE ID='{$ID}'
		");

	$Fetch=mysqli_fetch_assoc($Query);

	$arr["ERROR"] = false;
	$arr["MSG"] = "Select Success";
	$arr["DATA"] = $Fetch;
	echo json_encode($arr);
}
else if($TYPES=="INSERT_room_data"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";
	$user_ID = $CURRENT_DATA["ID"];

	$CODE = $FORM_DATA["CODE"];
	$NAME = $FORM_DATA["NAME"];
	$room_type_ID = $FORM_DATA["room_type_ID"];
	
	mysqli_query($DB,
		"INSERT INTO room_data(CODE,NAME,room_type_ID)
		VALUES ('{$CODE}','{$NAME}','{$room_type_ID}')
		");
	$arr["ERROR"] = false;
	$arr["MSG"] = "เพิ่มรายการสำเร็จ..";
	$arr["TYPE"] = "success";
	echo json_encode($arr);
}
else if($TYPES=="UPDATE_room_data"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";
	$user_ID = $CURRENT_DATA["ID"];
	$ID = $FORM_DATA["ID"];
	$CODE = $FORM_DATA["CODE"];
	$NAME = $FORM_DATA["NAME"];
	$room_type_ID = $FORM_DATA["room_type_ID"];


	mysqli_query($DB,
		"UPDATE room_data
		SET CODE='{$CODE}',NAME='{$NAME}',room_type_ID='{$room_type_ID}'
		WHERE ID='{$ID}'
		");

	$arr["ERROR"] = false;
	$arr["MSG"] = "แก้ไขรายการสำเร็จ..";
	$arr["TYPE"] = "success";
	echo json_encode($arr);
}
else if($TYPES=="DELETE_room_data"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";

	$user_ID = $CURRENT_DATA["ID"];

	$ID = isset($_POST["ID"]) ? $_POST["ID"] : "";
	$Query = mysqli_query($DB,
		"SELECT room_data.NAME
		FROM room_data
		WHERE ID='{$ID}'
		");
	$Fetch = mysqli_fetch_assoc($Query);
	if($Fetch){
		mysqli_query($DB,
			"DELETE FROM room_data
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
else if($TYPES=="SELECT_room_type"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	try {
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
	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "MySql Error!";
	}	
	echo json_encode($arr);
}
else{
	$arr["ERROR"] = true;
	$arr["MSG"] = "Cannot Show Database!";
	echo json_encode($arr);
}
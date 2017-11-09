<?php 
require_once "./MAIN.php";
$Connect_Status = MAIN::CONNECT();
//error_reporting(0);
 $DB = $Connect_Status["DB"];

$_POST = json_decode(file_get_contents('php://input'), true);

$TYPES = isset($_POST["TYPES"]) ? $_POST["TYPES"] : "";

$arr["ERROR"] = true;
if($TYPES=="SELECT_customer"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";

	$Query = mysqli_query($DB,
		"SELECT customer.*,prefix.PREFIX
		FROM customer
		LEFT JOIN prefix
		ON customer.PREFIX_ID=prefix.PREFIX_ID
		ORDER BY customer.ID ASC
		");
	$DATA = [];
	$n=0;
	while ($Fetch=mysqli_fetch_assoc($Query)) {
		$DATA[]=$Fetch;
		$DATA[$n]+=[
			"FULLNAME"=>$Fetch["PREFIX"].$Fetch["FNAME"]." ".$Fetch["LNAME"],
		];		
	$n++;
	}
	$arr["ERROR"] = false;
	$arr["MSG"] = "Select Success";
	$arr["DATA"] = $DATA;
	echo json_encode($arr);
}
else if($TYPES=="SELECT_customer_where"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$ID = isset($_POST["ID"]) ? $_POST["ID"] : "";
	$Query = mysqli_query($DB,
		"SELECT customer.*
		FROM customer
		WHERE ID='{$ID}'
		");

	$Fetch=mysqli_fetch_assoc($Query);

	$arr["ERROR"] = false;
	$arr["MSG"] = "Select Success";
	$arr["DATA"] = $Fetch;
	echo json_encode($arr);
}
else if($TYPES=="INSERT_customer"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";
	$user_ID = $CURRENT_DATA["ID"];

	$ID13 = $FORM_DATA["ID13"];
	$PREFIX_ID = $FORM_DATA["PREFIX_ID"];
	$FNAME = $FORM_DATA["FNAME"];
	$LNAME = $FORM_DATA["LNAME"];
	$ADDRESS = $FORM_DATA["ADDRESS"];
	$PHONE = $FORM_DATA["PHONE"];
	$EMAIL = $FORM_DATA["EMAIL"];
	try {
		$Query=mysqli_query($DB,
			"SELECT customer.ID13
			FROM customer
			WHERE customer.ID13='{$ID13}'
			");
		$Fetch=mysqli_fetch_assoc($Query);
		if(!$Fetch){
			mysqli_query($DB,
				"INSERT INTO customer(ID13,PREFIX_ID,FNAME,LNAME,ADDRESS,PHONE,EMAIL,CREATED)
				VALUES ('{$ID13}','{$PREFIX_ID}','{$FNAME}','{$LNAME}','{$ADDRESS}','{$PHONE}','{$EMAIL}',NOW())
				");
			$arr["ERROR"] = false;
			$arr["MSG"] = "เพิ่มรายการสำเร็จ..";
			$arr["TYPE"] = "success";
		}
		else{
			$arr["ERROR"] = true;
			$arr["MSG"] = "รหัสประจำตัวนี้มีในระบบแล้ว..";
			$arr["TYPE"] = "error";
		}
	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "Mysql Error!";
		$arr["TYPE"] = "error";
	}

	
	echo json_encode($arr);
}
else if($TYPES=="UPDATE_customer"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";
	$user_ID = $CURRENT_DATA["ID"];
	$ID = $FORM_DATA["ID"];
	
	$ID13 = $FORM_DATA["ID13"];
	$PREFIX_ID = $FORM_DATA["PREFIX_ID"];
	$FNAME = $FORM_DATA["FNAME"];
	$LNAME = $FORM_DATA["LNAME"];
	$ADDRESS = $FORM_DATA["ADDRESS"];
	$PHONE = $FORM_DATA["PHONE"];
	$EMAIL = $FORM_DATA["EMAIL"];

	mysqli_query($DB,
		"UPDATE customer
		SET ID13='{$ID13}',PREFIX_ID='{$PREFIX_ID}',FNAME='{$FNAME}',LNAME='{$LNAME}',ADDRESS='{$ADDRESS}',PHONE='{$PHONE}',EMAIL='{$EMAIL}'
		WHERE ID='{$ID}'
		");

	$arr["ERROR"] = false;
	$arr["MSG"] = "แก้ไขรายการสำเร็จ..";
	$arr["TYPE"] = "success";
	echo json_encode($arr);
}
else if($TYPES=="DELETE_customer"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";

	$user_ID = $CURRENT_DATA["ID"];

	$ID = isset($_POST["ID"]) ? $_POST["ID"] : "";
	$Query = mysqli_query($DB,
		"SELECT customer.ID13
		FROM customer
		WHERE ID='{$ID}'
		");
	$Fetch = mysqli_fetch_assoc($Query);
	if($Fetch){
		mysqli_query($DB,
			"DELETE FROM customer
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
else if($TYPES=="SELECT_prefix"){
	try {
		$Query=mysqli_query($DB,
			"SELECT *
			FROM prefix
			ORDER BY PREFIX_ID ASC
			");
		$DATA=[];
		while ($Fetch=mysqli_fetch_assoc($Query)) {
			$DATA[]=$Fetch;
		}
		$arr["ERROR"] = false;
		$arr["MSG"] = "Select Success";
		$arr["DATA"] = $DATA;
		
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
	echo json_encode($arr);
}
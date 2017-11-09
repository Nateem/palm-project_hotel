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
$orders_type_ID = isset($_POST['orders_type_ID']) ? $_POST['orders_type_ID'] : "";
switch ($orders_type_ID) {
	case '1':
		$codeEN = "BK";
		break;
	case '2':
		$codeEN = "EV";
		break;
	case '3':
		$codeEN = "NA";
		break;
}

$QueryChkBill = mysqli_query($DB,
	"SELECT events.BILLCODE
	FROM events
	WHERE BILLCODE LIKE '{$codeEN}%'
	ORDER BY events.BILLCODE DESC
	LIMIT 1
	");
$FetchChkBill = mysqli_fetch_assoc($QueryChkBill);
$LAST_BILLCODE = $FetchChkBill["BILLCODE"];
$NEXT_BILLCODE = MAIN::BILLCODE_GEN($LAST_BILLCODE,$codeEN,7);
if($TYPES=="SELECT_not_available"){

  	$FORM_DATA = isset($_POST['FORM_DATA']) ? $_POST['FORM_DATA'] : "";
  	$orders_type_ID = isset($_POST['orders_type_ID']) ? $_POST['orders_type_ID'] : "";
	$DATERANG = isset($FORM_DATA['DATERANG']) ? $FORM_DATA['DATERANG'] : "";
	//echo $DATE_IN."<br>",$DATE_OUT."<br>";
	$POST_E_START = $DATERANG["startDate"];
	$POST_E_END = $DATERANG["endDate"];
	$E_START = DATE("Y-m-d 12:00:00",strtotime($POST_E_START));
	$E_END =  DATE("Y-m-d 11:59:00",strtotime($POST_E_END));
	//$E_END = date("Y-m-d",strtotime("-1 day",strtotime($E_END)));
	//echo $E_START,"<br>",$E_END,"<br><hr>";
	$E_BETWEEN = (int) (((strtotime($E_END)-strtotime($E_START))/60)/60)/24;

	

	$NOW = date('Y-m-d H:i:s');
	try {
		mysqli_query($DB,"DROP TABLE IF EXISTS $temp[1]");
  		mysqli_query($DB,"CREATE TABLE $temp[1] LIKE booking");
  		mysqli_query($DB,"DROP TABLE IF EXISTS $temp[2]");
  		mysqli_query($DB,"CREATE TABLE $temp[2] LIKE booking_detail");

  		mysqli_query($DB,
				"INSERT INTO $temp[1](BILLCODE,orders_type_ID,E_START,E_END,E_BETWEEN,CREATED)
				VALUES ('{$NEXT_BILLCODE}','{$orders_type_ID}','{$E_START}','{$E_END}','{$E_BETWEEN}','{$NOW}')
				");

	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "My Sql Create Temp Error.";
		$arr["TYPE"] = "error";	
		echo json_encode($arr);
		exit();
	}
	
	
	$room_checkin = array();
	$room_booking = array();
	$room_repair = array();
	$n=0;
	while($E_START <= $E_END){
		
		$chkBookingS=mysqli_query($DB,
			"SELECT DISTINCT booking_detail.room_data_CODE,booking.orders_type_ID
			FROM booking_detail
			LEFT JOIN booking
			ON booking_detail.BILLCODE=booking.BILLCODE
			WHERE '{$E_START}' BETWEEN booking.E_START AND booking.E_END
			");
		$bs=0;
		while($chkBooking=mysqli_fetch_assoc($chkBookingS)){
			$orders_type_ID=$chkBooking['orders_type_ID'];
			switch ($orders_type_ID) {
				case '1':
					$room_data_CODE_booking=$chkBooking['room_data_CODE'];			
					//echo $room_data_CODE_booking."<br>รอบใน = ".$bs."<br>รอบนอก = ".$n."<br><hr>";
					array_push($room_booking, $room_data_CODE_booking);//เพิ่มห้องที่ไม่ว่างเข้า Array
					break;
				case '2':
					$room_data_CODE_checkin=$chkBooking['room_data_CODE'];
					array_push($room_checkin, $room_data_CODE_checkin);
					break;
				case '3':
					$room_data_CODE_repair=$chkBooking['room_data_CODE'];					
					array_push($room_repair, $room_data_CODE_repair);
					break;				
			}
		$bs++;
		}		
		//echo $E_START."<u>ยังไม่บวก</u><br>";
		/*=====================================================================*/
		$E_START = date("Y-m-d",strtotime("+1 day",strtotime($E_START)));
		//$res=mysqli_query($DB,"SELECT DATE_ADD('{$E_START}',INTERVAL + 1 DAY)AS E_START");//เรียกใช้ MySql เพื่อใช้ฟังค์ชั่น บวกเพิ่ม 1 วัน
		//$ax=mysqli_fetch_assoc($res);
		//$E_START = $ax['E_START'];//ประกาศตัวแปร $E_START ให้เป็นวันที่บวกเพิ่มเข้าไป 1 วันแล้ว เพื่อให้วนลูป while จนถึง $E_END
		/*=====================================================================*/
		//echo $E_START."<u>บวกแล้ว</u><br>";
		/*if($n==$E_BETWEEN){
			for ($i=0; $i < count($room_checkin); $i++) { 
				echo " <br> ห้องเต็มวันที่",$room_checkin[$i];
				echo "<br> <hr>";
			}					
		}*/
		

	$n++;
	}

	$room_checkin = array_unique($room_checkin);
	$room_repair = array_unique($room_repair);
	$room_booking = array_unique($room_booking);//array_unique เอาห้องที่ซ้ำกันออก
	//print_r($room_booking);
	//$rooms_in = implode("','",$room_booking);//เพื่อเอาตัวแปลแบบ array ใส่ลง Sql ได้ง่าย
	/*$Sql = "SELECT room_data.CODE AS NAME_CODE,room_data.NAME AS NAME_ROOM,room_type.NAME_TYPE,room_type.ID AS ROOM_TYPE_ID,room_type.SUPPORT,room_type.BED_SIZE,room_type.PRICE
		FROM room_data
		LEFT JOIN room_type
		ON room_data.room_type_ID = room_type.ID
		WHERE room_data.CODE 
		NOT IN ('{$rooms_in}')
		";*/
	$Sql = "SELECT room_type.NAME_TYPE,room_type.ID AS ROOM_TYPE_ID,room_type.SUPPORT,room_type.BED_SIZE,room_type.PRICE
			FROM room_type
			ORDER BY room_type.ID ASC			
	";
	//echo $Sql."<hr>";
	$roomS=mysqli_query($DB,$Sql);
	$n=0;
	$DATA = [];
	while ($room=mysqli_fetch_assoc($roomS)) {
		$DATA[]=$room;
		$ROOM_TYPE_ID = $room['ROOM_TYPE_ID'];

		$ssS=mysqli_query($DB,
			"SELECT room_data.ID,room_data.CODE,room_data.NAME
			FROM room_data
			WHERE room_data.room_type_ID='{$ROOM_TYPE_ID}'
			");
		
		$DATA_ROOM[$n] = [];
		$s=0;
		while($ss=mysqli_fetch_assoc($ssS)){

			$DATA_ROOM[$n][]=$ss;
			$NAME_CODE = $ss['CODE'];
			$NAME_ROOM = $ss['NAME'];
			$NAME_TYPE = $room['NAME_TYPE'];
			$SUPPORT = $room['SUPPORT'];
			$BED_SIZE = $room['BED_SIZE'];
			$PRICE = $room['PRICE'];
			$DATA_ROOM[$n][$s] += [
				"NAME_CODE" => $NAME_CODE,
				"NAME_ROOM" => $NAME_ROOM,
				"NAME_TYPE" => $NAME_TYPE,
				"SUPPORT" => $SUPPORT,
				"BED_SIZE" => $BED_SIZE,
				"PRICE" => $PRICE,
			];

			$found_booking = array_search($NAME_CODE, $room_booking);
			$found_checkin = array_search($NAME_CODE, $room_checkin);//ค้นหาห้องที่ไม่ว่างในอาเรย์ที่ตรวจสอบจากวันที่ข้างต้น
			$found_repair = array_search($NAME_CODE, $room_repair);
			if($found_repair !== false){ //ถ้ามีห้องในอาร์เรย์
				$DATA_ROOM[$n][$s] += [
					"ROOM_STATUS_CODE" => 4,
					"ROOM_STATUS" => "ไม่พร้อมให้บริการ",
				];
			}
			else if($found_booking !== false){ 
				$DATA_ROOM[$n][$s] += [
					"ROOM_STATUS_CODE" => 3,
					"ROOM_STATUS" => "จองแล้ว",
				];
			}
			else if($found_checkin !== false){
				$DATA_ROOM[$n][$s] += [
					"ROOM_STATUS_CODE" => 2,
					"ROOM_STATUS" => "มีผู้เข้าพัก",
				];
			}	
			else{
				$DATA_ROOM[$n][$s]+= [
					"ROOM_STATUS_CODE" => 1,
					"ROOM_STATUS" => "ห้องว่าง",
				];
			}
		$s++;
		}

		
		
		$n++;
	}
	$arr["ERROR"] = false;
	$arr["MSG"] = "แสดงห้องที่สามารถจองได้.";
	$arr["DATA"] = $DATA_ROOM;
	$arr["DATA_HEAD"] = $DATA;
	$arr["BILLCODE"] = $NEXT_BILLCODE;
	$arr["TYPE"] = "success";	
	echo json_encode($arr);
}
else if($TYPES=="SELECT_bed_plus"){
	try {
		$Query = mysqli_query($DB,
			"SELECT * 
			FROM bed_plus
			ORDER BY ID ASC
			");
		$DATA = [];
		while ($Fetch=mysqli_fetch_assoc($Query)) {
			$DATA[]=$Fetch;
		}
		$arr["ERROR"] = false;
		$arr["MSG"] = "Load Success.";
		$arr["DATA"] = $DATA;
		$arr["TYPE"] = "success";
		
	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "MySql Error.";
		$arr["TYPE"] = "error";
	}
	echo json_encode($arr);
}
else if($TYPES=="INSERT_TEMP"){
	$ROOM_CODE = isset($_POST['ROOM_CODE']) ? $_POST['ROOM_CODE'] : "";
	$FORM_DATA = isset($_POST['FORM_DATA']) ? $_POST['FORM_DATA'] : "";
	$DATERANG = isset($FORM_DATA['DATERANG']) ? $FORM_DATA['DATERANG'] : "";
	$E_START = date("Y-m-d",strtotime($DATERANG["startDate"]))." "."12:00:00";
	$E_END = date("Y-m-d",strtotime($DATERANG["endDate"]))." "."11:59:00";
	$E_BETWEEN = (((strtotime($E_END)-strtotime($E_START))/60)/60)/24;
	$E_BETWEEN = number_format($E_BETWEEN,0);
	$NOW = date('Y-m-d H:i:s');


	$chks=mysqli_query($DB,
		"SELECT $temp[2].room_data_CODE
		FROM $temp[2] 
		WHERE $temp[2].room_data_CODE='{$ROOM_CODE}'");
	$chk=mysqli_fetch_assoc($chks);


	$arr=array();
	
	if($chk){
		try {	
				$arr["ERROR"] = false;
				$arr["MSG"] = "เลือกห้องนี้แล้ว";
				$arr["TYPE"] = "warning";
		} catch (Exception $e) {
			$arr["ERROR"] = true;
			$arr["MSG"] = "MySql Error!";	
			$arr["TYPE"] = "error";		
		}
		
	}
	else{
		try {
			
			mysqli_query($DB,
				"INSERT INTO $temp[2](BILLCODE,room_data_CODE)
				VALUES ('{$NEXT_BILLCODE}','{$ROOM_CODE}')
				");			
			$arr["ERROR"] = false;
			$arr["MSG"] = "เพิ่มในรายการแล้ว";
			$arr["TYPE"] = "success";
		} catch (Exception $e) {
			$arr["ERROR"] = true;
			$arr["MSG"] = "MySql Error!";
			$arr["TYPE"] = "error";	
		}
		

	}

	echo json_encode($arr);
	
}
else if($TYPES=="SELECT_TEMP"){
	try {
		$Query = mysqli_query($DB,
			"SELECT $temp[1].E_BETWEEN,$temp[2].ID,$temp[2].room_data_CODE
			FROM $temp[2]
			LEFT JOIN $temp[1]
			ON $temp[2].BILLCODE=$temp[1].BILLCODE
			ORDER BY $temp[2].ID ASC
			");
		$DATA = [];
		$n=0;
		while ($Fetch=mysqli_fetch_assoc($Query)) {
			$room_data_CODE = $Fetch["room_data_CODE"];
			$Query1=mysqli_query($DB,
				"SELECT room_data.NAME,room_type.NAME_TYPE
				FROM room_data
				LEFT JOIN room_type
				ON room_data.room_type_ID=room_type.ID
				WHERE room_data.CODE = '{$room_data_CODE}'
				");
			$Fetch1=mysqli_fetch_assoc($Query1);
			$DATA[]=$Fetch;
			$DATA[$n]+=[
				"NAME"=>$Fetch1["NAME"],
				"NAME_TYPE"=>$Fetch1["NAME_TYPE"],
			];
			$n++;
		}

		$arr["ERROR"] = false;
		$arr["MSG"] = "แสดงรายการที่เลือก.";
		$arr["DATA"] = $DATA;
		$arr["TYPE"] = "success";	
	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "MySql Error!";
		$arr["TYPE"] = "error";	
	}	
	echo json_encode($arr);
}
else if($TYPES=="DELETE_TEMP"){
	$ID = isset($_POST['ID']) ? $_POST['ID'] : "";
	try {
		$Query = mysqli_query($DB,
			"SELECT $temp[2].ID
			FROM $temp[2]
			WHERE $temp[2].ID='{$ID}'
			ORDER BY $temp[2].ID ASC
			");
		$Fetch = mysqli_fetch_assoc($Query);		
		if($Fetch){
			mysqli_query($DB,
				"DELETE 
				FROM $temp[2]
				WHERE ID='{$ID}' 
				");
			$arr["ERROR"] = false;
			$arr["MSG"] = "ลบรายการที่เลือกสำเร็จ.";			
			$arr["TYPE"] = "success";
		}
		else{
			$arr["ERROR"] = true;
			$arr["MSG"] = "ไม่พบรายการที่เลือก หรือรายการนี้ถูกลบไปแล้ว!";
			$arr["TYPE"] = "warning";	
		}
		
		
		
	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "MySql Error!";
		$arr["TYPE"] = "error";	
	}	
	echo json_encode($arr);
}
else if($TYPES=="CHANGE_TO_UPDATE"){
	$FORM_DATA = isset($_POST['FORM_DATA']) ? $_POST['FORM_DATA'] : "";
	$UPDATE_CUS = isset($_POST['UPDATE_CUS']) ? $_POST['UPDATE_CUS'] : false;

	$NOTE = isset($FORM_DATA['NOTE']) ? $FORM_DATA['NOTE'] : "";
	try {
		mysqli_query($DB,
			"UPDATE $temp[1] 
			SET NOTE='{$NOTE}'
			");
		$arr["ERROR"] = false;
		$arr["MSG"] = "แก้ไขข้อมูลสำเร็จ.";
		$arr["TYPE"] = "success";
	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "MySql Error!";
		$arr["TYPE"] = "error";	
	}
	echo json_encode($arr);
	
}
else if($TYPES=="FORM_MODAL_SUBMIT"){
	try {
		mysqli_query($DB,
			"INSERT INTO events(BILLCODE,orders_type_ID,customer_ID,PHONE,E_START,E_END,E_BETWEEN,BEDPLUS1,BEDPLUS2,PRICE_BEDPLUS,PRICE_TOTAL,DISCOUNT,DEPOSIT,PAY,CASH_CHANGE,NOTE,STATUS,CREATED) SELECT BILLCODE,orders_type_ID,customer_ID,PHONE,E_START,E_END,E_BETWEEN,BEDPLUS1,BEDPLUS2,PRICE_BEDPLUS,PRICE_TOTAL,DISCOUNT,DEPOSIT,PAY,CASH_CHANGE,NOTE,STATUS,CREATED FROM $temp[1] ORDER BY BILLCODE
			");

		mysqli_query($DB,
			"INSERT INTO events_detail(BILLCODE,room_data_CODE,PRICE,STATUS,CHK_IN,CHK_IN_DATETIME) SELECT BILLCODE,room_data_CODE,PRICE,STATUS,CHK_IN,CHK_IN_DATETIME FROM $temp[2] ORDER BY BILLCODE,room_data_CODE
			");
		
		$arr["ERROR"] = false;
		$arr["MSG"] = "บันทึกลงฐานข้อมูลสำเร็จ.";
		$arr["TYPE"] = "success";	
	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "MySql Error!";
		$arr["TYPE"] = "error";	
	}	
	echo json_encode($arr);
}
else if($TYPES=="NUM_BADGE"){
	try {
		$Query = mysqli_query($DB,
			"SELECT room_data_CODE
			FROM $temp[2]
			");
		$Number = mysqli_num_rows($Query);
		$arr["ERROR"] = false;
		$arr["MSG"] = "แสดงห้องที่สามารถจองได้.";
		$arr["DATA"] = $Number;
		$arr["TYPE"] = "success";	
	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "MySql Error!";
		$arr["TYPE"] = "error";	
	}	
	echo json_encode($arr);
}
else if($TYPES=="SELECT_prefix"){
	try {
		$Query = mysqli_query($DB,
			"SELECT PREFIX_ID,PREFIX
			FROM prefix
			ORDER BY PREFIX_ID ASC
			");
		$DATA = [];
		while($Fetch = mysqli_fetch_assoc($Query)){
			$DATA[]=$Fetch;
		}
		$arr["ERROR"] = false;
		$arr["MSG"] = "Select Prefix Success.";
		$arr["DATA"] = $DATA;
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
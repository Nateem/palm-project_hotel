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

if($TYPES=="SELECT_events_count"){

	$NOW = date('Y-m-d H:i:s');
	try {
  		$Query = mysqli_query($DB,
  			"SELECT DISTINCT events_detail.room_data_CODE,events.orders_type_ID,events_detail.CHK_IN
  			FROM events_detail
			LEFT JOIN events
			ON events_detail.BILLCODE=events.BILLCODE
  			WHERE '{$NOW}' BETWEEN events.E_START AND events.E_END
  			AND events_detail.CHK_OUT='0'
  			");

  		$room_checkin = array();
		$room_booking = array();
		$room_repair = array();

  		while($Fetch=mysqli_fetch_assoc($Query)){
  			$type_ID=$Fetch['orders_type_ID'];
  			$CHK_IN=$Fetch['CHK_IN'];
  			//$CHK_OUT=$Fetch['CHK_OUT'];
  			switch ($type_ID) {
				case '1':

					if($CHK_IN==1){
						array_push($room_checkin, $Fetch['room_data_CODE']);
					}
					else{
						array_push($room_booking, $Fetch['room_data_CODE']);//เพิ่มห้องที่ไม่ว่างเข้า Array
					}		
			
					break;
				case '2':

					array_push($room_checkin, $Fetch['room_data_CODE']);

					break;
				case '3':				
					array_push($room_repair, $Fetch['room_data_CODE']);
					break;				
			}
  		}  	
  		$room_checkin = array_unique($room_checkin);
		$room_repair = array_unique($room_repair);
		$room_booking = array_unique($room_booking);//array_unique เอาห้องที่ซ้ำกันออก	  		

		$room_busy = array();
		$room_available = array();

		$room_checkin_implode = implode("','",$room_checkin);//เพื่อเอาตัวแปลแบบ array ใส่ลง Sql ได้ง่าย
		$room_repair_implode = implode("','",$room_repair);
		$room_booking_implode = implode("','",$room_booking);
		
		array_push($room_busy, $room_checkin_implode, $room_repair_implode, $room_booking_implode);
		$room_busy = array_filter($room_busy);//กรองเอาเฉพาะที่มีค่า
		$room_busy_implode = implode("','",$room_busy);//เพื่อเอาตัวแปลแบบ array ใส่ลง Sql ได้ง่าย
		$Query1 = mysqli_query($DB,
			"SELECT room_data.CODE
			FROM room_data
			WHERE room_data.CODE
			NOT IN ('{$room_busy_implode}')
			");
		$rows = mysqli_num_rows($Query1);

		$Query2 = mysqli_query($DB,
			"SELECT room_data.CODE
			FROM room_data
			");
		$room_rows = mysqli_num_rows($Query2);

		$room_all = $room_rows;

		$arr["ERROR"] = false;
		$arr["MSG"] = "แสดงจำนวนห้อง";
		$arr["room_checkin"] = count($room_checkin);
		$arr["room_repair"] = count($room_repair);
		$arr["room_booking"] = count($room_booking);
		$arr["room_available"] = $rows;
		//$arr["room_busy"] = $room_busy_implode;
		$arr["room_all"] = $room_all;
		//$arr["room_booking_percent"] = (100 / $room_all) * count($room_booking);
		
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
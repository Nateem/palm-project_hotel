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
if($TYPES=="checkInAfterBooking"){
	$ROOM_CODE = isset($_POST["ROOM_CODE"]) ? $_POST["ROOM_CODE"] : "";
	$BILLCODE = isset($_POST["BILLCODE"]) ? $_POST["BILLCODE"] : "";
	try {
		mysqli_query($DB,
			"UPDATE events_detail
			SET STATUS='1',CHK_IN='1',CHK_IN_DATETIME=NOW()
			WHERE BILLCODE='{$BILLCODE}'
			");
		$arr["ERROR"] = false;
		$arr["MSG"] = "Check-In Success.";
		$arr["TYPE"] = "success";
 	
	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "Mysql Error!";
		$arr["TYPE"] = "error";	
	} 	
 	echo json_encode($arr);
}
else if($TYPES=="CHANGE_ROOM_SHOW"){
	$ROOM_CODE = isset($_POST["ROOM_CODE"]) ? $_POST["ROOM_CODE"] : "";
	$FORM_DATA = isset($_POST['FORM_DATA']) ? $_POST['FORM_DATA'] : "";
	$DATERANG = isset($FORM_DATA['DATERANG']) ? $FORM_DATA['DATERANG'] : "";
	//echo $DATE_IN."<br>",$DATE_OUT."<br>";
	$POST_E_START = $DATERANG["startDate"];
	$POST_E_END = $DATERANG["endDate"];

	$E_START = DATE("Y-m-d H:i:s",strtotime($POST_E_START));

	//$CURDATE = date("Y-m-d H:i:s",$CURDATE);
		$Query = mysqli_query($DB,
			"SELECT events_detail.ID AS events_detail_ID,NOW() AS E_START,events.E_END
			FROM events_detail
			LEFT JOIN events
			ON events_detail.BILLCODE=events.BILLCODE
			WHERE events_detail.room_data_CODE='{$ROOM_CODE}'
			AND ( DATE(NOW()) BETWEEN DATE(events.E_START) AND DATE(events.E_END))
			");
		$Fetch=mysqli_fetch_assoc($Query);
		$events_detail_ID = $Fetch["events_detail_ID"];
		$E_START = $Fetch["E_START"];
		$E_END = $Fetch["E_END"];


		$Query3 = mysqli_query($DB,
			"SELECT room_type.*
			FROM room_data
			LEFT JOIN room_type
			ON room_data.room_type_ID=room_type.ID			
			WHERE room_data.CODE='{$ROOM_CODE}'
			");
		$Fetch3=mysqli_fetch_assoc($Query3);

		$Fetch3 += [
			"events_detail_ID"=>$events_detail_ID,
			"E_START"=>$E_START,
			"E_END"=>$E_END
		];
		$arr["ERROR"] = false;
		$arr["MSG"] = "Success.";
		$arr["ROOM_TYPE"] = $Fetch3;
		//$arr["Fetch"] = $Fetch;
		//$arr["_POST"] = $_POST;
		
		$arr["TYPE"] = "success";
 	
	
 	echo json_encode($arr);
}
else if($TYPES=="CHANGE_ROOM_CODE"){
	$ROOM_CODE = isset($_POST["ROOM_CODE"]) ? $_POST["ROOM_CODE"] : "";
	$events_detail_ID = isset($_POST['events_detail_ID']) ? $_POST['events_detail_ID'] : "";
	$Query = mysqli_query($DB,
		"SELECT BILLCODE
		FROM events_detail
		WHERE ID='{$events_detail_ID}'
		");
	$Fetch = mysqli_fetch_assoc($Query);
	if($Fetch){
		mysqli_query($DB,
			"UPDATE events_detail 
			SET room_data_CODE='{$ROOM_CODE}'
			WHERE ID='{$events_detail_ID}'
			");
		$arr["ERROR"] = false;
		$arr["MSG"] = "ย้ายห้องสำเร็จ.";
		$arr["TYPE"] = "success";
	}
	else{
		$arr["ERROR"] = true;
		$arr["MSG"] = "ไม่พบรายการที่ต้องการย้าย!";
		$arr["TYPE"] = "error";
	}

 	echo json_encode($arr);
}
else if($TYPES=="SELECT_events"){
	//echo $DATE_IN."<br>",$DATE_OUT."<br>";
	$POST_E_START = $_POST["E_START"];
	$POST_E_END = $_POST["E_END"];
	$ST_DATE = DATE("Y-m-d 12:00:00",strtotime($POST_E_START));

	$E_START = DATE("Y-m-d 12:00:00",strtotime($POST_E_START));
	$E_END =  DATE("Y-m-d 11:59:00",strtotime($POST_E_END));
	//$E_END = date("Y-m-d",strtotime("-1 day",strtotime($E_END)));
	//echo $E_START,"<br>",$E_END,"<br><hr>";
	$E_BETWEEN = (int) (((strtotime($E_END)-strtotime($E_START))/60)/60)/24;

	$room_checkin = array();
	$room_booking = array();
	$room_repair = array();
	$n=0;
	while($E_START <= $E_END){
		
		$chkBookingS=mysqli_query($DB,
			"SELECT DISTINCT events_detail.room_data_CODE,events.orders_type_ID
			FROM events_detail
			LEFT JOIN events
			ON events_detail.BILLCODE=events.BILLCODE
			WHERE '{$E_START}' BETWEEN events.E_START AND events.E_END
			AND events_detail.CHK_OUT='0'
			");
		$bs=0;
		while($chkBooking=mysqli_fetch_assoc($chkBookingS)){
			$type_ID=$chkBooking['orders_type_ID'];
			switch ($type_ID) {
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

		/*====================================================================*/
		$E_START = date("Y-m-d",strtotime("+1 day",strtotime($E_START)));
		

	$n++;
	}

	$room_checkin = array_unique($room_checkin);
	$room_repair = array_unique($room_repair);
	$room_booking = array_unique($room_booking);//array_unique เอาห้องที่ซ้ำกันออก

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
			"SELECT room_data.ID,room_data.CODE,room_data.NAME,room_data.room_type_ID
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
				"ROOM_TYPE_ID" => $ROOM_TYPE_ID,
				"SUPPORT" => $SUPPORT,
				"BED_SIZE" => $BED_SIZE,
				"PRICE" => $PRICE,
			];

			$found_booking = array_search($NAME_CODE, $room_booking);
			$found_checkin = array_search($NAME_CODE, $room_checkin);//ค้นหาห้องที่ไม่ว่างในอาเรย์ที่ตรวจสอบจากวันที่ข้างต้น
			$found_repair = array_search($NAME_CODE, $room_repair);			


			$ROOM_STATUS3 = "จองแล้ว";
			$ROOM_STATUS2 = "มีผู้เข้าพัก";
					
			$Query1 = mysqli_query($DB,
				"SELECT events_detail.CHK_IN,events_detail.CHK_OUT
				FROM events_detail
				LEFT JOIN events
				ON events_detail.BILLCODE=events.BILLCODE
				WHERE events_detail.room_data_CODE='{$NAME_CODE}'
				AND ('{$ST_DATE}' BETWEEN events.E_START AND events.E_END)
				AND events_detail.CHK_OUT='0'
				");
			$Fetch1 = mysqli_fetch_assoc($Query1);

			if($found_repair !== false){ //ถ้ามีห้องในอาร์เรย์
				$DATA_ROOM[$n][$s] += [
					"ROOM_STATUS_CODE" => 4,
					"ROOM_STATUS" => "ไม่พร้อมให้บริการ",
				];
			}
			else if($found_booking !== false){ //กรณีการจองห้องพัก
				if($Fetch1["CHK_IN"]==1){//ถ้าเชคอินแล้ว
					if($Fetch1["CHK_OUT"]==1){//ถ้าเชคเอ้าแล้ว
						$DATA_ROOM[$n][$s] += [
							"ROOM_STATUS_CODE" => 1,
							"ROOM_STATUS" => "ห้องว่าง",
						];
					}
					else{//ถ้าเชคอินแต่ยังไม่เชคเอ้า
						$DATA_ROOM[$n][$s] += [
							"ROOM_STATUS_CODE" => 2,
							"ROOM_STATUS" => $ROOM_STATUS2,
						];
					}
				}
				else{//ถ้ายังไม่เชคอิน
					$DATA_ROOM[$n][$s] += [
						"ROOM_STATUS_CODE" => 3,
						"ROOM_STATUS" => $ROOM_STATUS3,
					];
				}
				
				
			}
			else if($found_checkin !== false){//กรณีเชคอินโดยไม่ผ่านการจอง
				if($Fetch1["CHK_OUT"]==1){//ถ้าเชคเอ้าแล้ว
					$DATA_ROOM[$n][$s] += [
						"ROOM_STATUS_CODE" => 1,
						"ROOM_STATUS" => "ห้องว่าง",
					];
				}
				else{//ถ้าเชคอินแต่ยังไม่เชคเอ้า
					$DATA_ROOM[$n][$s] += [
						"ROOM_STATUS_CODE" => 2,
						"ROOM_STATUS" => $ROOM_STATUS2,
					];
				}				
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
	$arr["TYPE"] = "success";	
	echo json_encode($arr);
}
else{
	$arr["ERROR"] = true;
	$arr["MSG"] = "Cannot Show Database!";
	$arr["TYPE"] = "error";	
	echo json_encode($arr);
}
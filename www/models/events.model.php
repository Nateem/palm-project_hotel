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
if($TYPES=="SELECT_events"){

  	$FORM_DATA = isset($_POST['FORM_DATA']) ? $_POST['FORM_DATA'] : "";
	$DATERANG = isset($FORM_DATA['DATERANG']) ? $FORM_DATA['DATERANG'] : "";
	//echo $DATE_IN."<br>",$DATE_OUT."<br>";
	$POST_E_START = $DATERANG["startDate"];
	$POST_E_END = $DATERANG["endDate"];

	$ST_DATE = DATE("Y-m-d 12:00:00",strtotime($POST_E_START));

	$E_START = DATE("Y-m-d 12:00:00",strtotime($POST_E_START));
	$E_END =  DATE("Y-m-d 11:59:00",strtotime($POST_E_END));
	//$E_END = date("Y-m-d",strtotime("-1 day",strtotime($E_END)));
	//echo $E_START,"<br>",$E_END,"<br><hr>";
	$E_BETWEEN = (int) (((strtotime($E_END)-strtotime($E_START))/60)/60)/24;

	

	$NOW = date('Y-m-d H:i:s');
	try {
		mysqli_query($DB,"DROP TABLE IF EXISTS $temp[1]");
  		mysqli_query($DB,"CREATE TABLE $temp[1] LIKE events");
  		mysqli_query($DB,"DROP TABLE IF EXISTS $temp[2]");
  		mysqli_query($DB,"CREATE TABLE $temp[2] LIKE events_detail");
  		$Query = mysqli_query($DB,
  			"SELECT $temp[1].BILLCODE
  			FROM $temp[1]
  			WHERE $temp[1].BILLCODE='{$NEXT_BILLCODE}'
  			");
  		$rows=mysqli_num_rows($Query);
  		if($rows>0){
  			mysqli_query($DB,
				"UPDATE $temp[1]
				SET customer_ID='00000001',orders_type_ID='{$orders_type_ID}',E_START='{$E_START}',E_END='{$E_END}',E_BETWEEN='{$E_BETWEEN}',CREATED=NOW()
				WHERE BILLCODE='{$NEXT_BILLCODE}'
				");
  		}
  		else{
  			mysqli_query($DB,
				"INSERT INTO $temp[1](BILLCODE,customer_ID,orders_type_ID,E_START,E_END,E_BETWEEN,CREATED)
				VALUES ('{$NEXT_BILLCODE}','00000001','{$orders_type_ID}','{$E_START}','{$E_END}','{$E_BETWEEN}','{$NOW}')
				");
  		}

  		

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
		//echo $E_START."<u>ยังไม่บวก</u><br>";
		/*====================================================================*/
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

			switch ($orders_type_ID) {
				case '1'://booking
					$ROOM_STATUS3 = "จองแล้ว";
					$ROOM_STATUS2 = "มีผู้เข้าพักใช้งาน";
					break;
				case '2'://checkin
					$ROOM_STATUS3 = "จองไว้ยังไม่เข้าพัก";
					$ROOM_STATUS2 = "มีผู้เข้าพัก";
					break;
				default:
					$ROOM_STATUS3 = "จองแล้ว";
					$ROOM_STATUS2 = "มีผู้เข้าพัก";
					break;
			}
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
	$arr["BILLCODE"] = $NEXT_BILLCODE;
	$arr["orders_type_ID"] = $orders_type_ID;
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

	$q = mysqli_query($DB,
		"SELECT room_type.PRICE
		FROM room_data
		LEFT JOIN room_type
		ON room_data.room_type_ID=room_type.ID
		WHERE room_data.CODE='{$ROOM_CODE}';
		");

	$r =mysqli_fetch_assoc($q);

	$PRICE_SOME = $r['PRICE'];
	$PRICE = ($PRICE_SOME * $E_BETWEEN);

	$arr=array();
	
	if($chk){
		try {			
			mysqli_query($DB,
				"UPDATE $temp[2]
				SET PRICE='{$PRICE}'
				WHERE room_data_CODE='{$ROOM_CODE}'
				");

				$arr["ERROR"] = false;
				$arr["MSG"] = "เลือกห้องนี้แล้ว/อัพเดทที่เลือกแล้ว";
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
				"INSERT INTO $temp[2](BILLCODE,room_data_CODE,PRICE)
				VALUES ('{$NEXT_BILLCODE}','{$ROOM_CODE}','{$PRICE}')
				");			
			$arr["ERROR"] = false;
			$arr["MSG"] = "เพิ่ม <b>".$ROOM_CODE." จำนวน ".$E_BETWEEN."</b> คืน";
			$arr["TYPE"] = "success";
		} catch (Exception $e) {
			$arr["ERROR"] = true;
			$arr["MSG"] = "MySql Error!";
			$arr["TYPE"] = "error";	
		}
		

	}
	$Query2 = mysqli_query($DB,
		"SELECT SUM($temp[2].PRICE) AS PRICE_TOTAL
		FROM $temp[2]
		");
	$Fetch2 = mysqli_fetch_assoc($Query2);
	$PRICE_TOTAL = $Fetch2["PRICE_TOTAL"];
	mysqli_query($DB,
		"UPDATE $temp[1]
		SET PRICE_TOTAL='{$PRICE_TOTAL}'
		WHERE BILLCODE='{$NEXT_BILLCODE}'
		");

	echo json_encode($arr);
	
}
else if($TYPES=="SELECT_TEMP"){
	try {
		$Query = mysqli_query($DB,
			"SELECT $temp[1].E_BETWEEN,$temp[1].PRICE_BEDPLUS,$temp[1].DEPOSIT,$temp[1].PAY,$temp[1].CASH_CHANGE,$temp[2].ID,$temp[2].room_data_CODE,$temp[2].PRICE
			FROM $temp[2]
			LEFT JOIN $temp[1]
			ON $temp[2].BILLCODE=$temp[1].BILLCODE
			ORDER BY $temp[2].ID ASC
			");
		$DATA = [];
		$n=0;
		$PRICE_TOTAL=0;
		while ($Fetch=mysqli_fetch_assoc($Query)) {
			$room_data_CODE = $Fetch["room_data_CODE"];
			$PRICE_TOTAL+=$Fetch["PRICE"];
			$Query1=mysqli_query($DB,
				"SELECT room_data.NAME,room_type.NAME_TYPE,room_type.PRICE AS PRICE_SOME
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
				"PRICE"=>$Fetch["PRICE"],
				"PRICE_SOME"=>$Fetch1["PRICE_SOME"]
			];
			$n++;
		}

		$arr["ERROR"] = false;
		$arr["MSG"] = "แสดงรายการที่เลือก.";
		$arr["DATA"] = $DATA;
		$arr["PRICE_TOTAL"] = $PRICE_TOTAL;
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

	$ID13 = isset($FORM_DATA['ID13']) ? $FORM_DATA['ID13'] : "0000000000000";

	$PREFIX_ID = isset($FORM_DATA['PREFIX_ID']) ? $FORM_DATA['PREFIX_ID'] : "";
	$FNAME = isset($FORM_DATA['FNAME']) ? $FORM_DATA['FNAME'] : "";
	$LNAME = isset($FORM_DATA['LNAME']) ? $FORM_DATA['LNAME'] : "";
	$ADDRESS = isset($FORM_DATA['ADDRESS']) ? $FORM_DATA['ADDRESS'] : "";
	$PHONE = isset($FORM_DATA['PHONE']) ? $FORM_DATA['PHONE'] : "";

	$Query = mysqli_query($DB,
		"SELECT customer.ID
		FROM customer
		WHERE customer.ID13='{$ID13}'
		");
	$Fetch = mysqli_fetch_assoc($Query);
	if($Fetch){
		$CUSTOMER_ID = $Fetch["ID"];
		$NEW_CUSTOMER_ID = $CUSTOMER_ID;
		if($UPDATE_CUS==true){
			mysqli_query($DB,
				"UPDATE customer 
				SET PREFIX_ID='{$PREFIX_ID}',FNAME='{$FNAME}',LNAME='{$LNAME}',ADDRESS='{$ADDRESS}',PHONE='{$PHONE}'
				WHERE ID13='{$ID13}'
				");
		}
		
	}
	else{
		mysqli_query($DB,
			"INSERT INTO customer(ID13,PREFIX_ID,FNAME,LNAME,ADDRESS,PHONE,CREATED)
			VALUES ('{$ID13}','{$PREFIX_ID}','{$FNAME}','{$LNAME}','{$ADDRESS}','{$PHONE}',NOW())
			");

		$NEW_CUSTOMER_ID = mysqli_insert_id($DB);	
	}
	$PRICE_BEDPLUS = isset($FORM_DATA['PRICE_BEDPLUS']) ? $FORM_DATA['PRICE_BEDPLUS'] : "";

	$deposit = isset($FORM_DATA['deposit']) ? $FORM_DATA['deposit'] : "";//เงินมัดจำ
	$DISCOUNT = isset($FORM_DATA['DISCOUNT']) ? $FORM_DATA['DISCOUNT'] : "";//ส่วนลด
	$NOTE = isset($FORM_DATA['NOTE']) ? $FORM_DATA['NOTE'] : "";
	$PAY = isset($FORM_DATA['PAY']) ? $FORM_DATA['PAY'] : "";//จ่าย
	$OWE = isset($FORM_DATA['OWE']) ? $FORM_DATA['OWE'] : "";//ค้างชำระ
	$CASH_CHANGE = isset($FORM_DATA['CASH_CHANGE']) ? $FORM_DATA['CASH_CHANGE'] : "";//เงินทอน

	$Query1 = mysqli_query($DB,
		"SELECT $temp[2].PRICE
		FROM $temp[2]
		WHERE $temp[2].BILLCODE='{$NEXT_BILLCODE}'
		");
	$PRICE = 0;
	while($Fetch1=mysqli_fetch_assoc($Query1)){
		$PRICE += $Fetch1["PRICE"];
	}
	$PRICE_TOTAL = ($PRICE+ $PRICE_BEDPLUS)-$DISCOUNT;
	try {
		mysqli_query($DB,
			"UPDATE $temp[1] 
			SET customer_ID='{$NEW_CUSTOMER_ID}',BEDPLUS1='{$BEDPLUS1}',BEDPLUS2='{$BEDPLUS2}',PRICE_BEDPLUS='{$PRICE_BEDPLUS}',DISCOUNT='{$DISCOUNT}',DEPOSIT='{$deposit}',PRICE_TOTAL='{$PRICE_TOTAL}',PAY='{$PAY}',OWE='{$OWE}',CASH_CHANGE='{$CASH_CHANGE}',NOTE='{$NOTE}'
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
else if($TYPES=="PRINT_BILL"){
	$WEBAPP_DATA = isset($_POST['WEBAPP_DATA']) ? $_POST['WEBAPP_DATA'] : "";

	$NAME = isset($WEBAPP_DATA['NAME']) ? $WEBAPP_DATA['NAME'] : "";
	$ADDRESS = isset($WEBAPP_DATA['ADDRESS']) ? $WEBAPP_DATA['ADDRESS'] : "";
	$PHONE = isset($WEBAPP_DATA['PHONE']) ? $WEBAPP_DATA['PHONE'] : "";
	$CREDIT = isset($WEBAPP_DATA['CREDIT']) ? $WEBAPP_DATA['CREDIT'] : "";
	require_once "./FPDF/ThaiPDF.class.php";
	/*-------------------------------ขนาดใบเสร็จแบบย่อ--------------------------------------*/
	try {
		sleep(1);
		$pdf=new ThaiPDF();
		$pdf->AddThaiFont("dillenia");
		$pdf->AddPage('P',array(53,180));
		$pdf->SetMargins(0,0,1);
		$pdf->SetFont("dillenia",'',20);
		$pdf->Cell(0,1,$pdf->conv(''),0,1,"C");
		$pdf->Cell(0,8,$pdf->conv($NAME),0,1,"C");
		$pdf->SetFont("dillenia",'B',16);
		$pdf->Cell(0,7,$pdf->conv("THANK YOU"),0,1,"C");
		$pdf->Ln(6);

		$Query = mysqli_query($DB,
			"SELECT $temp[1].BILLCODE,$temp[1].customer_ID,$temp[1].E_START,$temp[1].E_END,$temp[1].E_BETWEEN,$temp[1].BEDPLUS1,$temp[1].BEDPLUS2,$temp[1].PRICE_BEDPLUS,$temp[1].PRICE_TOTAL,$temp[1].DISCOUNT,$temp[1].DEPOSIT,$temp[1].PAY,$temp[1].OWE,$temp[1].CASH_CHANGE,$temp[1].NOTE,$temp[1].CREATED
			FROM $temp[1]
			");
		$Fetch = mysqli_fetch_assoc($Query);
		$BILLCODE = $Fetch["BILLCODE"];
		$customer_ID = $Fetch["customer_ID"];
		$E_START = $Fetch["E_START"];
		$E_END = $Fetch["E_END"];
		$E_BETWEEN = $Fetch["E_BETWEEN"];
		$BEDPLUS1 = $Fetch["BEDPLUS1"];
		$BEDPLUS2 = $Fetch["BEDPLUS2"];
		$PRICE_BEDPLUS = $Fetch["PRICE_BEDPLUS"];
		$PRICE_TOTAL = $Fetch["PRICE_TOTAL"];
		$DISCOUNT = $Fetch["DISCOUNT"];
		$DEPOSIT = $Fetch["DEPOSIT"];
		$PAY = $Fetch["PAY"];
		$OWE = $Fetch["OWE"];
		$CASH_CHANGE = $Fetch["CASH_CHANGE"];
		$NOTE = $Fetch["NOTE"];
		$CREATED = $Fetch["CREATED"];

		
		
		$pdf->SetFont("dillenia",'',11);
		$pdf->Cell(0,4,$pdf->conv("REG : ".MAIN::formatDATE($CREATED)),0,1,"L");
		$pdf->Cell(0,4,$pdf->conv($BILLCODE),0,1,"R");
		//$pdf->Cell(0,4,$pdf->conv("Booking"),0,1,"L");
		$pdf->Cell(0,4,$pdf->conv(substr(MAIN::formatDATE($E_START),0,10)." to ".substr(MAIN::formatDATE($E_END),0,10)),0,1,"R");
		

		$Query1 = mysqli_query($DB,
			"SELECT $temp[2].*,room_data.NAME AS ROOM_NAME
			FROM $temp[2]
			LEFT JOIN room_data
			ON $temp[2].room_data_CODE=room_data.CODE
			WHERE $temp[2].BILLCODE='{$BILLCODE}'
			");
		while($Fetch1=mysqli_fetch_assoc($Query1)){
			$pdf->Cell(0,3,$pdf->conv($Fetch1["ROOM_NAME"]." x ".$E_BETWEEN),0,1,"L");
			$pdf->Cell(0,3,$pdf->conv("THB ".number_format($Fetch1["PRICE"]).".-"),0,1,"R");
		}
		$pdf->Cell(0,4,$pdf->conv("Extra bed  150 x ".($BEDPLUS1/150)." , 350 x ".($BEDPLUS2/350)),0,1,"L");
		$pdf->Cell(0,4,$pdf->conv("THB ".number_format($PRICE_BEDPLUS).".-"),0,1,"R");

		$pdf->Cell(0,4,$pdf->conv("Discount"),0,1,"L");
		$pdf->Cell(0,4,$pdf->conv("THB ".number_format($DISCOUNT).".-"),0,1,"R");

		$pdf->Cell(0,4,$pdf->conv("Deposit"),0,1,"L");
		$pdf->Cell(0,4,$pdf->conv("THB ".number_format($DEPOSIT).".-"),0,1,"R");

		$pdf->SetFont("dillenia",'B',20);
		$pdf->Cell(0,6,$pdf->conv("TOTAL      THB ".number_format($PRICE_TOTAL+$DEPOSIT).".-"),0,1,"R");
		$pdf->SetFont("dillenia",'B',20);
		$pdf->Cell(0,6,$pdf->conv("CASH      THB ".number_format($PAY).".-"),0,1,"R");
		//$pdf->SetFont("dillenia",'',16);
		//$pdf->Cell(0,6,$pdf->conv("Deposit      THB ".number_format($DEPOSIT).".-"),0,1,"C");
		
		$PDF_FILE = "./PDF_FILE/".$temp[1].".pdf";
		@unlink($PDF_FILE);
		$pdf->Output($PDF_FILE,"F");

		$arr["ERROR"] = false;
		$arr["MSG"] = "Success";
		$arr["PDF_FILE"] = $PDF_FILE;
		$arr["TYPE"] = "success";
	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "MySql&FPDF Error!";
		$arr["TYPE"] = "error";
	}
	
	echo json_encode($arr);
}
else if($TYPES=="FORM_MODAL_SUBMIT"){
	try {
		if($orders_type_ID==2){
			mysqli_query($DB,
				"UPDATE $temp[2]
				SET STATUS='1',CHK_IN='1',CHK_IN_DATETIME=NOW()
				WHERE BILLCODE='{$NEXT_BILLCODE}'
				");
		}

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
else if($TYPES=="CHECK_CUSTOMER"){
	$ID13 = isset($_POST['ID13']) ? $_POST['ID13'] : "";
	try {
		$Query = mysqli_query($DB,
			"SELECT customer.ID13,customer.PREFIX_ID,customer.FNAME,customer.LNAME,customer.ADDRESS,customer.PHONE
			FROM customer
			WHERE customer.ID13='{$ID13}'
			");
		$Fetch = mysqli_fetch_assoc($Query);
		if($Fetch){

			$arr["ERROR"] = false;
			$arr["MSG"] = "พบว่าเคยเข้ามาพักแล้ว.";
			$arr["DATA"] = $Fetch;
			$arr["TYPE"] = "success";
		}
		else{
			$arr["ERROR"] = true;
			$arr["MSG"] = "ไม่พบสมาชิก กรุณาเพิ่มรายละเอียดใหม่!";
			$arr["TYPE"] = "warning";
		}			
	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "MySql Error!";
		$arr["TYPE"] = "error";	
	}	
	echo json_encode($arr);
}
else if($TYPES=="InfoRoomBooking"){
	$ROOM_CODE = isset($_POST['ROOM_CODE']) ? $_POST['ROOM_CODE'] : "";
	$CURDATE = isset($_POST['CURDATE']) ? $_POST['CURDATE'] : "";

	$E_START = DATE("Y-m-d 12:00:00",strtotime($CURDATE));

	try {
		$Query = mysqli_query($DB,
			"SELECT events_detail.*,events.*,customer.FNAME,customer.LNAME
			FROM events
			LEFT JOIN events_detail
			ON events.BILLCODE=events_detail.BILLCODE
			LEFT JOIN customer
			ON events.customer_ID=customer.ID
			WHERE events_detail.room_data_CODE='{$ROOM_CODE}'
			AND ('{$E_START}' BETWEEN events.E_START AND events.E_END)
			AND events_detail.CHK_OUT='0'
			");
		$Fetch = mysqli_fetch_assoc($Query);
		if($Fetch){
			$BILLCODE = $Fetch["BILLCODE"];
			$Query1=mysqli_query($DB,
				"SELECT events_detail.*,room_data.NAME AS ROOM_NAME,room_data.room_type_ID
				FROM events_detail
				LEFT JOIN room_data
				ON events_detail.room_data_CODE=room_data.CODE
				WHERE events_detail.BILLCODE='{$BILLCODE}'
				");
			$DATA1 = [];
			$n1=0;
			while ($Fetch1=mysqli_fetch_assoc($Query1)) {
				$DATA1[]=$Fetch1;
				$room_type_ID = $Fetch1['room_type_ID'];
				$Query2=mysqli_query($DB,
					"SELECT room_type.NAME_TYPE
					FROM room_type
					WHERE room_type.ID='{$room_type_ID}'
					");
				$Fetch2=mysqli_fetch_assoc($Query2);
				$DATA1[$n1]+=[
					"NAME_TYPE"=>$Fetch2["NAME_TYPE"]
				];
				$n1++;
			}
			$Fetch["SUMTOTAL"] = $Fetch["PRICE_TOTAL"]+$Fetch["DEPOSIT"];
			if($Fetch["PAY"]>=$Fetch["SUMTOTAL"]){
				$Fetch["OWE"] = 0;
			}
			else{
				$Fetch["OWE"] = $Fetch["SUMTOTAL"]-$Fetch["PAY"];
			}
			
			$arr["ERROR"] = false;
			$arr["MSG"] = "มี";
			$arr["DATA"] = $Fetch;
			$arr["DATA1"] = $DATA1;
			$arr["TYPE"] = "success";
		}
		else{
			$arr["ERROR"] = true;
			$arr["MSG"] = "ไม่มี";
			$arr["TYPE"] = "warning";
		}
	} catch (Exception $e) {
		$arr["ERROR"] = true;
		$arr["MSG"] = "MySql Error!";
		$arr["TYPE"] = "error";
	}
	echo json_encode($arr);

}
else if($TYPES=="SELECT_status"){
	try {
		$Query = mysqli_query($DB,
			"SELECT status.*
			FROM status
			ORDER BY status ASC
			");
		$DATA = [];
		while($Fetch = mysqli_fetch_assoc($Query)){
			$DATA[]=$Fetch;
		}
		$arr["ERROR"] = false;
		$arr["MSG"] = "Select status Success.";
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
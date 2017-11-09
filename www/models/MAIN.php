<?php 
/**
* Created by Natee Puechpean
* 2016-09-07 19:20
*/
$path_config = dirname(__FILE__)."/db_config.php";
require_once $path_config;
date_default_timezone_set('Asia/Bangkok');
class MAIN
{

	function __construct()
	{
		# code...
	}
	
	public function CONNECT($HOST=HOST,$USER=USER,$PASS=PASS,$DATABASE=DATABASE){
		$DB = mysqli_connect($HOST,$USER,$PASS,$DATABASE);
		if(mysqli_connect_errno()){
			$arr["ERRODBR"] = true;
			$arr["MSG"] = "Failed to connect to MySQL: " . mysqli_connect_error();
		}
		else{
			mysqli_query($DB,"SET NAMES UTF8");
			$arr["ERROR"] = false;
			$arr["MSG"] = "Connection Success..";
			$arr["DB"] = $DB;
		}
		return $arr;
	}
	public function CreateTempSession($number){
		#สร้างเลข session_id ประจำเครื่อง 
		/*@session_start();
		$my_session_id=session_id();*/
		$my_session_id="";
		for($i=0;$i<=$number;$i++){
			$m=substr('00'.$i,-2);
			$temp[$i]="temp".$m.$my_session_id;
		}
		return $temp;
	}
	public function BILLCODE_GEN($BillCode,$CodeEN,$length){//สร้างเลขที่บิล วิธีใช้ BILLCODE_GEN("เลขที่บิลก่อนหน้า","คำนำหน้า","จำนวนความยาวตัวเลข");
		$ordertxt = $CodeEN;
		$Relength = $length-1;
			if($BillCode!=""){					
				$strLen = intval(strlen($ordertxt));
				$BillCode=intval(substr($BillCode, $strLen, strlen($BillCode)))+1;
				$txtOrderCode=$ordertxt.str_repeat("0",$length-intval(strlen($BillCode))).intval($BillCode);
				return $txtOrderCode;
			}
			else{
				$txtOrderCode=$ordertxt.str_repeat("0",$Relength).'1';
				return $txtOrderCode;
				} 		
		}
	public function formatDATE($DATE_IN){
		$OLD_FORMAT=strtotime($DATE_IN);
		$y = date("Y",$OLD_FORMAT);
		$NEW_FORMAT = date("d/m/$y", $OLD_FORMAT);
		if(date("H:i:s",$OLD_FORMAT)!="00:00:00"){
			$NEW_TIME= date("H:i:s",$OLD_FORMAT);
		}
		else{
			$NEW_TIME="";
		}
		if($OLD_FORMAT){
			return $NEW_FORMAT.' '.$NEW_TIME;
		}
	}
public function ConVertDateInput($DATE_BETWEEN,$START_END){
		$START_Y = substr($DATE_BETWEEN, 6,4);
		$START_m = substr($DATE_BETWEEN, 3,2);
		$START_d = substr($DATE_BETWEEN, 0,2);

		$END_Y = substr($DATE_BETWEEN, 19,4);
		$END_m = substr($DATE_BETWEEN, 16,2);
		$END_d = substr($DATE_BETWEEN, 13,2);

		switch ($START_END) {
			case 'START':
				$result = $START_Y."-".$START_m."-".$START_d.' '."12:00:00";
				break;
			case 'END':
				$result = $END_Y."-".$END_m."-".$END_d.' '."11:59:00";
				break;			
		}		
		return $result;		
	}
	public function GenerateCode($CodeOld,$organization_ID,$cus_ID,$goods_ID,$manufacture_category_ID,$length=3){
		$organization_ID = str_pad($organization_ID, 4, "0", STR_PAD_LEFT);
		$cus_ID = str_pad($cus_ID, 4, "0", STR_PAD_LEFT);
		$goods_ID = str_pad($goods_ID, 3, "0", STR_PAD_LEFT);
		$manufacture_category_ID = str_pad($manufacture_category_ID, 2, "0", STR_PAD_LEFT);
		$CodePrimary = $organization_ID.$cus_ID.$goods_ID.$manufacture_category_ID;
		if($CodeOld!=""){
			$strAll = intval(strlen($CodePrimary)+$length);
			$strlenStart = intval(strlen($CodePrimary));
			$strlenEnd = intval($length);
			$subNumberCode = substr($CodeOld, $strlenStart,$strlenEnd);
			$txtCode = $CodePrimary.str_pad(intval($subNumberCode+1) , $length, "0", STR_PAD_LEFT);
		}
		else{
			$txtCode = $CodePrimary.str_pad("1", $length, "0", STR_PAD_LEFT);
		}
		return $txtCode;
	}
	public function productionExists($PIC_SQL,$path="../img/productions/"){
		if($PIC_SQL){
			if(file_exists($path.$PIC_SQL)){
				$PIC_ = $PIC_SQL;
			}
			else{
				$PIC_ = "default.jpg";
			}
		}
		else{
			$PIC_ = "default.jpg";
		}
		return $PIC_;
	}
	public function profileExists($PIC_SQL,$path="../img/profiles/"){
		if($PIC_SQL){
			if(file_exists($path.$PIC_SQL)){
				$PIC_ = $PIC_SQL;
			}
			else{
				$PIC_ = "default.png";
			}
		}
		else{
			$PIC_ = "default.png";
		}
		return $PIC_;
	}
	public function shortDate($input){
		$_year=substr($input,0,4);
		$_month=substr($input,5,2);
		$_day=substr($input,8,2);
		$_time=substr($input,11,8);
		/*switch($_month)
		{
			case 1:
				$month_name='มกราคม';
				break;
			case 2:
				$month_name='กุมภาพันธ์';
				break;
			case 3:
				$month_name='มีนาคม';
				break;
			case 4:
				$month_name='เมษายน';
				break;
			case 5:
				$month_name='พฤษภาคม';
				break;
			case 6:
				$month_name='มิถุนายน';
				break;
			case 7:
				$month_name='กรกฎาคม';
				break;
			case 8:
				$month_name='สิงหาคม';
				break;
			case 9:
				$month_name='กันยายน';
				break;
			case 10:
				$month_name='ตุลาคม';
				break;
			case 11:
				$month_name='พฤศจิกายน';
				break;
			case 12:
				$month_name='ธันวาคม';
				break;
		}*/
		//$budha_year=$_year+543;
		$budha_year=$_year;
		return $budha_year."/".$_month."/".$_day." ".$_time;
	}
}
 ?>
<?php
ob_start();
ini_set('date.timezone','Africa/Cairo');
date_default_timezone_set('Africa/Cairo');
session_start();

require_once('initialize.php');
require_once('classes/DBConnection.php');
require_once('classes/SystemSettings.php');
$db = new DBConnection;
$conn = $db->conn;
function redirect($url=''){
	if(!empty($url))
	echo '<script>location.href="'.base_url .$url.'"</script>';
}
function validate_image($file){
    global $_settings;
	if(!empty($file)){
			// exit;
        $ex = explode("?",$file);
        $file = $ex[0];
        $ts = isset($ex[1]) ? "?".$ex[1] : '';
		if(is_file(base_app.$file)){
			return base_url.$file.$ts;
		}else{
			return base_url.($_settings->info('logo'));
		}
	}else{
		return base_url.($_settings->info('logo'));
	}
}
function format_num($number = '' , $decimal = ''){
    if(is_numeric($number)){
        $ex = explode(".",$number);
        $decLen = isset($ex[1]) && abs($ex[1]) != 0 ? strlen($ex[1]) : 0;
        if(is_numeric($decimal)){
            return number_format($number,$decimal);
        }else{
            return number_format($number,$decLen);
        }
    }else{
        return "Invalid Input";
    }
}
function isMobileDevice(){
    $aMobileUA = array(
        '/iphone/i' => 'iPhone', 
        '/ipod/i' => 'iPod', 
        '/ipad/i' => 'iPad', 
        '/android/i' => 'Android', 
        '/blackberry/i' => 'BlackBerry', 
        '/webos/i' => 'Mobile'
    );

    //Return true if Mobile User Agent is detected
    foreach($aMobileUA as $sMobileKey => $sMobileOS){
        if(preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])){
            return true;
        }
    }
    //Otherwise return false..  
    return false;
}
ob_end_flush();

// Search by inmate name

function Get_inmate_name($inmate_name)
{

    global $conn;


  $sql = "SELECT *,concat(il.id) as `inmid` FROM `inmate_list`il,`cell_list`cl where il.cell_id = cl.id and il.firstname Like '%$inmate_name%' or il.middlename LIKE '%$inmate_name%'";

   $query = mysqli_query($conn,$sql);
   if($query)
   {
    return $query;

   }else{
echo "Wrong query";
    $conn.die();
   }



}


function Get_inmate_action($inmate_action){

    global $conn;


  $sql = "SELECT *,concat(il.id) as `inmid` FROM `record_list`rl,`inmate_list`il,`action_list`al WHERE rl.action_id = al.id and rl.`inmate_id` = il.id and rl.`action_id` = $inmate_action";

   $query = mysqli_query($conn,$sql);
   if($query)
   {
    return $query;

   }else{
echo "Wrong query";
    $conn.die();
   }


}




// SELECT * FROM `inmate_crimes`ic , `crime_list`cl , `inmate_list`il WHERE ic.crime_id = cl.id and ic.inmate_id = il.id and cl.id = 3;

function Get_inmate_crime($inmate_crime){

    global $conn;


  $sql = "SELECT *,concat(il.id) as `inmid` FROM `inmate_crimes`ic , `crime_list`cl , `inmate_list`il WHERE ic.crime_id = cl.id and ic.inmate_id = il.id and cl.id = $inmate_crime";

   $query = mysqli_query($conn,$sql);
   if($query)
   {
    return $query;

   }else{
echo "Wrong query";
    $conn.die();
   }


}

function Get_inmate_cell($cell_id)
{
    global $conn;


    $sql = "SELECT *,il.id as `inmid`, cel.name as `cel_name` FROM `cell_list`cel , `inmate_list`il WHERE cel.id = il.cell_id and il.cell_id = $cell_id";
   
  
     $query = mysqli_query($conn,$sql);
     if($query)
     {
      return $query;
  
     }else{
  echo "Wrong query";
      $conn.die();
     }

}












function Inmate_relase_today(){

$today = date("Y-m-d");

global $conn;


$sql = "SELECT * from `inmate_list` WHERE  `date_to` = '$today' ";

 $query = mysqli_query($conn,$sql);
 if($query)
 {
  return $query;

 }else{
echo "Wrong query";
  $conn.die();
 }

}


function Update_status_cell($inmate_id,$cell_id){

    global $conn;

    $sql_check = "SELECT * FROM `inmate_list`il,`cell_list`cl where cl.id = il.cell_id and il.id = $inmate_id and il.cell_id = $cell_id and il.status = 2 ";
    $query = mysqli_query($conn,$sql_check);
    if(mysqli_num_rows($query) > 0){
        // 
    }else{
        $sql_cell = "SELECT * FROM cell_list where id = $cell_id  ";
        $query_cell = mysqli_query($conn,$sql_cell);
        $rows = mysqli_fetch_array($query_cell);
        $new_number =  $rows['number']+ 1;
        $Update_query_2 = "UPDATE `cell_list`cl ,`inmate_list`il  SET cl.number = $new_number, il.status = 2 where cl.id = il.cell_id and  cl.id = $cell_id and il.id = $inmate_id ";
        $query_3 = mysqli_query($conn,$Update_query_2);
    }

}




function Change_status($inmate_id,$cell_id){


    global $conn;

    $sql_check = "SELECT * FROM `inmate_list`il,`cell_list`cl where cl.id = il.cell_id and il.id = $inmate_id and il.cell_id = $cell_id and il.status = 1 or 2 ";
    $query = mysqli_query($conn,$sql_check);
    if(mysqli_num_rows($query) > 0){
        // 
    }else{
        $sql_cell = "SELECT * FROM cell_list where id = $cell_id  ";
        $query_cell = mysqli_query($conn,$sql_cell);
        $rows = mysqli_fetch_array($query_cell);
        $new_number =  $rows['number']- 1;
        $Update_query_2 = "UPDATE `cell_list`cl ,`inmate_list`il  SET cl.number = $new_number, il.status = 1 where cl.id = il.cell_id and  cl.id = $cell_id and il.id = $inmate_id ";
        $query_3 = mysqli_query($conn,$Update_query_2);
    }


}










?>
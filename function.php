<?php

session_start();
//connect to database
  include 'db.php';

//dynamic service title
function get_service_name($s_id){
    $s_id = intval($s_id);
    return get_var("SELECT name FROM service WHERE s_id=$s_id LIMIT 1");
}
//get how many people in queue
function get_inline($s_id){
  $s_id = intval($s_id);
  $result = get_result("SELECT u_id FROM user WHERE s_id=$s_id AND state=0");
  return($result->num_rows);
}
// Get the services for the company
function get_services($c_id=1){
  $c_id = intval($c_id);
  if($c_id!=0){
    $result = get_result("SELECT s_id,name,(SELECT COUNT(u_id) FROM user WHERE state=0 AND s_id=service.s_id) as q_count FROM service WHERE c_id=$c_id");
    return $result;
  }else{
    return false;
  }
}

// a_id still need configuring, when user state goes from 0-1 a_id should go null-to current a_id
function user_update_by_service($s_id,$a_id){
  $s_id = intval($s_id);
  $a_id = intval($a_id);
  if($s_id!=0){
    get_result("UPDATE user SET state=3 WHERE s_id = $s_id AND state=1 ORDER BY time_in ASC LIMIT 1");
    get_result("UPDATE user SET state=1,a_id=$a_id WHERE s_id = $s_id AND state=0 ORDER BY time_in ASC LIMIT 1");
    return true;
  }else{
    return false;
  }
}

function user_update_state(){
  $a_id = 1;
  get_result("UPDATE user SET state=2 WHERE a_id = $a_id ORDER BY time_in ASC LIMIT 1");
}

function reset_queue($s_id){
  $s_id = intval($s_id);
  get_result("UPDATE user SET state=4 WHERE s_id = $s_id AND (state=0 OR state=1) ORDER BY time_in ASC");
}

//extract first value/variable in database
function get_var($query){

  $result = get_result($query);
  if ($result->num_rows == 0)
  {
    return false;
  }
  else {
    // Pop the value
    $service = $result->fetch_assoc();
    // Get the search colum by split the query
    $split = explode(' ',$query);
    // Return the selected colum value
    return $service[$split[1]];
  }
}

//connection to sql return result
function get_result($query){
  // Import our global mysqli-varible
  global $mysqli;

  // Run the query
  $result = $mysqli->query($query);

  return $result;

}
function sendSMS ($sms) {
  // Set your 46elks API username and API password here
  // You can find them at https://dashboard.46elks.com/
  $username = 'u92c0d266fed48b8ca18a4d2f795eb1fd';
  $password = '81669E7493A876DC1AD08452334FDEA4';
  $context = stream_context_create(array(
    'http' => array(
      'method' => 'POST',
      'header'  => "Authorization: Basic ".
                   base64_encode($username.':'.$password). "\r\n".
                   "Content-type: application/x-www-form-urlencoded\r\n",
      'content' => http_build_query($sms),
      'timeout' => 10
  )));
  $response = file_get_contents(
    'https://api.46elks.com/a1/SMS', false, $context );
  if (!strstr($http_response_header[0],"200 OK"))
    return $http_response_header[0];

  return $response;
}


function makeSMS($phone_no,$in_line,$link,$q_no)
{
  $temp = (string)$phone_no;
  $temp1 = substr($temp,1);
  $num = '+46'.$temp1;
	return array(
	'from' => 'Queue',
	'to' => $num,
	'message' => "Your number is ".(string)$q_no.".\nThere are ".(string)$in_line."people in queue, click on the link: \n".$link."/"
);
}

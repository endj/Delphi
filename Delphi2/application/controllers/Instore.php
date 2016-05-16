<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Instore extends CI_Controller{



  public function index()
  {
//    $c_id = $this->session->userdata('c_id');
    $c_id = 1;
    $data['services'] =$this->get_services($c_id);
    $data['theme'] = $this->use_theme($c_id);
    $data['margin'] = $this->set_margin($this->get_services($c_id));
    $this->load->view('instore/index_i',$data);

  }

  // display for index page
  private function set_margin($result){
    if(sizeof($result)==4){
          return 'remove_margin_top';
    }
    else return '';
  }

  // get theme selected by company
  private function use_theme($c_id){
    $c_id = intval($c_id);
    $this->load->model('instore_m');
    $theme = $this->instore_m->get_theme($c_id); // get theme from database

    // send theme with html
    if ($theme === "dark"){
      return "class = 'dark'";
    }else if ($theme === "red"){
      return "class = 'red'";
    }
    else return "";
  }

  // get a list of services currently offered by the company
  // return array of service id and service name
  private function get_services($c_id){
    $c_id = intval($c_id);
    $this->load->model('instore_m');
    $result = $this-> instore_m ->get_services($c_id);
    return $result;
  }

  /* Goes to specific register page */
  public function register()
  	{
  		// Load the model
  		$this->load->model('instore_m');
  		$result = $this->instore_m->get_service_name(intval($_GET['service'])); //gets the service
  		// Load the view
      $data['service']= $result[0]->name;
      $in_line = $this->instore_m->get_inline(intval($_GET['service'])); //inline
      $data['inline']= sizeof($in_line);
      $result = $this->instore_m->ewt(intval($_GET['service']));
      $ewt = $result[0]['ewt'];
      $handler = $result[0]['handlers'];
      if ($handler==0) {
        $handler=1;
      }
      $data['ewt'] = ceil(($ewt/$handler)/60);
  		$this->load->view('instore/register',$data);
  	}

  public function submit(){

    // Check if the number is submited
    if(isset($_POST['number'])){

      // Setup the varibles & Clean the data
      $number = $mysqli->real_escape_string($_POST['number']); /*ask ludwig if we really need it?*/
      $time_in = time(); // time() return the unix timestamp
      $s_id = intval($_POST['service_id']); // make sure it's a number

      // Get the queue-number
      $this->load->model('instore_m');

      $result = $this->instore_m->q_no($s_id); 

      //sendSMS(makeSMS($_POST['number'],$_POST['in_line']));


      if($result==false){
        $q_no = 1;
      }else{
        $q_no = $result+1;
      }
      // Check the service id
      if($s_id!=0){

        $public_id = generateRandomString(5);

        // Run the query and insert into db
        $mysqli->query("INSERT INTO user(public_id,phone_no,time_in,s_id,q_no) VALUES('$public_id','$number',$time_in,$s_id,$q_no)");

        // send the user to the next page
        $uid= $mysqli->insert_id;
        $link1 = 'http://46.101.97.62/user/?u='.$public_id;

        sendSMS(makeSMS($_POST['number'],$_POST['in_line'],$link1,$q_no,$uid,$s_id));
        header("Location: done.php?q_no=".$q_no."&phone_nr=".$_POST['number']."&service=".$s_id);

      }


    }else{
      header("Location: index.php");
    }



    //send sms
    //make sms
    //redirect to done.php
  }

  private function sendSMS ($sms) {
    // Set your 46elks API username and API password here
    // You can find them at https://dashboard.46elks.com/
    //Comment out 2 lines below to disable SMS when testing
    $username = 'u92c0d266fed48b8ca18a4d2f795eb1fd';
    $password = 'D80F6925A0D8DD4732734486222D884A';
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


  private function makeSMS($phone_no,$in_line,$link1,$q_no,$user,$s_id)
  {
    $temp = (string)$phone_no;
    $temp1 = substr($temp,1);
    $num = '+46'.$temp1;
  	return array(
  	'from' => 'Queue',
  	'to' => $num,
  	'message' => "Your number is ".(string)$q_no.".\nThere are ".(string)$in_line." people in queue. Please return to DQ in ".ewt_for_user($s_id,$user)." minutes. Track yourself here ".$link1
  );
  }

}

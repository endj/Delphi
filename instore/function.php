<?php
//connect to database
  include 'db.php';

//dynamic service title
function get_service_name($s_id){
    $s_id = intval($s_id);
    return get_var("SELECT name FROM service WHERE s_id=$s_id LIMIT 1");
}

function get_var($query){

  // Import our global mysqli-varible
  global $mysqli;

  // Run the query
  $result = $mysqli->query($query);

  // If the number of result is 0 for the query, return false
  if($result->num_rows==0)
  {
    return false;
  }
  else
  {
    // Pop the value
    $service = $result->fetch_assoc();
    // Get the search colum by split the query
    $split = explode(' ',$query);
    // Return the selected colum value
    return $service[$split[1]];
  }

}

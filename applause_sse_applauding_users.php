<?php

/* 
	Source for Server Side Events: Currently applauding users 
	
	The Javascript in applause_client.html and applause_server.html connect (via 
	applause_sse.js) to this ServerSideEvent and get Updated, when the number of 
	applauding users is changed in applause_state_applauding_users.txt
	Whenever a new connection is established, we register the new User in 
	applause_state_registered_users.txt, and we unregister him, when the connection is 
	aborted
	
	The logic of the Server Side Events is copied from: 
	https://kevinchoppin.dev/blog/server-sent-events-in-php and 
https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events
*/


header("Content-Type: text/event-stream");
header("Cache-Control: no-cache");
header("Access-Control-Allow-Origin: *");

ignore_user_abort(true); // Stops PHP from checking for user disconnect

require("applause_numbers_in_file.php");


// Is this a new stream or an existing one?
$lastEventId = floatval(isset($_SERVER["HTTP_LAST_EVENT_ID"]) ? $_SERVER["HTTP_LAST_EVENT_ID"] : 0);
if ($lastEventId == 0) {
	register_new_user();
	$lastEventId = floatval(isset($_GET["lastEventId"]) ? $_GET["lastEventId"] : 0);
}

// 2 kB padding for IE
echo ":" . str_repeat(" ", 2048) . "\n"; 
echo "retry: 2000\n";

$old_claps = 0;


while (true) {
  // Read from applause_state.txt, how many people are currently applauding
  $current_claps = get_number_from_file("applause_state_applauding_users.txt");
  
  
  if ($current_claps != $old_claps) {
	$lastEventId = $lastEventId+1;
	$old_claps = $current_claps;
	echo "id: " . $lastEventId . "\n";
	echo "data: $current_claps \n\n";
	ob_flush();
	flush();
  } else {
	// no new data to send
	echo ": heartbeat\n\n";
	ob_flush();
	flush();
  }
  usleep(200);
  // Break the loop if the client aborted the connection (closed the page)
  if ( connection_aborted() ) {
  	unregister_user();
  	break;
  }

}




function register_new_user() {
	increase_number_in_file("applause_state_registered_users.txt");
}
function unregister_user() {
	decrease_number_in_file("applause_state_registered_users.txt");
}
?>
<?php

/* 
	Source for Server Side Events: Currently registered users 
	
	applause_server.html connects (via applause_sse.js) to this ServerSideEvent and gets
	updated, when the number of registered (=connected) users is changed in 
	applause_current_users.txt.
	
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
	$lastEventId = floatval(isset($_GET["lastEventId"]) ? $_GET["lastEventId"] : 0);
}

// 2 kB padding for IE
echo ":" . str_repeat(" ", 2048) . "\n"; 
echo "retry: 2000\n";

$old_users = 0;


while (true) {
  // Read from applause_state.txt, how many event streams are currently online
  $current_users = get_number_from_file("applause_current_users.txt");
  
  
  if ($current_users != $old_users) {
	$lastEventId = $lastEventId+1;
	$old_users = $current_users;
	echo "id: $lastEventId \n";
	echo "data: $current_users \n\n";
	ob_flush();
	flush();
  } else {
	// no new data to send
	echo ": heartbeat\n\n";
	ob_flush();
	flush();
  }
  sleep(1);
  // Break the loop if the client aborted the connection (closed the page)
  if ( connection_aborted() ) {
  	break;
  }

}




?>
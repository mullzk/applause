<?php

/* 
	See: https://kevinchoppin.dev/blog/server-sent-events-in-php
		 https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events
		 https://www.kevssite.com/seamless-audio-looping/
*/


header("Content-Type: text/event-stream");
header("Cache-Control: no-cache");
header("Access-Control-Allow-Origin: *");

ignore_user_abort(true); // Stops PHP from checking for user disconnect


// Is this a new stream or an existing one?
$lastEventId = floatval(isset($_SERVER["HTTP_LAST_EVENT_ID"]) ? $_SERVER["HTTP_LAST_EVENT_ID"] : 0);
if ($lastEventId == 0) {
	$lastEventId = floatval(isset($_GET["lastEventId"]) ? $_GET["lastEventId"] : 0);
}

// 2 kB padding for IE
echo ":" . str_repeat(" ", 2048) . "\n"; 
echo "retry: 2000\n";

$old_claps = 0;


while (true) {
  // Read from applause_state.txt, how many people are currently applauding
  $myfile = fopen("applause_state.txt", "r");
  if ($myfile) {
  	$file_content = fgets($myfile);
	fclose($myfile);
  }
  $current_claps = intval(trim($file_content));
  
//  $current_claps = $old_claps + 1; // recurring increase in develop-Phase
  
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
  sleep(2);
}
?>
<?php
/*
	These functions provide a basic 'model' of an integer, stored in a text-file. 
	The functions are used by: 
		- applause_handler.php when adding or removing applause to 
		  applause_state_applauding_users.txt
		- applause_sse_applauding_users.php when reading the current applause (for sending
		  to the client) and when registering and registering clients in 
		  applause_state_registered_users.txt.
		- applause_sse_registered_users.php when reading registered users and sending to 
		  Admin-Website
	
	
	caveat: Due to the very simple demands of this project, the File-Handling is 
	very sloppy: 
		- We do not check wether the file exists. If it doesn't, the functions (except 
		  the simple setter) produce an E_WARNING and possibly crash. 
		- We do not not lock the File between reading and writing when we increase or
		  decrease the number

*/
function get_number_from_file($filepath) {
	return intval(trim(file_get_contents($filepath)));
}

function set_number_in_file($number, $filepath) {	
	return file_put_contents($filepath, $number);
}

function increase_number_in_file($filepath) {
	$number = get_number_from_file($filepath);
	set_number_in_file($number + 1, $filepath);
}

function decrease_number_in_file($filepath) {
	$number = get_number_from_file($filepath);
	if ($number > 0) {
		set_number_in_file($number - 1, $filepath);
	}
}

?>
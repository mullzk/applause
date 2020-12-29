<?php
header("Cache-Control: no-cache");
require("applause_numbers_in_file.php");

if (isset($_GET['action']) && $_GET['action'] == 'startApplauding') {
	 increase_number_in_file("applause_state.txt");	
} else if (isset($_GET['action']) && $_GET['action'] == 'stopApplauding') {
	 decrease_number_in_file("applause_state.txt");	
} else if (isset($_GET['action']) && $_GET['action'] == 'stopAllApplause') {
	 set_number_in_file(0, "applause_state.txt");
} else if (isset($_GET['action']) && $_GET['action'] == 'getApplause') {
	 get_number_from_file("applause_state.txt");	
}
?>

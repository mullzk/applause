<?php
/* 
   Applause-Handler is the Entry-Point to the Server Side of the Applause Tool. 
   It is called via Web-Requests with a 'action'-Parameter. 

   The action-Parameter is used for adding or removing a client from the 
   Applauding-Clients-List, which is done via increasing or decreasing the content-number 
   in applause_state_applauding_users.txt using the methods defined in 
   applause_numbers_in_file.php
*/

header("Cache-Control: no-cache");
require("applause_numbers_in_file.php");

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'startApplauding') {
	 increase_number_in_file("applause_state_applauding_users.txt");	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'stopApplauding') {
	 decrease_number_in_file("applause_state_applauding_users.txt");	
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'stopAllApplause') {
	 set_number_in_file(0, "applause_state_applauding_users.txt");
} else if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getApplause') {
	 get_number_from_file("applause_state_applauding_users.txt");	
}
?>

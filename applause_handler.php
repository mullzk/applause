<?php
header("Cache-Control: no-cache");

function get_applause() {
  $myfile = fopen("applause_state.txt", "r");
  if ($myfile && flock($myfile, LOCK_SH)) {
  	$file_content = fgets($myfile);
  	flock($myfile, LOCK_UN);
	fclose($myfile);
  }
  return intval($file_content);	
}


function set_applause($new_applause) {
	$myfile = fopen("applause_state.txt", "w");
	if ($myfile) {
		if (flock($myfile, LOCK_EX)) {
			fwrite($myfile, "$new_applause");
			fflush($myfile);
			flock($myfile, LOCK_UN);
			fclose($myfile);
			return true;
		} else {
			fclose($myfile);
		}
	}
	return false;
}


function increase_applause() {
	$myfile = fopen("applause_state.txt", "r+");
	if ($myfile) {
		if (flock($myfile, LOCK_EX)) {
			$file_content = fgets($myfile);
			$new_applause = trim($file_content) + 1;
			ftruncate($myfile, 0);
			fwrite($myfile, $new_applause);
			fflush($myfile);
			flock($myfile, LOCK_UN);
			fclose($myfile);
			return $new_applause;
		} else {
			fclose($myfile);
		}
	}
	return false;
}


function decrease_applause() {
	$myfile = fopen("applause_state.txt", "r+");
	if ($myfile) {
		if (flock($myfile, LOCK_EX)) {
			$file_content = fgets($myfile);
			$old_applause = intval(trim($file_content));
			if ($old_applause > 0) {
				$new_applause = $old_applause - 1;
				ftruncate($myfile, 0);
				fwrite($myfile, $new_applause);
			}
			fflush($myfile);
			flock($myfile, LOCK_UN);
			fclose($myfile);
			return $new_applause;
		} else {
			fclose($myfile);
		}
	}
	return false;
}
if (isset($_GET['action']) && $_GET['action'] == 'startApplauding') {
	 increase_applause();	
} else if (isset($_GET['action']) && $_GET['action'] == 'stopApplauding') {
	 decrease_applause();	
} else if (isset($_GET['action']) && $_GET['action'] == 'stopAllApplause') {
	 set_applause(0);
} else if (isset($_GET['action']) && $_GET['action'] == 'getApplause') {
	 get_applause();	
}
?>

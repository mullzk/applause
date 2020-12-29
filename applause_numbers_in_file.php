<?php
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
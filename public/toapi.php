<?php
/**
 * 
 * @authors Trym Lund Flogard (trym2001@hotmail.com)
 * @date    14/12/18 10:33:42
 */
echo $_SERVER['DOCUMENT_ROOT'];
set_include_path('');
include '../app/database.php';

function checkTimer() {
	global $conn;
	global $credentials;
	print date("H:i", time()) . '<br />';
	$currentDay = strtolower(date("D", time())); //current day in short format
	$time = date("H:i", time()); //time in 00:00 format
	//queries database for entries matching current machine time
	$timerTimesSql = "SELECT * FROM timer_heat WHERE t_time='$time' AND t_enable=1";
	$result = $conn->query($timerTimesSql);
	$dayArray = json_decode(mysqli_fetch_row($result)[3]);
	//checks if query returns result and current day matches
	if ($result->num_rows > 0 && in_array($currentDay, $dayArray)) {
		echo '<b>Starting Timer!</b>';
		echo 'day is in array';
		$output = "<pre>".shell_exec("voc -v $credentials heater start")."</pre>";
		echo $output;
	} else {echo 'oh no!';}


}

checkTimer();
?>
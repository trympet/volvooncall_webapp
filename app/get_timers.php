<?php
/**
 * 
 * @authors Trym Lund Flogard (trym2001@hotmail.com)
 * @date    11/12/18 22:12:17
 */
require_once '../app/database.php';
//include 'database.php';
header("Content-Type: application/json; charset=UTF-8");
function getTimersFromDatabase () {
	global $conn;
	$sqlGetTimers = "SELECT t_id, t_name, t_time, t_day, t_enable FROM timer_heat";
	$sqlGetTimersResult = $conn->query($sqlGetTimers);
	if ($sqlGetTimersResult->num_rows > 0) {
		$outp = $sqlGetTimersResult->fetch_all(MYSQLI_ASSOC);
		echo json_encode($outp);
	} else {
    echo "0 results";
	}
}
getTimersFromDatabase();
?>
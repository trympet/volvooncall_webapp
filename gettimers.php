<?php
/**
 * 
 * @authors Trym Lund Flogard (trym2001@hotmail.com)
 * @date    11/12/18 22:12:17
 */
include 'database.php';
header("Content-Type: application/json; charset=UTF-8");

function getTimersFromDatabase () {
	$sqlGetTimers = "SELECT t_name, t_time, t_day, t_repeat FROM timer_heat";
	$sqlGetTimersResult = $conn->query($sqlGetTimers);
	if ($sqlGetTimersResult->num_rows > 0) {
		$outp = $sqlGetTimersResult->fetch_all(MYSQLI_ASSOC);
		echo json_encode($outp);
	} else {
    echo "0 results";
	}
}

?>
<?php
/**
 * 
 * @authors Trym Lund Flogard (trym2001@hotmail.com)
 * @date    11/12/18 22:06:44
 */

/* EDIT FOLLOWING PARAMETERS FOR YOUR DATABASE */
$hostname = "localhost";
$username = "volvooncall";
$password = "volvooncall";
$database = "volvooncall";
$conn = mysqli_connect($hostname, $username, $password, $database);
if (!$conn) {
	echo "<script>console.log(\"failed to connect to database on $localhost\")</script>";
	die("Connection failed: " . $conn->connect_error);
}
?>
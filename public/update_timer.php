<?php
/**
 * RECIEVING POST DATA WHEN UPDATING TIMER
 * @authors Trym Lund Flogard (trym2001@hotmail.com)
 * @date    13/12/18 14:04:25
 */

/* posted variable should be 'timerData'! */
require_once '../app/database.php';
global $conn;
$timerData = json_decode($_POST['timerData']);
$oldName = $timerData->{"oldName"};
$name = $timerData->{"name"};
$time = $timerData->{"time"};
$days = json_encode($timerData->{"days"});
$enable = $timerData->{"enable"};
if ($enable === "") {$enable=0;}
echo $name, $time, $days, $enable, $oldName;



//create new timer
if (!$oldName) {
  $newTimer = "INSERT INTO timer_heat (t_name, t_time, t_day, t_enable)
  VALUES ('$name', '$time', '$days', '$enable')";
  if ($conn->query($newTimer) === TRUE) {
    echo "Record updated successfully";
  } else {
      echo "Error updating record: " . $conn->error;
  }
} else {
  $sql1 = "UPDATE timer_heat SET t_time='$time' WHERE t_name='$oldName'";
  $sql2 = "UPDATE timer_heat SET t_day='$days' WHERE t_name='$oldName'";
  $sql3 = "UPDATE timer_heat SET t_enable='$enable' WHERE t_name='$oldName'";
  $sql4 = "UPDATE timer_heat SET t_name='$name' WHERE t_name='$oldName'";

  if (($conn->query($sql1) && $conn->query($sql2) && $conn->query($sql3) && $conn->query($sql4)) === TRUE) {
    echo "Record updated successfully";
  } else {
      echo "Error updating record: " . $conn->error;
  }
}

//update existing timer



?>
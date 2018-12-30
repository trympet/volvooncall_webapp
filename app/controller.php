<?php
require_once 'config/database.php';
global $conn;
global $_POST;

function login ($postData) {
  var_dump($postData);
  if ($postData['username'] === 'admin' && $postData['password'] === 'admin') {
    header("Location: /dashboard");
  } else { return; }
}


function deleteTimer ($id) {
  global $conn;
  $sql = "DELETE FROM timer_heat WHERE t_id=$id";
  if ($conn->query($sql)) {
    return true;
  } else { return false; }
}

function getTimersFromDatabase () {
  global $conn;
  //header("Content-Type: application/json; charset=UTF-8");
  $sqlGetTimers = "SELECT t_id, t_name, t_time, t_day, t_enable FROM timer_heat";
  $sqlGetTimersResult = $conn->query($sqlGetTimers);
  if ($sqlGetTimersResult->num_rows > 0) {
    $outp = $sqlGetTimersResult->fetch_all(MYSQLI_ASSOC);
    return json_encode($outp);
  } else {
    return "0 results";
  }
}

function updateTimer () {
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
}

?>
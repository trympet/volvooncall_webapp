<?php
require __DIR__ . '/../controller.php';

function checkTimer() {
  global $client;
  global $conn;
  global $credentials;
  print date("H:i", time()) . '<br />';
  $time = date("H:i", time());
  $currentDay = strtolower(date("D", time())); //current day in short format
  //queries database for entries matching current machine time
  $timerTimesSql = "SELECT * FROM timer_heat WHERE t_time='$time' AND t_enable=1";
  $result = $conn->query($timerTimesSql);
  $dayArray = json_decode(mysqli_fetch_row($result)[3]);
  if ($result->num_rows > 0 && in_array($currentDay, $dayArray)) {
    echo '<b>Starting Timer!</b>';
    echo 'day is in array';
    $request = $client->startHeater();
    //logs that timer is started
    toLog('Timer started at '.date("F j, Y, g:i a").PHP_EOL);
    toLog('Output from API: '.$request.PHP_EOL);
  } else {echo 'no timers set to activate now';}
}

function toLog($data) {
  file_put_contents (__DIR__ . '/../log/voc_log.txt' , $data, FILE_APPEND | LOCK_EX);
}
checkTimer();

?>
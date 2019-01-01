<?php
//require_once '../app/database.php';
$request = $_SERVER['REQUEST_URI'];
require_once '../app/controller.php';
global $_POST;
global $client; //client for api calls
switch ($request) {
  case '/' : 
    header("Location: /dashboard");
    die();
    break;
  case '' : 
    header("Location: /dashboard");
    die();
    break;
  case '/login' : 
    if (isset($_POST['username']) && !empty($_POST['password'])) {
      echo login($_POST);
    } else {
    require '../app/views/login.html';
    }
    break;
/* functioncalls */
  case '/dashboard/delete' :
    echo deleteTimer($_POST['id']);
    break;
  case '/dashboard/get_timers' : 
    echo getTimersFromDatabase();
    break;
  case '/dashboard/update' : 
    echo updateTimer();
    break;
  case '/dashboard' : 
    require '../app/views/dashboard.html';
    break;
  case '/api' : 
    echo 'no method specified';
    break;
  case '/api/heater/start' :
    echo $client->startHeater();
    break;
  case '/api/heater/stop' :
    echo $client->stopHeater();
    break;
  default:
    echo '404 not found';
    break;
}
?>
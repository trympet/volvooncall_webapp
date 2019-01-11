<?php
require_once __DIR__ . '/controller.php';
require_once __DIR__ . '/authcontroller.php';

global $_POST;
global $client; //client for api calls

$request = $_SERVER['REQUEST_URI'];
if ( isset( $_SESSION['user_id'] ) ) {
  switch ($request) {
    // Grab user data from the database using the user_id
    // Let them access the "logged in only" pages
    case '/' : 
      header("Location: /dashboard");
      die();
      break;
    case '' : 
      header("Location: /dashboard");
      die();
      break;
  /* functioncalls */
    case '/configure/user/update' :
      $username = json_decode($_POST['creds'])->username;
      $password = json_decode($_POST['creds'])->password;
      echo updateCredentials($password, $username);
      break;
    case '/configure/voc/get' :
      echo getVocCredentials();
      break;
    case '/configure/voc/update' :
      $username = json_decode($_POST['vocCreds'])->username;
      $password = json_decode($_POST['vocCreds'])->password;
      echo updateVocCredentials($password, $username);
      break;
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
    case '/login' : 
      header("Location: /dashboard");
      die();
      break;
    case '/logout' :
      session_unset();
      session_destroy();
      exit('logged out');
    default:
      echo '404 not found';
      break;
  }
} else {
  switch ($request) {
    case '/login' :
      login($_POST);
      require '../app/views/login.html';
      break;
    case '' :
      header("Location: /login");
      die();
      break;
    case '/' :
      header("Location: /login");
      die();
      break;
    default : 
      echo '404 not found';
      break;
  }



}
?>
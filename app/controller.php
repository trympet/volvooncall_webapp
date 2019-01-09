<?php
require_once 'config/database.php';
require_once __DIR__ . '/../vendor/autoload.php';
use GuzzleHttp\Client;

global $conn;
global $_POST;

// function login ($postData) {
//   var_dump($postData);
//   if ($postData['username'] === 'admin' && $postData['password'] === 'admin') {
//     header("Location: /dashboard");
//     die();
//   } else { return; }
// }


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

function getVocCredentials () {
  global $conn;
  $sql = $conn->query("SELECT email, region FROM voc_api_auth WHERE voc_id = 0");
  $res = $sql->fetch_all(MYSQLI_ASSOC);
  return json_encode($res);

}

function updateVocCredentials($vocCredsJson) {
  global $conn;
  $creds = json_decode($vocCredsJson);
  $email = $creds->vocEmail;
  $password = $creds->vocPassword;
  var_dump($creds);
  switch ($creds->vocRegion) {
    case '0':
      $region = "na";
      break;
    case '1':
      $region = "cn";
      break;
    case '2': //eu
      $region = ""; 
      break;
    default:
      return('Incorrect region');
      break;
  }

  $sql1 = "UPDATE voc_api_auth SET email='$email' WHERE voc_id='0'";
  $sql2 = "UPDATE voc_api_auth SET password='$password' WHERE voc_id='0'";
  $sql3 = "UPDATE voc_api_auth SET region='$region' WHERE voc_id='0'";
  if (($conn->query($sql1) && $conn->query($sql2) && $conn->query($sql3)) === TRUE) {
    return ('success!');
  }
}

//sends http requests to VOC REST API
class vocApi 
{
  public function __construct() {
  //username and password from database
  $this->vocUser = '';
  $this->vocPass = '';
  $this->region = ""; //default region none
  $this->service_url = "https://vocapi".$this->region.".wirelesscar.net/customerapi/rest/v3.0/";
  $this->client = new client([
    'base_uri' => $this->service_url,
    'timeout'  => 30.0,
    'headers' => [
      "X-Device-Id" => "Device",
      "X-OS-Type" => "Android",
      "X-Originator-Type" => "App",
      "X-OS-Version" => "22",
      "Content-Type" => "application/json",
    ],
  'auth' => [$this->vocUser, $this->vocPass]
  ]); //client object used for all requests
  }
  function getVehicleId () {
    $res = $this->client->request('GET', 'customeraccounts'); //responds with JSON
    $resJson = json_decode($res->getBody());
    //accountvehiclerelations is array of cars in account
    $vehicleRes = $this->client->request('GET', $resJson->accountVehicleRelations[0]);
    $vehicleJson = json_decode($vehicleRes->getBody());
    //the vehicle object is base-url for api calls
    return $vehicleJson->vehicle;
  }

  function apiCall ($uri) {
    //base-url for api calls
    $vehicle = $this->getVehicleId();
    $res = $this->client->request('POST', $vehicle . $uri);
    return $res->getStatusCode() . PHP_EOL . $res->getBody();
  }
  /* API calls */
  public function startHeater() {
    return $this->apiCall('/heater/start');
  }
  public function stopHeater() {
    return $this->apiCall('/heater/stop');
  }
}
$client = new vocApi;
?>
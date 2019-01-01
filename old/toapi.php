<?php
/**
 * 
 * @authors Trym Lund Flogard (trym2001@hotmail.com)
 * @date    14/12/18 10:33:42
 */
require_once __DIR__ . '/../vendor/autoload.php';
use GuzzleHttp\Client;


function vocApi () {
 	global $client;
  $region = ""; //default region none
  $service_url = "https://vocapi".$region.".wirelesscar.net/customerapi/rest/v3.0/";
  $client = new client([
  	'base_uri' => $service_url,
  	'timeout'  => 30.0,
  	'headers' => [
			"X-Device-Id" => "Device",
			"X-OS-Type" => "Android",
			"X-Originator-Type" => "App",
			"X-OS-Version" => "22",
			"Content-Type" => "application/json",
		],
	'auth' => ['tom.lund@e7online.no', 'Login10022']
  ]); //client object used for all requests

  function getVehicleId () {
  	global $client;
  	//HTTP request to VOC REST API
  	$res = $client->request('GET', 'customeraccounts'); //responds with JSON
  	$resJson = json_decode($res->getBody());
  	//accountvehiclerelations is array of cars in account
  	$vehicleRes = $client->request('GET', $resJson->accountVehicleRelations[0]);
  	$vehicleJson = json_decode($vehicleRes->getBody());
  	//the vehicle object is base-url for api calls
  	return $vehicleJson->vehicle;
  }

  function apiCall ($uri) {
  	global $client;
  	//base-url for api calls
  	$vehicle = getVehicleId();
  	$res = $client->request('POST', $vehicle . $uri);
  	return $res->getStatusCode();
 	}

 	function startHeater() {
 		return apiCall('/heater/start');
 	}
}
vocApi();
?>
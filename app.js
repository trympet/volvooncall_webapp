/**
 * 
 * @authors Trym Lund Flogard (trym2001@hotmail.com)
 * @date    10/12/18 20:51:48
 */
/*jshint esversion: 6*/
document.addEventListener('DOMContentLoaded', getTimers);
let timersFromDatabase = {};

//get timers from database
function getTimers () {
  let timersRequest = new XMLHttpRequest();
  timersRequest.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
          timersFromDatabase = JSON.parse(this.responseText);
    }
  }
  timersRequest.open("GET", document.location.href + "gettimers.php?"
   + Math.random(), true);
  timersRequest.send();
}

//function for toggling timer modal
function toggleTimerModal (timer) {

}

//edit timer
function editTimerData () {
  let timerRequest = new XMLHttpRequest();
  timerRequest.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
          document.alert(this.responseText);
    }
  }
  timerRequest.open("GET", document.location.href + "demo_get.php?timerName=test&t="
   + Math.random(), true);
  timerRequest.send();
}
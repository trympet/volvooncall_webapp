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
          displayTimers(); //once response recived, call function displayTimers
          console.log('fetched!');
    }
  }
  timersRequest.open("GET", document.location.href + "gettimers.php?"
   + Math.random(), true);
  timersRequest.send();
}

//prints timers inside timer field
function displayTimers () {
  const TIMERFIELD = document.getElementById('timers');
  //for each row in mysql database, print timer
  for (let i = 0; timersFromDatabase.length > i; i++) {
    console.log(i)
    let timerDiv = document.createElement('DIV');
    timerDiv.className = "timer";
    timerDiv.innerHTML = `${timersFromDatabase[i].t_name}
      <button type="button" value="${timersFromDatabase[i].t_name}" onclick="editTimer(this.value)" class="float-right btn btn-info">
      <i class="fas fa-edit"></i><span>Edit</span></button>`;
    TIMERFIELD.appendChild(timerDiv);
  }
}

function editTimer (a) {
  console.log(a);
  $('#heat-timer-modal').modal();
  let modalContent = document.getElementById('heat-timer-modal').querySelector('div[class="modal-body"]');
  console.log(modalContent);
  

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
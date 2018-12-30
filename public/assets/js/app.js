/**
 * 
 * @authors Trym Lund Flogard (trym2001@hotmail.com)
 * @date    10/12/18 20:51:48
 */
/*jshint esversion: 6*/
document.addEventListener('DOMContentLoaded', getTimers);

let timersFromDatabase = {};
let oldName;
let indicator = {};
//get timers from database
function getTimers () {
  const TIMERFORM = document.getElementById('timer-edit-form');
  $('#heat-timer-modal').on('hidden.bs.modal', function () {
        TIMERFORM.reset();
  });
  document.getElementById('submit-timer-edit-form').addEventListener('click', setTimerData);
  let timersRequest = new XMLHttpRequest();
  timersRequest.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
          timersFromDatabase = JSON.parse(this.responseText);
          displayTimers(); //once response recived, call function displayTimers
          console.log('fetched!');
    }
  }
  timersRequest.open("GET", document.location.href + "/get_timers", true);
  timersRequest.send();
}

//prints timers inside timer field
function displayTimers () {
  const TIMERFIELD = document.getElementById('timers');
  //removes old records when pulling new ones
  TIMERFIELD.innerHTML = '';
  //for each row in mysql database, print timer
  for (let i = 0; timersFromDatabase.length > i; i++) {
    console.log(i)
    let timerDiv = document.createElement('DIV');
    timerDiv.className = "timer";
    timerDiv.setAttribute('data-name', timersFromDatabase[i].t_name);
    timerDiv.id = 'timer-' + timersFromDatabase[i].t_id;
    timerDiv.innerHTML = `${timersFromDatabase[i].t_name}
      <button type="button" value="${timersFromDatabase[i].t_id}" onclick="deleteTimer(this)" class="delete-button float-right btn btn-danger">
        <i class="fas fa-trash-alt"></i>
      </button>
      <button type="button" value="${i}" onclick="editTimer(this.value)" class="edit-button float-right btn btn-info">
      <i class="fas fa-edit"></i><span>Edit</span></button>`;
    TIMERFIELD.appendChild(timerDiv);
  }
}

function newTimer () {
  oldName = undefined;
  $('#heat-timer-modal').modal();
}

function deleteTimer (index) {
let id = +index.value;
let name = index.parentElement.getAttribute('data-name');
if(confirm(`Are you sure that you want to remove the timer "${name}"?`)) {
  $.ajax({
    url: '/dashboard/delete',
    type: 'POST',
    data: {id: id},
    error: function() {
      indicator.error('Error deleting timer')
    },
    success: function(data) {
      $("#timer-"+id).remove();
      $('#success-indicator').show(100);
      $('#success-indicator, #success-indicator *').delay(1000).hide(200)
    }
  });
}
$('#delete-timer-modal').modal();
}


//function for editing selected timer
function editTimer (index) {
  timerIndex = index;
  getTimerData(index);
  $('#heat-timer-modal').modal();
  let modalContent = document.getElementById('heat-timer-modal').querySelector('div[class="modal-body"]');
  console.log(modalContent);
}

//sets form fields to values in timersFromDatabase object
function getTimerData (index) {
  let name = timersFromDatabase[index].t_name;
  oldName = timersFromDatabase[index].t_name;
  let time = timersFromDatabase[index].t_time;
  let days = JSON.parse(timersFromDatabase[index].t_day);//.split(", "); console.log(days);
  let enable = timersFromDatabase[index].t_enable;

  document.getElementById('timer-name').value = name;
  document.getElementById('timer-time').value = time;
  if (enable) {
    document.getElementById('timer-enable').checked = true;
  } //checks days that are checked in TimersFromDatabase
  for (let i = 0; i < days.length; i++) {
    document.getElementById(days[i]).checked = true;
  }
}

function refresh () {
  location.reload();
}

//edit timer
function setTimerData (e) {
  Event.preventDefault;
  const TIMERFORM = document.getElementById('timer-edit-form');
  const TIMERDAYS = document.getElementById('timer-days');
  let name = TIMERFORM.querySelector('input[id="timer-name"]').value;
  let time = TIMERFORM.querySelector('input[id="timer-time"]').value;
  let days = [];
  //for each day that is checked, push to day array
  TIMERDAYS.querySelectorAll('input').forEach(function (e) {
    if (e.checked) {days.push(e.id)}
  })
  let enable = TIMERFORM.querySelector('input[id="timer-enable"]').checked;
  //object to post
  let postData = {oldName, name, time, days, enable};
  let postDataStringify = JSON.stringify(postData);
  //sending name, time, days and enable to update_timer.php
  $.ajax({
    url: '/dashboard/update',
    type: 'post',
    data: {timerData: postDataStringify},
    beforeSend: function() {
      $('#loading-indicator').show(100);
    },
    error: function() {
      indicator.error('Error setting timer data')
    },
    success: function(response){
      $('#loading-indicator').hide(100);
      $('#success-indicator').show(100);
      $('#success-indicator, #success-indicator *').delay(1000).hide(200);
      console.log(response);
      getTimers();
      $('#heat-timer-modal').modal("hide");
    }
  });
}
indicator.loading = bool => bool ? $('#loading-indicator').show(100) : $('#loading-indicator').hide(100);
indicator.success = function() {
 $('#success-indicator, #success-indicator *').show(100);
 $('#success-indicator, #success-indicator *').delay(1000).hide(200);
}
indicator.error = function(error) {
  $('#error-indicator, #error-indicator *').show(100);
  $('#error-indicator, #error-indicator *').delay(3500).hide(200);
  console.log('%c'+error, 'color: blue; font-weight: bold');
}

/*
indicator.loading = bool => bool ? $('#loading-indicator').show(100) : $('#loading-indicator').hide(100);
indicator.success = bool => bool ? $('#success-indicator').show(100) : $('#success-indicator, #success-indicator *').delay(1000).hide(200);
indicator.error = bool => bool ? $('#error-indicator').show(100) : $('#error-indicator, #error-indicator *').delay(1000).hide(200);*/
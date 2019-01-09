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
  document.getElementById('timer-edit-form').addEventListener('submit', setTimerData);
  document.getElementById('configure-form').addEventListener('submit', configure.save);

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
  timerStatus();
  const TIMERFIELD = document.getElementById('timers');
  //removes old records when pulling new ones
  TIMERFIELD.innerHTML = '';
  if (!timersFromDatabase.length) {
    TIMERFIELD.innerHTML = '<div class="timer">No timers are configured</div>';
  }
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

function timerStatus () {
  let TIMERSTATUS = document.getElementById('timer-status');
  TIMERSTATUS.innerHTML = '';
  if (!timersFromDatabase.length) {
    TIMERSTATUS.innerHTML = '<tr><th colspan="2">No timers are configured</th></tr>';
  } else {
    for (let i = 0; i < timersFromDatabase.length; i++){
      let tr = document.createElement('tr');
      let th = document.createElement('th');
      th.setAttribute('scope', 'row');
      th.innerHTML = timersFromDatabase[i].t_name;
      let td = document.createElement('td');
      if (timersFromDatabase[i].t_enable) {
        td.setAttribute('class', 'table-success');
        td.innerHTML = 'Active <i class="fas fa-check-circle"></i>';
      } else {
        td.setAttribute('class', 'table-danger');
        td.innerHTML = 'Disabled <i class="fas fa-times-circle"></i>';
      }
      TIMERSTATUS.appendChild(tr);
      TIMERSTATUS.lastElementChild.appendChild(th);
      TIMERSTATUS.lastElementChild.appendChild(td);
    }
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
      beforeSend: function() {
        indicator.loading(true);
      },
      error: function() {
        indicator.loading(true);
        indicator.error('Error deleting timer')
      },
      success: function(data) {
        indicator.loading(true);
        indicator.success();
        getTimers();
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
//edit timer
function setTimerData (e) {
  event.preventDefault();
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
      indicator.loading(true);
    },
    error: function() {
      indicator.loading(false);
      indicator.error('Error setting timer data')
    },
    success: function(response){
      indicator.loading(false);
      indicator.success();
      console.log(response);
      getTimers();
      $('#heat-timer-modal').modal("hide"); //hiding modal
    }
  });
}

let manualHeaterCall = new class {
  ajax () {
    $.ajax({
    url: '/api/' + this.url,
    type: 'get',
    beforeSend: function() {
      indicator.loading(true);
    },
    error: function() {
      indicator.loading(false);
      indicator.error('Error (see console log for details)');
      console.log(response);
    },
    success: function(response){
      indicator.loading(false);
        indicator.success();
    }
    });
  }
  start () {
    if(confirm(`Are you sure that you want to start the parking heater?`)) {
      this.url = 'heater/start';
      this.ajax();
    } 
  }
  stop () {
    this.url = 'heater/stop';
    this.ajax();
  }
}

configure = new class {
  open() {
    if (this.get()) {
      document.querySelector('input#voc-email').value = this.res.email;
      document.querySelector('select#voc-region').value = this.res.region;
    }
    document.querySelector('input#voc-password').value = '';
    $('#configure-modal').modal();
    
  }
  save(e) {
    e.preventDefault()
    console.log('saved!' + document.querySelector('#voc-email').value);
    let vocEmail = document.querySelector('input#voc-email').value;
    let vocPassword = document.querySelector('input#voc-password').value;
    let vocRegion = document.querySelector('select#voc-region').value;
    $.ajax({
      url: '/configure/update',
      type: 'post',
      data: {vocCreds: JSON.stringify({vocEmail, vocPassword, vocRegion})},
      beforeSend: function() {
        indicator.loading(true);
      },
      error: function() {
        indicator.loading(false);
        indicator.error('Error (see console log for details)');
        console.log(response);
      },
      success: function(response){
        indicator.loading(false);
        indicator.success();
      }
    });
    $('#configure-modal').modal('toggle'); //close modal
  }
  get () {
    $.ajax({
      url: '/configure/get',
      type: 'get',
      beforeSend: function() {
      },
      error: function(response) {
        indicator.error('Error (see console log for details)');
        console.log(response);
      },
      success: function(response){
        console.log(JSON.parse(response))
        this.res = JSON.parse(response)
      }
    });

  }
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

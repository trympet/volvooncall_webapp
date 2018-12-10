/**
 * 
 * @authors Trym Lund Flogard (trym2001@hotmail.com)
 * @date    10/12/18 20:51:48
 */
/*jshint esversion: 6*/
// document.addEventListener('DOMContentLoaded',
//     function(event){
//         function...
//         xxxx.addEventListener('submit', function....);  // hvis brukeren klikker knapp, kjør funksjon
// });

//function for toggling timer modal
function toggleTimerModal (timer) {

}

function loadTimerData () {
  let timerRequest = new XMLHttpRequest();
  timerRequest.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
          document.alert(this.responseText);
    }
  }
  timerRequest.open("GET", "demo_get.asp?timerName=test&t="
   + Math.random(), true);
  timerRequest.send();
}
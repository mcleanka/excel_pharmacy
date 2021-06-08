$(document).ready(function(){

});
  function showTime(){
  if (!document.all && !document.getElementById)
    return; 
  thelement = document.getElementById ? document.getElementById("Time") : document.all.Time;
  var digit = new Date(), hours = digit.getHours(), minutes = digit.getMinutes(), seconds = digit.getSeconds(), dn = '';
  if (hours < 12 ){
    dn = 'AM';
    if (hours <= 9) {
      hours   = "0" + hours;
      minutes = "" + minutes;
      seconds = "" + seconds;
    }else{
      hours   = hours;
      minutes = "" + minutes;
      seconds = "" + seconds;
    }
  }else{
    if(hours >= 12){
      dn='PM';
      hours = hours - 12;
      if (hours == 0) {
        hours = "12";
      }
      minutes = "" + minutes;
      seconds=""+seconds;
    }
  }
  if (minutes < 10) {
    minutes = "0" + minutes;
  }
  if (seconds < 10) {
    seconds = "0" + seconds;
  }
  var ctime = hours + ":" + minutes + ":" + seconds + " " + dn;
  thelement.innerHTML = ctime;
  setTimeout("showTime()", 1000)}
  window.onload = showTime;
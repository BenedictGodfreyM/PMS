if (document.readyStatus == 'loading') {
  document.addEventListener('DOMContentLoaded', ready)
}else{
  ready()
}

function ready(){
  //Refreshing date and time every 1000ms (1 second)
  setInterval(clockTick, 1000); 
}

function clockTick(){
  var currentTime = new Date(),
  month = currentTime.getMonth() + 1,
  day = currentTime.getDate(),
  year = currentTime.getFullYear(),
  hours = currentTime.getHours(),
  minutes = currentTime.getMinutes(),
  seconds = currentTime.getSeconds(),
  text = (/*day + "/" + ((month < 10) ? "0" : " ") + month + "/" + year +*/ " " + 
    ((hours < 10) ? "0" : " ") + hours + 
    ((minutes < 10) ? ":0" : ":") + minutes + 
    ((seconds < 10) ? ":0" : ":") + seconds + " Hrs");

  //Write the date and time in the "date" element
  document.getElementById("CLOCK").innerHTML = text;
}
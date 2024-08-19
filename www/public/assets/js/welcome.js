$(document).ready(function() {
    var url = "?url=login";
    var counter = 15;
    var display = document.getElementById("countdown");
    setInterval(function() {
        counter--;
        if(counter >= 0){
            display.innerHTML = counter;
        }
        if(counter == 0){
            clearInterval(counter);
            window.location = url;
        }
    }, 1000);
});
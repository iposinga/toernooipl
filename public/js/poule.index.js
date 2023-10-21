function highLight(teamnr){
    if($(".team_" + teamnr).hasClass("alert alert-warning"))
    {
        $("tr").removeClass("alert alert-warning");
        $("td").removeClass("bg-warning");
        //$("li").removeClass("alert alert-primary");
    }
    else
    {
        $("tr").removeClass("alert alert-warning");
        $("td").removeClass("bg-warning");
        //$("li").removeClass("alert alert-primary");
        $(".wedstr_" + teamnr).addClass("bg-warning");
        $(".team_" + teamnr).addClass("alert alert-warning");
    }
}

function clock(){
    let time = new Date()
    let hr = time.getHours()
    let min = time.getMinutes()
    let sec = time.getSeconds()
    if (hr < 10){
        hr = " " + hr
    }
    if (min < 10){
        min = "0" + min
    }
    if (sec < 10){
        sec = "0" + sec
    }
    document.clockForm.clockButton.value = hr + ":" + min
    setTimeout("clock()", 1000)
}

window.onload = clock;

function checklength(i) {
    'use strict';
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}
let minutes, seconds, count, counter;
count = 61; //seconds
counter = setInterval(timer, 1000);

function timer() {
    'use strict';
    count = count - 1;
    minutes = checklength(Math.floor(count / 60));
    seconds = checklength(count - minutes * 60);
    if (count < 0) {
        clearInterval(counter);
        return;
    }
    document.getElementById("timer").innerHTML = ' ' + minutes + ':' + seconds + ' ';
    if (count === 0) {
        location.reload();
    }
}

function pageloadEvery(t) {
    setTimeout('location.reload()', t);
}

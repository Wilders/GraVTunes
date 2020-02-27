/**
    $(document).ready(function(){
        $("#buttonPlayer").on('click',function (e) {
           player.show();
               //document.getElementById("content-wrapper").removeChild(document.getElementById("drivebar"));
               let driveBar = document.createElement("footer");
               driveBar.id = "drivebar";
               driveBar.className = "sticky-footer bg-primary";
               document.getElementById("content-wrapper").appendChild(driveBar);
        });
    });

*/

var run;
$(document).ready(function() {
    run = $('body').stickyAudioPlayer(
        {
            url:       'http://tiendasdigitales.net/github/stickyaudioplayerjquery/bensound-goinghigher.mp3',
            position:  'bottom', //'bottom'|'top'|'inline'
            text:      'Bensound - Going Higher - Music: http://www.bensound.com',
            image:     'http://tiendasdigitales.net/github/stickyaudioplayerjquery/images/cover.png',
            volume:    40,
            repeat:    false,
        }
    );
});

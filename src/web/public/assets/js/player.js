var player;
$(function () {
    player = $('body').stickyAudioPlayer({
        url: 'http://tiendasdigitales.net/github/stickyaudioplayerjquery/bensound-goinghigher.mp3',
        position: 'bottom',
        text: 'Bensound - Going Higher - Music: http://www.bensound.com',
        image: 'http://tiendasdigitales.net/github/stickyaudioplayerjquery/images/cover.png',
        maxWidth: 1000
    });
    $('.playBtn').each(function (i,e) {
        $(e).on('click', function () {
            let file = $(this).data('file');
            let title = $(this).data('title');
            let cover = $(this).data('cover');
            player.changeAudio(file, title, cover);
        });
    });
});

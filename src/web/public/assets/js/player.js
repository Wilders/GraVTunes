let player;
$(function () {
    $('.playBtn').each(function (i,e) {
        if(i === 1) {
            player = $('body').stickyAudioPlayer({
                url: $(e).data('file'),
                position: 'bottom',
                text: $(e).data('title'),
                image: $(e).data('cover'),
                maxWidth: 1000
            });
        }
        $(e).on('click', function () {
            let file = $(this).data('file');
            let title = $(this).data('title');
            let cover = $(this).data('cover');
            player.changeAudio(file, title, cover);
        });
    });
});
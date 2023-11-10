
$(document).ready(function () {
    console.log('Reloaded');

    const $allVideos = $("iframe[src^='http://88.183.212.133']");
    // The element that is fluid width
    const videocards = $(".videocard");
    const card = videocards[0];
    console.log(videocards);

    $allVideos.each(function() {
        let h = parseInt(this.height);
        let w = parseInt(this.width);
        $(this).data('aspectRatio', h / w)
            // and remove the hard coded width/height
            .removeAttr('height')
            .removeAttr('width');
        console.log(`aspect ratio set to ${h/w}`);
    });

    // When the window is resized
    $(window).resize(function() {
        console.log(`The new width is ${$(card).width()}`);
        const newWidth = $(card).width();
        // Resize all videos according to their own aspect ratio
        $allVideos.each(function() {  
            var $el = $(this);
            $el
                .width(newWidth)
                .height(newWidth * $el.data('aspectRatio'));  
        });
    // Kick off one resize to fix all videos on page load
    }).resize();
})


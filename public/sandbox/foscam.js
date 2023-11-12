
$(document).ready(function () {
    console.log('Reloaded');

    const $allVideos = $("iframe[src^='http://88.183.212.133']");
    // The element that is fluid width
    const videocards = $(".video--43");
    const card = videocards[0];
    console.log(videocards);

    $allVideos.each(function() {
        const parent = $(this).parent();
        let h = parseInt($(parent).height());
        let w = parseInt($(parent).width());
        // $(this).data('aspectRatio', h / w)
        //     // and remove the hard coded width/height
        //     .removeAttr('height')
        //     .removeAttr('width');
        $(this).attr('height', h).attr('width', w);
        console.log(`aspect ratio set to ${h/w}`);
    });

    // When the window is resized
    $(window).resize(function() {
        console.log(`The new width is ${$(card).width()}`);
        const newWidth = $(card).width();
        // Resize all videos according to their own aspect ratio
        $allVideos.each(function() {  
            const parent = $(this).parent();
            let h = parseInt($(parent).height());
            let w = parseInt($(parent).width());
            $(this).attr('height', h).attr('width', w);
            console.log(`aspect ratio set to ${h/w}`);                
        });
    // Kick off one resize to fix all videos on page load
    }).resize();
})


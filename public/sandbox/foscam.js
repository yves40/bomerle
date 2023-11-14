
$(document).ready(function () {
    console.log('Reloaded');

    const $allVideos = $("iframe[src^='http://88.183.212.133']");
    // The element that is fluid width
    const videocards = $(".video-43");
    const card = videocards[0];
    console.log(videocards);

    $allVideos.each(function() {
        const parent = $(this).parent();
        let h = parseInt($(parent).height());
        let w = parseInt($(parent).width());
        const ratio = w/h;
        console.log(`padding-top set to ${ratio}%`);
        $(parent).addClass('fluid-vids').css('padding-top', `${ratio}%`);
        $(this).removeAttr('height').removeAttr('width');
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
            const ratio = w/h;
            console.log(`padding-top set to ${ratio}%`);
            $(parent).addClass('fluid-vids').css('padding-top', `${ratio}%`);
            $(this).removeAttr('height').removeAttr('width');
        });
    // Kick off one resize to fix all videos on page load
    }).resize();
})


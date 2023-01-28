// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();
    console.log(`[${$props.version()} ]` );
    // -------------------------
    // Check knife images
    // -------------------------
    let nbimages = 0;
    $(".allimages img").each(function (indexInArray, element) {
        ++nbimages;
        let url = element.src;
        let filename = url.replace(/^.*[\\\/]/, '');
        let elemwidth = element.width;
        let elemheight = element.height;
        console.log(`Image file name : ${filename}`);
    });
    // -------------------------
    // Arm click handlers
    // -------------------------
    $(".allimages a").click(function (event) {
        event.preventDefault();
        actionRequest(this);
    });
    console.log(`Knife initially uses ${nbimages} images`);
})
// ------------------------------------------------------------- handler 
function actionRequest(element) {
    let url = $(element).attr('href');
    let feedbackmessage = $('#feedback');
    feedbackmessage.text('');
    console.log(`Remove image, url: ${url}`);
    $.ajax({
        type: "method",
        url: url,
        dataType: "json",
        async: false,
        success: function (response) {
            $(".allimages").fadeOut(500, () => {
                $(element).parent().remove();
                $(".allimages").fadeIn(500, () => {
                    feedbackmessage.text(`OK ${response.message} for knife ${response.knifeid} image : ${response.imageid}` );
                    feedbackmessage.addClass('ysuccess').removeClass('yerror');    
                });
            });
        },
        error: function (xhr) {
            feedbackmessage.text(`KO ${xhr.responseJSON.detail}` );
            feedbackmessage.addClass('yerror').removeClass('ysuccess');
            console.log(xhr.responseJSON.detail);
        }
    });
}

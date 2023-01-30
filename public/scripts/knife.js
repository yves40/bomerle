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
    let command = $(element).attr('id').split('-')[0];
    let id = $(element).attr('id').split('-')[1];
    switch(command) {
        case 'left': console.log($props.knifehandler() + `Move image left with ID: ${{id}}`);
                break;
        case 'right': console.log($props.knifehandler() + `Move image right with ID: ${{id}}`);
                break;
        case 'del': console.log('Delete image ');
                deleteImage(element);
                break;
        default: console.log('Unknown command'); 
                break;
    }
}
// ------------------------------------------------------------- Delete handler 
function deleteImage(element){
    let feedbackmessage = $('#feedback');
    feedbackmessage.text('');
    let url = $(element).attr('href');
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

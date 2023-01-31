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
    console.log($(element).attr('id'));
    let command = $(element).attr('id').split('-')[0];
    switch(command) {
        case 'left': moveLeft(element);
                break;
        case 'right': moveRight(element);
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
// ------------------------------------------------------------- Left handler 
function moveRight(element) {
    let knifeid = $(element).attr('id').split('-')[1];
    let imageid = $(element).attr('id').split('-')[2];
    let order = $(element).attr('id').split('-')[3];
    //
    console.log($props.knifehandler() + `RIGHT: ImageID: ${imageid} knifeID: ${knifeid} Order:${order}`);
}
// ------------------------------------------------------------- Right handler 
function moveLeft(element) {
    let knifeid = $(element).attr('id').split('-')[1];
    let imageid = $(element).attr('id').split('-')[2];
    let order = $(element).attr('id').split('-')[3];
    console.log($props.knifehandler() + `LEFT: ImageID: ${imageid} knifeID: ${knifeid} Order:${order}`);
}


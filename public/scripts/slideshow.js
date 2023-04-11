// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();
    console.log(`[${$props.version()} ]` );
    // -------------------------
    // Arm click handlers
    // -------------------------
    armIcons();
})
// -------------------------------------------------------------
// Handlers section
// -------------------------------------------------------------
const NOACTION = 0;
const RIGHTACTION = 1;
const LEFTACTION = 2;
// ------------------------------------------------------------- handler 
function actionRequest(element) {
    let command = $(element).attr('id').split('-')[0];
    switch(command) {
        case 'left': moveLeft(element);
                break;
        case 'right': moveRight(element);
                break;
        case 'del': deleteImage(element);
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
    // Get the selected action parameters
    const slideshowid = $(element).attr('id').split('-')[1];
    const selectedimageid = $(element).attr('id').split('-')[2];
    console.log(`Delete image, url: ${url} Slide ID : ${slideshowid} image ID: ${selectedimageid}`);
    // $.ajax({
    //     type: "POST",
    //     url: url,
    //     dataType: "json",
    //     async: false,
    //     success: function (response) {
    //         $(".allimages").fadeOut(500, () => {
    //             $(`#imgcard-${imgid}`).remove();
    //             $(".allimages").fadeIn(500, () => {
    //                 feedbackmessage.text(`OK ${response.message} for knife ${response.knifeid} image : ${response.imageid} Reordered : ${response.reordered}` );
    //                 feedbackmessage.addClass('ysuccess').removeClass('yerror');    
    //             });
    //         });
    //     },
    //     error: function (xhr) {
    //         feedbackmessage.text(`KO ${xhr.statusText}` );
    //         feedbackmessage.addClass('yerror').removeClass('ysuccess');
    //         console.log(xhr);
    //     }
    // });    
}
// ------------------------------------------------------------- Left handler 
function moveRight(element) {
    let url = $(element).attr('href');
    // Get the selected action parameters
    const slideshowid = $(element).attr('id').split('-')[1];
    const selectedimageid = $(element).attr('id').split('-')[2];
    const rank = $(element).attr('id').split('-')[3]
    // Build the current image list and move the image
    // moveImage(buildImagesList(selectedimageid, RIGHTACTION), url);
    // buildImagesList(selectedimageid, RIGHTACTION);
    console.log(`Move image Right, url: ${url} Slide ID : ${slideshowid} image ID: ${selectedimageid}/${rank}`);
}
// ------------------------------------------------------------- Right handler 
function moveLeft(element) {
    let url = $(element).attr('href');
    // Get the selected action parameters
    const slideshowid = $(element).attr('id').split('-')[1];
    const selectedimageid = $(element).attr('id').split('-')[2];
    const rank = $(element).attr('id').split('-')[3]
    // Build the current image list
    // moveImage(buildImagesList(selectedimageid, LEFTACTION), url);
    // buildImagesList(selectedimageid, LEFTACTION);
    console.log(`Move image Left, url: ${url} Slide ID : ${slideshowid} image ID: ${selectedimageid}/${rank}`);
}
// ------------------------------------------------------------- Set up icons handlers
function armIcons() {
    $("#commandzone a").click(function (event) {
        event.preventDefault();
        actionRequest(this);
    });
}

// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();
    console.log(`[${$props.version()} ]` );
    // -------------------------
    // Check knife images
    // -------------------------
    let nbimages = 0;
    $("img").each(function (indexInArray, element) {
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
    $("#commandzone a").click(function (event) {
        event.preventDefault();
        actionRequest(this);
    });
    console.log(`Knife initially uses ${nbimages} images`);
})
// ------------------------------------------------------------- handler 
function actionRequest(element) {
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
    let imgid = $(element).attr('id').split('-')[1];
    console.log(`Remove image, url: ${url}`);
    $.ajax({
        type: "POST",
        url: url,
        dataType: "json",
        async: false,
        success: function (response) {
            $(".allimages").fadeOut(500, () => {
                $(`#imgcard-${imgid}`).remove();
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
// -------------------------------------------------------------
// Handler section
// -------------------------------------------------------------
const NOACTION = 0;
const RIGHTACTION = 1;
const LEFTACTION = 2;
// ------------------------------------------------------------- Left handler 
function moveRight(element) {
    // Get the selected action element
    let selectedimageid = $(element).attr('id').split('-')[2];
    // Build the current image list
    let imagespayload = [];
    $(".container img").each(function (indexInArray, theimage) {
        imagespayload.push(getImageAtributes(theimage, selectedimageid, RIGHTACTION));
    });
    moveImage(imagespayload);
    console.log(`RIGHT : Transmit this list to the json service`);
}
// ------------------------------------------------------------- Right handler 
function moveLeft(element) {
    // Get the selected action element
    let selectedimageid = $(element).attr('id').split('-')[2];
    // Build the current image list
    let imagespayload = [];
    $(".container img").each(function (indexInArray, theimage) {
        imagespayload.push(getImageAtributes(theimage, selectedimageid, LEFTACTION));
    });
    moveImage(imagespayload);
    console.log(`LEFT : Transmit this list to the json service`);
}
// ------------------------------------------------------------- Image Data helper
function getImageAtributes(element, selectedimageid, requestedaction) {
    let imageid = $(element).attr('data-imageid');
    let file = $(element).attr('data-imagefile');
    let knifeid = $(element).attr('data-imageknifeid');
    let rank = $(element).attr('data-imagerank');
    let action = NOACTION
    if(selectedimageid === imageid) {
        action = requestedaction;
    }
    return { imageid: imageid, 
            file: file,
            knifeid: knifeid,
            rank: rank, 
            action: action
         }
}
// ------------------------------------------------------------- Move the selected image
function moveImage(imageslist) {
    console.log(`${JSON.stringify(imageslist)}`);
    $('.allimages').fadeOut(500, () => {
        imageslist.every((element, index) => {
            if(element.action !== NOACTION) {   
                let moveto = '';
                let imagemoved = imageslist[index];
                let imagetarget = {};
                if(element.action === RIGHTACTION) {
                    moveto = 'right';
                    imagetarget = imageslist[index+1];
                    imageslist[index] = imagetarget;
                    imageslist[index+1] = imagemoved;
                }
                else {
                    moveto = 'left';
                    imagetarget = imageslist[index-1];
                    imageslist[index] = imagetarget;
                    imageslist[index-1] = imagemoved;
                }
                return false;   // Job done get out
            }
            return true;
        });
        // $('.allimages').load(location.href + " #refreshzone")
        $('.allimages').fadeIn(500, () => {
            console.log('Image moved');
        })
    })
}
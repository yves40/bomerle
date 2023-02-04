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
    });
    // -------------------------
    // Arm click handlers
    // -------------------------
    armIcons();
})
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
// -------------------------------------------------------------
// Handlers section
// -------------------------------------------------------------
const NOACTION = 0;
const RIGHTACTION = 1;
const LEFTACTION = 2;
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
// ------------------------------------------------------------- Left handler 
function moveRight(element) {
    let url = $(element).attr('href');
    // Get the selected action element
    let selectedimageid = $(element).attr('id').split('-')[2];
    // Build the current image list
    let imagespayload = [];
    $(".container img").each(function (indexInArray, theimage) {
        imagespayload.push(getImageAtributes(theimage, selectedimageid, RIGHTACTION));
    });
    moveImage(imagespayload, url);
}
// ------------------------------------------------------------- Right handler 
function moveLeft(element) {
    let url = $(element).attr('href');
    // Get the selected action element
    let selectedimageid = $(element).attr('id').split('-')[2];
    // Build the current image list
    let imagespayload = [];
    $(".container img").each(function (indexInArray, theimage) {
        imagespayload.push(getImageAtributes(theimage, selectedimageid, LEFTACTION));
    });
    moveImage(imagespayload, url);
}
// -------------------------------------------------------------
// Helpers section
// -------------------------------------------------------------


// ------------------------------------------------------------- Retrieve image parameters
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
function moveImage(imageslist, url) {
    let feedbackmessage = $('#feedback');
    $('.allimages').fadeOut(500, () => {
        for(index = 0; index < imageslist.length; ++index) {
            let element = imageslist[index];
            if(element.action !== NOACTION) {   
                let imagemoved = imageslist[index];
                let imagetarget = {};
                if(element.action === RIGHTACTION) {
                    imagetarget = imageslist[index+1];
                    imagetarget.rank = imagemoved.rank;
                    imagemoved.rank++;
                    imageslist[index] = imagetarget;
                    imageslist[index+1] = imagemoved;
                }
                else {
                    imagetarget = imageslist[index-1];
                    imagetarget.rank = imagemoved.rank;
                    imagemoved.rank--;
                    imageslist[index] = imagetarget;
                    imageslist[index-1] = imagemoved;
                }
                break;
            }
        }
        // ----------------------------- Server DB update
        let payload = {
            "imagedata" :  imageslist
        }
        $.ajax({
            type: "POST",
            url: url,
            dataType: "json",
            data: JSON.stringify(payload),
            contentType: 'application/json',
            async: false,
            success: function (response) {
                reloadImages(imageslist);
                $(".allimages").fadeIn(500, () => {
                    feedbackmessage.text(`OK ` );
                    feedbackmessage.addClass('ysuccess').removeClass('yerror');    
                });
            },
            error: function (xhr) {
                $(".allimages").fadeIn(500, () => {
                    console.log(xhr.responseJSON.detail);
                    feedbackmessage.text(`KO ${xhr.responseJSON.detail}` );
                    feedbackmessage.addClass('yerror').removeClass('ysuccess');
                });
            }
        });
    });
}
// ------------------------------------------------------------- Move the selected image
function reloadImages(imageslist) {
    console.log(`********************* Reloading ${imageslist.length} image(s)`);
    $("#refreshzone .row .col-sm").each(function (indexInArray, element) {
        let elementid = $(element).attr('id')
        console.log(`Removing image card ${elementid}`);
        $(element).remove();
    });
    // Prepare command icons
    let lefticonbutton = document.createElement("a");
    let lefticon = document.createElement("ion-icon");
    lefticon.setAttribute('name', 'arrow-back-circle-outline');

    let deletebutton = document.createElement("a");
    let deleteicon = document.createElement("ion-icon");
    deleteicon.setAttribute('name', 'trash-bin-outline')

    let righticonbutton = document.createElement("a");
    let righticon = document.createElement("ion-icon");
    righticon.setAttribute('name', 'arrow-forward-circle-outline');

    // Images load
    imageslist.forEach((imgcard, index) => {
        // Build the card container
        let newdiv = document.createElement("div");
        // Outer div
        newdiv.id = "imgcard-" + imgcard.imageid;
        newdiv.className = "col-sm";
        // The command icon zone
        let command = document.createElement("div");
        command.id = "commandzone";
        command.className = "mt-4";
        // The image
        let img = document.createElement("img");
        img.src = '/images/knife/' + imgcard.file;
        img.className = "imagesmall";
        img.setAttribute('data-imageid', imgcard.imageid);
        img.setAttribute('data-imagefile', imgcard.file);
        img.setAttribute('data-imageknifeid', imgcard.knifeid);
        img.setAttribute('data-imagerank', imgcard.rank);
        newdiv.appendChild(img);
        // Command zone icons
        // Left icon
        if(index !== 0) {
            lefticonbutton.setAttribute('id', "left-" + imgcard.knifeid + "-"
                                                 + imgcard.imageid + "-"
                                                 + imgcard.rank);
            lefticonbutton.setAttribute('href', '/bootadmin/knives/swapphotos');
            lefticonbutton.appendChild(lefticon);
            command.appendChild(lefticonbutton);
        }
        // Delete icon
        deletebutton.setAttribute('id', "del-" + imgcard.imageid);
        deletebutton.setAttribute('href', '/bootadmin/knives/removephoto/' 
                                                + imgcard.knifeid + '/' 
                                                + imgcard.imageid);
        deletebutton.appendChild(deleteicon);
        command.appendChild(deletebutton);
        // Right icon
        if(index !== imageslist.length - 1) { // Right icon
            righticonbutton.setAttribute('id', "right-" + imgcard.knifeid + "-"
                                                 + imgcard.imageid + "-"
                                                 + imgcard.rank);
            righticonbutton.setAttribute('href', '/bootadmin/knives/swapphotos');
            righticonbutton.appendChild(righticon);
            command.appendChild(righticonbutton);
        }
        // Insert the icons
        newdiv.appendChild(command);
        // Insert the whole card
        $("#refreshzone .row").append(newdiv);
        console.log(`Added image card ${imgcard.imageid}`);
    });
    armIcons();
}
// ------------------------------------------------------------- Set up icons handlers
function armIcons() {
    $("#commandzone a").click(function (event) {
        event.preventDefault();
        actionRequest(this);
    });
}

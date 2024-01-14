// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();
    console.log(`[${$props.version()} ]` );
    // -------------------------
    // Arm click handlers
    // -------------------------
    armIcons();
    armDeleteAlert();
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
                    feedbackmessage.text(`OK ${response.message} for knife ${response.knifeid} image : ${response.imageid} Reordered : ${response.reordered}` );
                    feedbackmessage.addClass('ysuccess').removeClass('yerror');    
                });
            });
        },
        error: function (xhr) {
            feedbackmessage.text(`KO ${xhr.statusText}` );
            feedbackmessage.addClass('yerror').removeClass('ysuccess');
            console.log(xhr);
        }
    });    
}
// ------------------------------------------------------------- Left handler 
function moveRight(element) {
    let url = $(element).attr('href');
    // Get the selected action element
    let selectedimageid = $(element).attr('id').split('-')[2];
    // Build the current image list and move the image
    moveImage(buildImagesList(selectedimageid, RIGHTACTION), url);
    // buildImagesList(selectedimageid, RIGHTACTION);
}
// ------------------------------------------------------------- Right handler 
function moveLeft(element) {
    let url = $(element).attr('href');
    // Get the selected action element
    let selectedimageid = $(element).attr('id').split('-')[2];
    // Build the current image list
    moveImage(buildImagesList(selectedimageid, LEFTACTION), url);
    // buildImagesList(selectedimageid, LEFTACTION);
}
// -------------------------------------------------------------
// Build the images refresh list from the DOM
// -------------------------------------------------------------
function buildImagesList(selectedimageid, action) {
    let imagespayload = [];
    $(".container .imagesmall").each(function (idx, theimage) {
        imagespayload.push(getImageAtributes(theimage, selectedimageid, action));
    });
    return imagespayload;
}
// -------------------------------------------------------------
// Helpers section
// ------------------------------------------------------------- 
// Retrieve image parameters and flag the moved image with the requested action
function getImageAtributes(element, selectedimageid, requestedaction) {
    let imageid = $(element).attr('data-imageid');
    let file = $(element).attr('data-imagefile');
    let knifeid = $(element).attr('data-imageknifeid');
    let rank = $(element).attr('data-imagerank');
    let action = NOACTION
    if(selectedimageid === imageid) {   // Image to be moved ? 
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
    // console.log("Enter move Image");
    // console.log(imageslist);
    let feedbackmessage = $('#feedback');
    let movingimageid = 0;
    let relatedimageid = 0;
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
            relatedimageid = imagetarget.imageid;
            movingimageid = element.imageid;
        break;
        }
    }
    swapImages(movingimageid, relatedimageid);
    // reloadImages(imageslist);
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
        async: true,
        success: function (response) {
            feedbackmessage.text(`OK ` );
            feedbackmessage.addClass('ysuccess').removeClass('yerror');    
        },
        error: function (xhr) {
            console.log(xhr.responseJSON.detail);
            feedbackmessage.text(`KO ${xhr.responseJSON.detail}` );
            feedbackmessage.addClass('yerror').removeClass('ysuccess');
        }
    });
}
// -------------------------------------------------------------
// This method work only on images being moved
// -------------------------------------------------------------
function swapImages(movingimageid, relatedimageid) {
    // Manage 2 cards, 2 images and 6 command icons
    let movingcard = null;
    let relatedcard = null;
    let movingimg = relatedimg = null;
    let movingleft, movingdel, movingright, relatedleft, relateddel, relatedright = null;
    $(`#imgcard-${movingimageid}`).each((idx, element) => {
        movingcard = element;
    });
    $(`#imgcard-${relatedimageid}`).each((idx, element) => {
        relatedcard = element;
    });
    $(`#imgcard-${movingimageid} .imagesmall`).each((idx, element) => {
        movingimg = element;
    });
    $(`#imgcard-${relatedimageid} .imagesmall`).each((idx, element) => {
        relatedimg = element;
    });
    $(`#imgcard-${movingimageid} a[id^=left]`).each((idx, element) => {
        movingleft = element;
    });
    $(`#imgcard-${movingimageid} a[id^=del]`).each((idx, element) => {
        movingdel = element;
    });
    $(`#imgcard-${movingimageid} a[id^=right]`).each((idx, element) => {
        movingright = element;
    });
    $(`#imgcard-${relatedimageid} a[id^=left]`).each((idx, element) => {
        relatedleft = element;
    });
    $(`#imgcard-${relatedimageid} a[id^=del]`).each((idx, element) => {
        relateddel = element;
    });
    $(`#imgcard-${relatedimageid} a[id^=right]`).each((idx, element) => {
        relatedright = element;
    });

    $(movingcard).hide();
    $(relatedcard).hide();

    // Swap images and their data
    let imgid1 = $(movingimg).attr('data-imageid');
    let imgfile1 = $(movingimg).attr('data-imagefile');
    let imgknifeid1 = $(movingimg).attr('data-imageknifeid');
    let imgrank1 = $(movingimg).attr('data-imagerank');

    let imgid2 = $(relatedimg).attr('data-imageid');
    let imgfile2 = $(relatedimg).attr('data-imagefile');
    let imgknifeid2 = $(relatedimg).attr('data-imageknifeid');
    let imgrank2 = $(relatedimg).attr('data-imagerank');

    $(movingimg).attr('src', $props.knifeimageslocation() + '/' + imgfile2);
    $(movingimg).attr('data-imageid', imgid2);
    $(movingimg).attr('data-imagefile', imgfile2);
    $(movingimg).attr('data-imageknifeid', imgknifeid2);
    $(movingimg).attr('data-imagerank', imgrank2);
    
    $(relatedimg).attr('src', $props.knifeimageslocation() + '/' + imgfile1);
    $(relatedimg).attr('data-imageid', imgid1);
    $(relatedimg).attr('data-imagefile', imgfile1);
    $(relatedimg).attr('data-imageknifeid', imgknifeid1);
    $(relatedimg).attr('data-imagerank', imgrank1);
    // Swap cards ID
    $(movingcard).attr('id', `imgcard-${imgid2}`);
    $(relatedcard).attr('id', `imgcard-${imgid1}`);
    // Swap command icons
    let atr1 = $(movingleft).attr('id');  // LEFT (change ID)
    let atr2 = $(relatedleft).attr('id');
    // If Moving or Related image was topleft we have to rebuild the icon ID
    if(atr1 === undefined) {
        atr1 = `left-${imgknifeid1}-${imgid1}-${imgrank1}`
    }
    if(atr2 === undefined) {
        atr2 = `left-${imgknifeid2}-${imgid2}-${imgrank2}`
    }
    $(movingleft).attr('id', atr2);
    $(relatedleft).attr('id', atr1);
    
    atr1 = $(movingdel).attr('href');     // DEL (change ID and URL)
    atr2 = $(relateddel).attr('href');
    $(movingdel).attr('href', atr2);
    $(relateddel).attr('href', atr1);
    atr1 = $(movingdel).attr('id');
    atr2 = $(relateddel).attr('id');
    $(movingdel).attr('id', atr2);
    $(relateddel).attr('id', atr1);

    atr1 = $(movingright).attr('id');     // RIGHT (Change ID)
    atr2 = $(relatedright).attr('id');
    // If Moving or Related image was topright we have to rebuild the icon ID
    if(atr1 === undefined) {
        atr1 = `right-${imgknifeid1}-${imgid1}-${imgrank1}`
    }
    if(atr2 === undefined) {
        atr2 = `right-${imgknifeid2}-${imgid2}-${imgrank2}`
    }
    $(movingright).attr('id', atr2);
    $(relatedright).attr('id', atr1);

    // Redisplay cards  
    $(movingcard).slideDown(1000);
    $(relatedcard).slideDown(1000);
    return;
}
// ------------------------------------------------------------- 
// Refresh all the images
// ------------------------------------------------------------- 
function reloadImages(imageslist) {
    console.log(`********************* Reloading ${imageslist.length} image(s)`);
    $("#refreshzone").hide();
    // Clear the command zone
    $("#refreshzone .row").remove();
    let divrow = document.createElement('div');
    divrow.className = 'row';
    $("#refreshzone").append(divrow);

    // Images load
    imageslist.forEach((imgcard, index) => {
        // Build the image card container
        let newdiv = buildCard(imgcard, index, imageslist.length - 1);
        // Insert the whole card
        $("#refreshzone .row").append(newdiv);
        console.log(`Added image card ${imgcard.imageid}`);
    });
    $("#refreshzone").slideDown(500);
    armIcons();
}
// ------------------------------------------------------------- 
// Build a card container for image and commands icons
// In the initial version, this function is used to reload all
// images after a move.
// ------------------------------------------------------------- 
function buildCard(imgcard, index, lastimageindex) {

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

        // The image card        
        let newdiv = document.createElement("div");
        newdiv.id = "imgcard-" + imgcard.imageid;
        newdiv.className = "col-sm";
        // The command icon zone
        let command = document.createElement("div");
        command.id = "commandzone";
        command.className = "mt-4";
        // The image
        let img = document.createElement("img");
        img.src = $props.knifeimageslocation() + '/' + imgcard.file;
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
            lefticonbutton.setAttribute('href', '/knives/protected/swapphotos');
            lefticonbutton.appendChild(lefticon);
            command.appendChild(lefticonbutton);
        }
        // Delete icon
        deletebutton.setAttribute('id', "del-" + imgcard.imageid);
        deletebutton.setAttribute('href', '/knives/protected/removephoto/' 
                                                + imgcard.knifeid + '/' 
                                                + imgcard.imageid);
        deletebutton.appendChild(deleteicon);
        command.appendChild(deletebutton);
        // Right icon
        if(index !== lastimageindex) { // Right icon
            righticonbutton.setAttribute('id', "right-" + imgcard.knifeid + "-"
                                                 + imgcard.imageid + "-"
                                                 + imgcard.rank);
            righticonbutton.setAttribute('href', '/knives/protected/swapphotos');
            righticonbutton.appendChild(righticon);
            command.appendChild(righticonbutton);
        }
        // Insert the icons
        newdiv.appendChild(command);
        return newdiv;
}
// ------------------------------------------------------------- Set up icons handlers
function armIcons() {
    $("#commandzone a").click(function (event) {
        event.preventDefault();
        actionRequest(this);
    });
}
// ------------------------------------------------------------- Set up icons handlers
function armDeleteAlert() {
    $(".deleteaction").click(function (event) {
        event.preventDefault();
        let knifeid = $(this).data('knifeid');
        let knifename = $(this).data('knifename');
        let modalbody = $(".modal-body p");
        modalbody.text(`${knifename} : ID : ${knifeid}`);
        $(".modalaction").attr('onclick', 
                    `window.location.href='/knives/protected/delete/${knifeid}'`);
    });
}

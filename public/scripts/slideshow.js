// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();
    console.log(`[${$props.version()} ]` );

    // Some variables to handle show/hide
    const showall = $('#showall');
    const hideall = $('#hideall');
    const slidename = $('#slide_show_name');        // Check existing or new slide show
    const slidermode = $('#slide_show_slider');     // Slider mode check box
    const gallerymode = $('#slide_show_gallery');   // gallery mode check box
    const daterange = $('#slide_show_daterange');   // Date restriction checkbox
    const days = $('.days');                        // Days selection 
    const thedates = $('.thedates');
    const sliderdelay = $('#sliderdelay');
    const slidingtime = $props.getSlidingTime();
    hideAll(slidingtime);
    manageDates();
    // -------------------------
    // Arm click handlers
    // -------------------------
    showall.click( (e) => { e.preventDefault(); showAll(slidingtime); });
    hideall.click( (e) => { e.preventDefault(); hideAll(slidingtime); });
    slidermode.click( (e) => { manageShowMode('s');  })
    gallerymode.click( (e) => { manageShowMode('g');  })
    daterange.click( (e)=> { manageDates();  })
    armIcons();
    // Set default show mode to slider if new show
    if($(slidename).val() == '') {
        $(slidermode).prop('checked', true);
    }
// ----------------------------------------------------------------------------
    // Handle show mode actions
    // ----------------------------------------------------------------------------
    function manageShowMode(target) {
        if(target == 's') { // Action on slider checkbox ?
            $(sliderdelay).fadeIn(700);
            if($(slidermode).prop('checked')) {
                $(gallerymode).prop('checked', false);
            }
            else {
                $(gallerymode).prop('checked', true);
            }
        }
        else {              // gallery check
            $(sliderdelay).fadeOut(700);
            if($(gallerymode).prop('checked')) {
                $(slidermode).prop('checked', false);
            }
            else {
                $(slidermode).prop('checked', true);
            }
    }
    }
    // ----------------------------------------------------------------------------
    // Handle date range activation
    // ----------------------------------------------------------------------------
    function manageDates() {
        if($(daterange).prop('checked')) {
            $(thedates).slideDown(slidingtime);
            $(days).slideDown(slidingtime);
        }
        else {
            $(thedates).slideUp(slidingtime);
            $(days).slideUp(slidingtime);
        }
    }
    // ------------------------------------------------------------- Zoom handler 
    function actionRequest(element) {
        const theslide = $(element).closest('.list-group-item');
        theslide.children('.details').toggle(slidingtime);
    }
    // ------------------------------------------------------------- Set up icons handlers
    function armIcons() {
        $(".zoom a").click(function (event) {
            event.preventDefault();
            actionRequest(this);
        });
        $(".zoom a").hover(function (event) {
            event.preventDefault();
            console.log('Show me the description');
        });
        $("#commandzone a").click(function (event) {
            event.preventDefault();
            imageRequest(this);
        });
    }
})
// ------------------------------------------------------------- Image move handler 
function imageRequest(element) {
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
// ----------------------------------------------------------------------------
// Show all messages details
// ----------------------------------------------------------------------------
function showAll(slidingtime) {
    $('.details').each(function (index, element) {
        // element == this
        $(this).slideDown(slidingtime);            
    });
}
// ----------------------------------------------------------------------------
// Hide all messages details
// ----------------------------------------------------------------------------
function hideAll(slidingtime) {
    $('.details').each(function (index, element) {
        // element == this
        $(this).slideUp(slidingtime);            
    });
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
    // Get the selected action parameters
    const slideshowid = $(element).attr('id').split('-')[1];
    const selectedimageid = $(element).attr('id').split('-')[2];
    console.log(`Delete image, url: ${url} Slide ID : ${slideshowid} image ID: ${selectedimageid}`);
    const payload = {
        "showid" :  slideshowid,
        "imageid": selectedimageid
    }
    $.ajax({
        type: "POST",
        url: url,
        data: JSON.stringify(payload),
        dataType: "json",
        async: false,
        success: function (response) {
            $(".allimages").fadeOut(500, () => {
                $(`#imgcard-${selectedimageid}`).remove();
                $(".allimages").fadeIn(500, () => {
                    feedbackmessage.text(`OK ${response.message} for slide ${response.showid} image : ${response.imageid} Reordered : ${response.reordered}` );
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
    // Get the selected action parameters
    const slideshowid = $(element).attr('id').split('-')[1];
    const selectedimageid = $(element).attr('id').split('-')[2];
    const rank = $(element).attr('id').split('-')[3]
    // Build the current image list and move the image
    moveImage(buildImagesList(selectedimageid, RIGHTACTION), url);
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
    moveImage(buildImagesList(selectedimageid, LEFTACTION), url);
    console.log(`Move image Left, url: ${url} Slide ID : ${slideshowid} image ID: ${selectedimageid}/${rank}`);
}
// -------------------------------------------------------------
// Helpers section
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
// Retrieve image parameters and flag the moved image with 
// the requested action
// -------------------------------------------------------------
function getImageAtributes(element, selectedimageid, requestedaction) {
    let imageid = $(element).attr('data-imageid');
    let file = $(element).attr('data-imagefile');
    let showid = $(element).attr('data-imageshowid');
    let rank = $(element).attr('data-imagerank');
    let action = NOACTION
    if(selectedimageid === imageid) {   // Image to be moved ? 
        action = requestedaction;
    }
    return { imageid: imageid, 
            file: file,
            showid: showid,
            rank: rank, 
            action: action
         }
}
// ------------------------------------------------------------- Move the selected image
function moveImage(imageslist, url) {
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
    // ----------------------------- Server DB update
    let payload = {
        "imagedata" :  imageslist
    }
    // console.log(` imagedata : ${JSON.stringify(payload)}`);
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
    let imgshowid1 = $(movingimg).attr('data-imageshowid');
    let imgrank1 = $(movingimg).attr('data-imagerank');

    let imgid2 = $(relatedimg).attr('data-imageid');
    let imgfile2 = $(relatedimg).attr('data-imagefile');
    let imgshowid2 = $(relatedimg).attr('data-imageshowid');
    let imgrank2 = $(relatedimg).attr('data-imagerank');

    $(movingimg).attr('src', '/images/slideshow/' + imgfile2);
    $(movingimg).attr('data-imageid', imgid2);
    $(movingimg).attr('data-imagefile', imgfile2);
    $(movingimg).attr('data-imageshowid', imgshowid2);
    $(movingimg).attr('data-imagerank', imgrank2);
    
    $(relatedimg).attr('src', '/images/slideshow/' + imgfile1);
    $(relatedimg).attr('data-imageid', imgid1);
    $(relatedimg).attr('data-imagefile', imgfile1);
    $(relatedimg).attr('data-imageshowid', imgshowid1);
    $(relatedimg).attr('data-imagerank', imgrank1);
    // Swap cards ID
    $(movingcard).attr('id', `imgcard-${imgid2}`);
    $(relatedcard).attr('id', `imgcard-${imgid1}`);
    // Swap command icons
    let atr1 = $(movingleft).attr('id');  // LEFT (change ID)
    let atr2 = $(relatedleft).attr('id');
    // If Moving or Related image was topleft we have to rebuild the icon ID
    if(atr1 === undefined) {
        atr1 = `left-${imgshowid1}-${imgid1}-${imgrank1}`
    }
    if(atr2 === undefined) {
        atr2 = `left-${imgshowid2}-${imgid2}-${imgrank2}`
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
        atr1 = `right-${imgshowid1}-${imgid1}-${imgrank1}`
    }
    if(atr2 === undefined) {
        atr2 = `right-${imgshowid2}-${imgid2}-${imgrank2}`
    }
    $(movingright).attr('id', atr2);
    $(relatedright).attr('id', atr1);

    // Redisplay cards  
    $(movingcard).slideDown(1000);
    $(relatedcard).slideDown(1000);
    return;
}

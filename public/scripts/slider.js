// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();
    console.log(`[ ${$props.sliderhandler()}  ]` );
    // Get slider parameters
    const imagesdivid = $(".sliderdata").data('imagesid');
    const showbuttons = $(".sliderdata").data('buttons');
    const mousehovermsg = $(".sliderdata").data('mousehovermsg');
    const mouseoutmsg = $(".sliderdata").data('mouseoutmsg');
    const buttonshowid = 'showslider';
    const buttonhideid = 'hideslider';
    const buttonhide = $(`#${buttonhideid}`);
    const buttonshow = $(`#${buttonshowid}`);
    const slidescontainer = $("#yslider");
    const sliderstatus = $("#sliderstatus p");
    const indicators = $(".carousel-indicators");   // Genuine bootstrap class
    const slideinterval = $(".sliderdata").data('interval');
    // Initialize
    $(slidescontainer).hover(
        () => {     // In
            sliderstatus.text(mousehovermsg)
                .addClass('sliderpaused')
                .removeClass('sliderrunning');
        },
        () => {     // out
            sliderstatus.text(mouseoutmsg)
            .addClass('sliderrunning')
            .removeClass('sliderpaused');
        }
    )

    $(slidescontainer).hide();
    $('.carousel-inner').remove();
    let imagesArray = getImages(imagesdivid);
    sliderstatus.text(mouseoutmsg).addClass('sliderrunning');
    // Activate
    if(showbuttons === true) {
        $(buttonhide).hide();
        $(buttonshow).show();
        $(slidescontainer).hide();
        addImages(imagesArray);
        $(buttonshow).click(function (e) { 
            e.preventDefault();
            $(buttonshow).hide();
            $(buttonhide).show();
            $(slidescontainer).show();
            addImages(imagesArray);
        });
        $(buttonhide).click(function (e) { 
            e.preventDefault();
            $(buttonshow).show();
            $(buttonhide).hide();
            $(slidescontainer).hide();
            removeImages();
        });
    }
    else {
        $(buttonshow).hide();
        $(buttonhide).hide();
        addImages(imagesArray);
        $(slidescontainer).show();
    }
    // ----------------------------------------------------------------------------
    // Search for images contained in the transmitted DOM source
    // source is the ID of a containing element
    // ----------------------------------------------------------------------------
    function getImages(source) {
        let thelist = [];
        $(`#${source} img`).each(function (index, element) {
            thelist.push(this);
        });
        return thelist;
    }
    // ----------------------------------------------------------------------------
    // Add images to the slider
    // ----------------------------------------------------------------------------
    function addImages(imglist) {
        let slides = $("<div>").addClass('carousel-inner').addClass('yinner');
        $(slidescontainer).append(slides);
        imglist.forEach( (element, index) => {
            let item = $("<div>").addClass('carousel-item');
            $(item).attr('data-bs-interval', slideinterval);
            let caption = $("<div>").addClass('carousel-caption');
            let knifename = $(element).data('knifename');
            let h3 = $("<div>").text(`${knifename} ${index+1}/${imglist.length}`);
            caption.append(h3);
            item.append(caption);
            let buttonindicator = $("<button>").attr('type', 'button');
            buttonindicator.attr('data-bs-target', '#yslider').attr('data-bs-slide-to', index); 
            if(index === 0) {
                $(item).addClass('active');
                buttonindicator.addClass('active');
            }
            $(indicators).append(buttonindicator);
            let newimg = $('<img>');
            let imgid = 'slideid-' + $(element).data('imageid');
            let imgsrc = $(element).attr('src');
            $(newimg).attr('id', imgid)
                    .addClass('responsive')
                    .attr('src', imgsrc);
            $(item).append(newimg);
            $(slides).append(item);
        });
    }
    // ----------------------------------------------------------------------------
    // Add images to the slider
    // ----------------------------------------------------------------------------
    function removeImages() {
        $('.carousel-inner').remove();
        $('.carousel-indicators button').each(function (index, element) {
            // element == this
            $(this).remove();
        });
    }
})

// // Track window resize
// window.onresize =  () =>  {
//     slideWidth = currentSlideWidth;
//     // Place photos with the new window size
//     slides.forEach( setSlidePosition );
//     let currentslide = document.getElementsByClassName('current-slide carousel-slide')[0];
//     let theimg = currentslide.firstElementChild.getAttribute('src');
//     console.log(`Resized : Current image : ${theimg}`);
//     moveToSlide(track, currentslide, currentslide);
// };

// /*
//       R E S E R V O I R    C O D E 

    
// setInterval(() => {
//   let currentSlideWidth = slides[0].getBoundingClientRect().width;
//   if(currentSlideWidth !== slideWidth) {
//     slideWidth = currentSlideWidth;
//     // Place photos with the new window size
//     slides.forEach( setSlidePosition );
//     let currentslide = document.getElementsByClassName('current-slide carousel-slide')[0];
//     let theimg = currentslide.firstElementChild.getAttribute('src');
//     console.log(`Resized : Current image : ${theimg}`);
//     moveToSlide(track, currentslide, currentslide);
//   }
// }, 500);
// */



// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();
    console.log(`[ ${$props.sliderhandler()}  ]` );
    // Get slider parameters
    const VERYLONGTIMESLIDER = 60000;
    const imagesdivid = $(".sliderdata").data('imagesid');
    const automove = $(".sliderdata").data('auto');
    let slideinterval = $(".sliderdata").data('interval');
    const mousehovermsg = $(".sliderdata").data('mousehovermsg');
    const mouseoutmsg = $(".sliderdata").data('mouseoutmsg');
    const slidescontainer = $("#sliderzone");
    const sliderstatus = $("#sliderstatus p");
    const indicators = $(".carousel-indicators");   // Genuine bootstrap class
    // Do not want animated slider ? 
    if(!automove) {
        slideinterval = VERYLONGTIMESLIDER; // Almost disable auto scroll
    }
    // Initialize handlers
    $(window).resize ( () =>  {
        let hsize = $(window).width();
        console.log(`Resized new width : ${hsize}`);
    });
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
    // Initialize UI
    $(slidescontainer).hide();
    $('.carousel-inner').remove();
    let imagesArray = getImages(imagesdivid);
    sliderstatus.text(mouseoutmsg).addClass('sliderrunning');
    $(slidescontainer).show();
    addImages(imagesArray);
    // ----------------------------------------------------------------------------
    // Search for images contained in the transmitted DOM source
    // source is the ID of a containing element
    // ----------------------------------------------------------------------------
    function getImages(source) {
        let thelist = [];
        $(`#${source} .imagesmall`).each(function (index, element) {
            thelist.push(this);
        });
        return thelist;
    }
    // ----------------------------------------------------------------------------
    // Add images to the slider
    // ----------------------------------------------------------------------------
    function addImages(imglist) {
        let slides = $("<div>").addClass('carousel-inner');
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
            buttonindicator.attr('data-bs-target', '#sliderzone').attr('data-bs-slide-to', index); 
            if(index === 0) {
                $(item).addClass('active');
                buttonindicator.addClass('active');
            }
            $(indicators).append(buttonindicator);
            let newimg = $('<img>');
            let imgid = 'slideid-' + $(element).data('imageid');
            let imgsrc = $(element).attr('src');
            $(newimg).attr('id', imgid)
                    .addClass('sliderimage')
                    .attr('src', imgsrc);
            $(newimg).click(function (e) { 
                e.preventDefault();
                fullscreen(this, indicators);
            });
            $(item).append(newimg);
            $(slides).append(item);
        });
    }
    // --------------------------------------------------------------------------------------
    // Fullscreen image preview function selecting all required elements
    // --------------------------------------------------------------------------------------
    function fullscreen(knifeimage, indicators){

        console.log($(knifeimage).attr('src'));
        // Remove body scroll bar so user cant scroll up or down
        $("body").css("overflow", "hidden");
        // Pass the user clicked image source in preview image source
        $("#fullscreen").css("background-image", "url(" + $(knifeimage).attr('src') + ")");
        // Show the preview image box and the the light grey background
        $("#fullscreen").addClass("sliderzoomon")
                        .removeClass('sliderzoomoff');
        indicators.hide();
        $(".slidercontrol").hide();
        // Wait for the user to close the box
        $("#fullscreen").click( () => { 
            $("#fullscreen").removeClass("sliderzoomon")
                            .addClass('sliderzoomoff');
            indicators.show();
            $(".slidercontrol").show();
            $("body").css("overflow", "auto");
        });
    }
})




//
//       R E S E R V O I R    C O D E 
//
// Track window resize
// window.onresize =  () =>  {
//     slideWidth = currentSlideWidth;
//     // Place photos with the new window size
//     slides.forEach( setSlidePosition );
//     let currentslide = document.getElementsByClassName('current-slide carousel-slide')[0];
//     let theimg = currentslide.firstElementChild.getAttribute('src');
//     console.log(`Resized : Current image : ${theimg}`);
//     moveToSlide(track, currentslide, currentslide);
// };
// */



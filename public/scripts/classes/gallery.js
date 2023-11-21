/*----------------------------------------------------------------------------
    Gallery

    jun 17 2023     Initial
    jun 18 2023     Add div and text to the gallery template
    jun 19 2023     Get slideshow description text
    jun 21 2023     Gallery zoom for the 2nd time !!!
    Aug 29 2023     Reorg into single css file

----------------------------------------------------------------------------*/
class Gallery {

    constructor(container, description) {
        // Init
        this.version = 'Gallery:1.04, Aug 29 2023 ';
        this.container = container;
        this.description = description;
        this.containername = $(container).attr('name');
        this.gallery = `${this.containername}-zone`;
        this.galleryarea = `${this.containername}-area`;
        this.windowx = $(window).width();
        this.windowy = $(window).height();
        this.zoomactive = false;
        this.currentzoom = '';
        this.buildGalleryFrame(container);

        // Initialize handlers
        $(window).resize ( () =>  {
            if(this.zoomactive) {
            this.fullScreen(this.currentzoom);
            }
            this.windowx = $(window).width();
            this.windowy = $(window).height();
        })
    }
    // ------------------------------------------------------------------------------------------------
    buildGalleryFrame(container) {
        const galleryzone = $("<div>").addClass('galleryzone');
        const gallerytext = $("<div>");
        const text = $('<p>').text(this.description);
        $(gallerytext).append(text);
        const gallery = $("<ul>").attr('id', this.gallery).addClass('gallery')
        $(galleryzone).append(gallerytext);
        $(galleryzone).append(gallery);
        $(container).append(galleryzone);
        $(container).append($('<div></div>').attr('id', 'fullscreen').addClass('zoomoff'));
    }
    // ------------------------------------------------------------------------------------------------
    addImages(allimages) {
        // Get the container
        const gallery = $(`#${this.gallery}`);
        for(let i = 0; i < allimages.length; ++i) {
            // console.log(`${this.version} Adding image ${allimages[i]} to the gallery`);
            let newimg = $('<img>').attr('src', "/images/slideshow/"+allimages[i]);
            let theline = $('<li>');
            $(theline).append(newimg);
            $(gallery).append(theline);
            $(newimg).click( (e) => { // Arrow function mandatory here to use this
                e.preventDefault();
                this.fullScreen(allimages[i]);
            });
        }
    }
  // ------------------------------------------------------------------------------------------------
  fullScreen(imagesrc) {
    if(!this.zoomactive) {
      this.zoomactive = true;
      this.currentzoom = imagesrc;
    }
    // Remove body scroll bar so user cant scroll up or down
    $("body").css("overflow", "hidden");
    // Position the zoom 
    const top = window.scrollY;
    $("#fullscreen").css("background-image", "url(/images/slideshow/" + imagesrc + ")");
    $("#fullscreen").css({'top': top, 'left': 0, 'z-index': 1000});
    $("#fullscreen").addClass("zoomon").removeClass('zoomoff');
    // Hide a few things
    $(`#${this.indicators}`).hide();
    $(".slidercontrol").hide();

    // Wait for the user to close the box
    $("#fullscreen").click( () => { 
      this.zoomactive = false;
      $("#fullscreen").removeClass("zoomon")
                      .addClass('zoomoff');
      $(`#${this.indicators}`).show();
      $(".slidercontrol").show();
      $("body").css("overflow", "auto");
      $('.diapo').show();
    });
  }
}
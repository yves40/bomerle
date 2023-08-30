/*----------------------------------------------------------------------------
    Slider

    jun 05 2023     Initial
    jun 06 2023     Build the code
    jun 07 2023     Slider and zoom
    jun 08 2023     Slider and zoom, cont
    jun 09 2023     Add slider timing set into the UI
    jun 20 2023     Add slider description into the UI
    Aug 29 2023     Reorg into single css file

    ----------------------------------------------------------------------------*/
class Slider {

  constructor(container, timing = 2, description = '') {
    // Init
      this.version = 'Slider:1.12, Aug 30 2023 ';
      this.container = container;
      this.containername = $(container).attr('name');
      this.slideinterval = timing * 1000;
      this.description = description;
      this.homezone = `${this.containername}-zone`;
      this.indicators = `${this.containername}-indicators`;   // Used to manage inidicators when sliding
      this.sliderarea = `${this.containername}-area`;
      this.windowx = $(window).width();
      this.windowy = $(window).height();
      this.zoomactive = false;
      this.currentzoom = '';

      // Necessary to call a function from the click handler
      const handleButtons = this.buttonHandler.bind();

      // Slider parameters
      const VERYLONGTIMESLIDER = 60000;
      const automove = true;        // manage later
      if(!automove) {
        this.slideinterval = VERYLONGTIMESLIDER; // Almost disable auto scroll
      }
      this.buildSliderFrame(container);
      $('.carousel-button').each(function (index, button) {
        $(button).on('click', () => {
          handleButtons(this);
        });
      });


      // Initialize handlers
      $(window).resize ( () =>  {
        if(this.zoomactive) {
          this.fullScreen(this.currentzoom);
        }
        this.windowx = $(window).width();
        this.windowy = $(window).height();
      });
  }
  // ------------------------------------------------------------------------------------------------
  buttonHandler = function manageActiveSlide(buttonelement) {
    if(buttonelement.classList.contains('next')) {
      console.log('______ NEXT');
    }
    else {
      console.log('______ PREV');
    }
  }
  // ------------------------------------------------------------------------------------------------
  buildSliderFrame(container) {
    const galleryzone = $("<div>").addClass('galleryzone');
    const sliderzone = $("<div>").attr('id', this.homezone)
                                          .addClass('carousel');
      const slidetext = $('<div></div>').addClass('gallerytext');
      const spantitle = $('<span></span>').text(this.description);
      $(slidetext).append(spantitle);
      $(galleryzone).append(slidetext);
      const ul = $('<ul></ul>').attr('id', this.sliderarea).addClass('sliderarea');
      const prev = $("<button></button>").addClass('carousel-button').addClass('prev')
                                        .html('&#8656;');
      const next = $("<button></button>").addClass('carousel-button').addClass('next')
                                        .html('&#8658;');
      $(sliderzone).append(prev);
      $(sliderzone).append(next);
      $(sliderzone).append(ul);
      $(galleryzone).append(sliderzone);
      $(container).append(galleryzone);
      $(container).append($('<div></div>').attr('id', 'fullscreen').addClass('zoomoff'));
  }
  // ------------------------------------------------------------------------------------------------
  addImages(allimages) {
    console.log(`To be used later : Slide interval ${this.slideinterval}`);
    // Get the container
    const slides = $(`#${this.sliderarea}`);
    for(let i = 0; i < allimages.length; ++i) {
      let oneimage = $("<li>").addClass('slide');
      if(i === 0 ){
        $(oneimage).addClass('active');
      }
      let h3 = $("<h5></h5>").text(`${i+1}/${allimages.length}`);
      let newimg = $('<img>').addClass('sliderimage')
      .attr('src', "/images/slideshow/"+allimages[i]);
      $(newimg).click( (e) => { // Arrow function mandatory here to use this
        e.preventDefault();
        this.fullScreen(allimages[i]);
      });
      $(oneimage).append(newimg);
      $(slides).append(oneimage);      
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
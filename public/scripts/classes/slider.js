/*----------------------------------------------------------------------------
    Slider

    jun 05 2023     Initial
    jun 06 2023     Build the code
    jun 07 2023     Slider and zoom
    jun 08 2023     Slider and zoom, cont
    jun 09 2023     Add slider timing set into the UI
    jun 20 2023     Add slider description into the UI
    Aug 29 2023     Reorg into single css file
    Sep 01 2023     Fix some details

    ----------------------------------------------------------------------------*/
class Slider {

  constructor(container, timing = 2, description = '') {
    // Init
      this.version = 'Slider:1.3, Sep 01 2023 ';
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
      this.allimages = [];  // Will be filled bu addImages()
      this.activeindex = 0;

      // Necessary to call the handler function from the click handler
      // In the click handler, the element button is passed, using 'this'
      // Binding buttonhandler with 'this' as parameter gives access to 
      // the class methods and attributes from within the buttonhandler
      // Strange isn't it ? 
      // I hate JS ;-)
      const handleButtons = this.buttonHandler.bind(this);

      // Slider parameters
      const VERYLONGTIMESLIDER = 60000;
      const automove = true;        // manage later
      if(!automove) {
        this.slideinterval = VERYLONGTIMESLIDER; // Almost disable auto scroll
      }
      else {
        // Auto move
        this.intervalid = setInterval( () => {
          this.nextSlide();
        }, this.slideinterval);
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
    if(this.intervalid !== 0) {
      clearInterval(this.intervalid);
      this.intervalid = 0;
    }
    if(buttonelement.classList.contains('next')) {
      this.nextSlide();
    }
    else {
      this.previousSlide();
    }
  }
  // ------------------------------------------------------------------------------------------------
  nextSlide() {
    const splitter = this.sliderarea.split('-');
    const imageroot= splitter[0] + splitter[1];
    const activeline = $(`#${imageroot}-${this.activeindex}`);
    ++this.activeindex;
    const newindex = this.checkBoundaries();
    $(activeline).removeClass('active');
    $(`#${imageroot}-${newindex}`).addClass('active');
  }
  previousSlide() {
    const splitter = this.sliderarea.split('-');
    const imageroot= splitter[0] + splitter[1];
    const activeline = $(`#${imageroot}-${this.activeindex}`);
    --this.activeindex;
    const newindex = this.checkBoundaries();
    $(activeline).removeClass('active');
    $(`#${imageroot}-${newindex}`).addClass('active');
  }
  checkBoundaries() {
    if(this.activeindex === this.allimages.length) {
      this.activeindex = 0;
    }
    if(this.activeindex < 0) {
      this.activeindex = this.allimages.length - 1;
    }
    return this.activeindex;
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
      const prev = $("<a></a>").addClass('carousel-button prev');
      const previmage = $("<img>").attr('src', "/images/svg/arrow-back.svg").addClass("svgbig-white");
      $(prev).append(previmage);
      const next = $("<a></a>").addClass('carousel-button next');
      const nextimage = $("<img>").attr('src', "/images/svg/arrow-forward.svg").addClass("svgbig-white");
      $(next).append(nextimage);
      $(sliderzone).append(prev);
      $(sliderzone).append(next);
      $(sliderzone).append(ul);
      $(galleryzone).append(sliderzone);
      $(container).append(galleryzone);
      $(container).append($('<div></div>').attr('id', 'fullscreen').addClass('zoomoff'));
  }
  // ------------------------------------------------------------------------------------------------
  addImages(allimages) {
    console.log(`Slide interval ${this.slideinterval}`);
    // Get the container
    const slides = $(`#${this.sliderarea}`);
    const splitter = this.sliderarea.split('-');
    const imageroot= splitter[0] + splitter[1];
    for(let i = 0; i < allimages.length; ++i) {
      let oneimage = $("<li>").attr('id', `${imageroot}-${i}`).addClass('slide');
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
    this.activeindex = 0;    
    this.allimages = allimages; // Save it for later use by buttons handler
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
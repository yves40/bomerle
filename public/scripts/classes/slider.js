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
    Oct 12 2023     Typo. Add indicators to the slider frame
    Oct 14 2023     Indicators to the slider frame.
    Oct 15 2023     Indicators to the slider frame..

    ----------------------------------------------------------------------------*/
class Slider {

  // slidertype is used to generate images location.
  // As the slider can display diaporama images or knife images
  // and the location is /images/slideshow or /images/knife
  // Two accepted values : SHOW (the default) and KNIFE
  constructor(container, timing = 2, description = '', allimages, slidertype = 'SHOW') {
    // Init
      this.version = 'Slider:1.47, Oct 15 2023 ';
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
      this.allimages = allimages;
      this.activeindex = 0;
      this.slidertype = slidertype;
      this.imagespath = "";

      // Manage the web path used to load images, depending on slidertype
      switch (this.slidertype) {
        case 'SHOW':  this.imagespath = "/images/slideshow/";
                      break;
        case 'KNIFE': this.imagespath = "/images/knife/";
                      break;
        default:      this.imagespath = "/images/slideshow/";
                      break;
      }
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
      this.addImages(allimages);
      // Arm handlers
      $(`#${this.homezone} > .carousel-button`).children().on('click', (event) => {
            this.manageActiveSlide(event);
      })
      const indicators = $(`#${this.homezone} > .carousel-indicators`);
      $(indicators).children().on('click', (event) => {
            this.manageActiveIndicator(event);
      });
      // Some other handlers
      $(window).resize ( () =>  {
        if(this.zoomactive) {
          this.fullScreen(this.currentzoom);
        }
        this.windowx = $(window).width();
        this.windowy = $(window).height();
      });
  }
  // ------------------------------------------------------------------------------------------------
  manageActiveSlide(event) {
    if(this.intervalid !== 0) {
      clearInterval(this.intervalid);
      this.intervalid = 0;
    }
    const parent = $(event.target).parent();
    if($(parent).hasClass('next')) {
      this.nextSlide();
    }
    else {
      this.previousSlide();
    }
  }
  // ------------------------------------------------------------------------------------------------
  manageActiveIndicator(event) {
    const newindex = $(event.target).data('imageindex');
    const splitter = this.sliderarea.split('-');
    const imageroot= splitter[0] + splitter[1];
    const activeline = $(`#${imageroot}-${this.activeindex}`);
    this.activeindex = newindex;
    $(activeline).removeClass('active');
    $(`#${imageroot}-${newindex}`).addClass('active');
    this.updateActiveButton();
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
    this.updateActiveButton();
  }
  // ------------------------------------------------------------------------------------------------
  previousSlide() {
    const splitter = this.sliderarea.split('-');
    const imageroot= splitter[0] + splitter[1];
    const activeline = $(`#${imageroot}-${this.activeindex}`);
    --this.activeindex;
    const newindex = this.checkBoundaries();
    $(activeline).removeClass('active');
    $(`#${imageroot}-${newindex}`).addClass('active');
    this.updateActiveButton();
  }
  // ------------------------------------------------------------------------------------------------
  updateActiveButton() {
    const indicatorslist = $(`#${this.homezone} > .carousel-indicators`).children();
    for(let i = 0; i < indicatorslist.length; ++i){
      $(indicatorslist[i]).removeClass('active');
    };
    $(indicatorslist[this.activeindex]).addClass('active');
    // console.log(indicatorslist);
  }
  // ------------------------------------------------------------------------------------------------
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
    const sliderzone = $(`#${this.homezone}`);
    // Add the indicators
    const indicators = $("<div>").addClass('carousel-indicators');
    for(let i = 0; i < allimages.length; ++i) {
      let buttonindic = $('<button>').addClass('carousel-indicators-button')
        .attr('type', 'button')
        .attr('data-imageindex', `${i}`);
      if (i == 0) $(buttonindic).addClass('active');
      $(indicators).append(buttonindic);
    }
    $(sliderzone).append(indicators);
    // Add images
    const splitter = this.sliderarea.split('-');
    const imageroot= splitter[0] + splitter[1];
    for(let i = 0; i < allimages.length; ++i) {
      let oneimage = $("<li>").attr('id', `${imageroot}-${i}`).addClass('slide');
      if(i === 0 ){
        $(oneimage).addClass('active');
      }
      let h3 = $("<h5></h5>").text(`${i+1}/${allimages.length}`);
      let newimg = $('<img>').addClass('sliderimage')
          .attr('src', this.imagespath+allimages[i]);
      $(newimg).click( (e) => { // Arrow function mandatory here to use this
        e.preventDefault();
        this.fullScreen(this.allimages[this.activeindex]);
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
    // $("#fullscreen").css("background-image", "url(/images/slideshow/" + imagesrc + ")");
    $("#fullscreen").css("background-image", `url(${this.imagespath}${imagesrc})`);
    $("#fullscreen").css({'top': top, 'left': 0, 'z-index': 2000});
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
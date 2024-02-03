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
    Dec 20 2023     Fix timer problem
    Jan 07 2024     New DOM structure
    Jan 13 2024     New management of Y scrolling

    ----------------------------------------------------------------------------*/

  import Logger  from './logger.js';
  import $props  from '../properties.js';
    export default class Slider {

  // slidertype is used to generate images location.
  // As the slider can display diaporama images or knife images
  // and the location is /images/slideshow or /images/knife
  // Two accepted values : SHOW (the default) and KNIFE
  constructor(container, timing = 2, description = '', allimages, slidertype = 'SHOW') {
    // Init
      this.version = 'Slider:1.54, Jan 21 2024 ';
      this.container = container;
      this.containername = $(container).attr('name');
      this.slideinterval = timing * 1000;
      this.intervalid = 0;
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

      const devmode = $('.debug').length === 1 ? 'dev' : 'prod';
      this.logger = new Logger(devmode);
  
      // Manage the web path used to load images, depending on slidertype
      switch (this.slidertype) {
        case 'SHOW':  this.imagespath = $props.slideimageslocation() +  "/";
                      break;
        case 'KNIFE': this.imagespath = $props.knifeimageslocation() + "/";
                      break;
        default:      this.imagespath = $props.slideimageslocation() +  "/";
                      break;
      }
      this.buildSliderFrame(container);
      this.addImages(allimages);
      // Arm handlers
      $(`#${this.sliderarea} > .slider__box__elements__action`).children().on('click', (event) => {
        event.stopPropagation();
        this.manageActiveSlide(event);
      })
      $(`#${this.sliderarea} > .slider__box__elements__indicators`).children().on('click', (event) => {
        event.stopPropagation();
        this.manageActiveIndicator(event);
      });
      // Track any keyboard or mobile input
      $('html').keyup( (event) => { 
          console.log(event.keyCode);
          event.stopPropagation();
          // $(this.container).empty().hide();
          // $("body").css("overflow", "auto");
      });
      // Some other handlers
      $(window).resize ( () =>  {
        if(this.zoomactive) {
          this.fullScreen(this.currentzoom);
        }
        this.windowx = $(window).width();
        this.windowy = $(window).height();
      });
      $(window).scroll( () => {
        if(($(`#${this.homezone}`).length > 0) &&  this.isSliderVisible($(`#${this.homezone}`))) {
          this.startSlider();
        }
        else {
          this.stopSlider();
        }
      })
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
    const indicatorslist = $(`#${this.sliderarea} > .slider__box__elements__indicators`).children();
    for(let i = 0; i < indicatorslist.length; ++i){
      $(indicatorslist[i]).removeClass('active');
    };
    $(indicatorslist[this.activeindex]).addClass('active');
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
    const slidescontainer = $("<div>").addClass('slider');
    const sliderzone = $("<div>").attr('id', this.homezone)
                                          .addClass('slider__box');
    const ul = $('<ul></ul>').attr('id', this.sliderarea).addClass('slider__box_elements');
    const prev = $("<a></a>").addClass('slider__box__elements__action prev');
    const previmage = $("<img>").attr('src', `${$props.svgimageslocation()}/arrow-back.svg`).addClass("svg-white");
    $(prev).append(previmage);
    const next = $("<a></a>").addClass('slider__box__elements__action next');
    const nextimage = $("<img>").attr('src',  `${$props.svgimageslocation()}/arrow-forward.svg`).addClass("svg-white");
    $(next).append(nextimage);
    $(ul).append(prev);
    $(ul).append(next);
    $(sliderzone).append(ul);
    $(slidescontainer).append(sliderzone);
    $(container).append(slidescontainer);
    // $(container).append($('<div></div>').attr('id', 'fullscreen').addClass('zoomoff'));
    this.startSlider();
  }
  // ------------------------------------------------------------------------------------------------
  addImages(allimages) {
    // Get the container
    const slides = $(`#${this.sliderarea}`);
    const sliderzone = $(`#${this.homezone}`);
    // Add the indicators
    const indicators = $("<div>").addClass('slider__box__elements__indicators');
    for(let i = 0; i < allimages.length; ++i) {
      let buttonindic = $('<button>').addClass('slider__box__elements__indicators__flags')
        .attr('type', 'button')
        .attr('data-imageindex', `${i}`);
      if (i == 0) $(buttonindic).addClass('active');
      $(indicators).append(buttonindic);
    }
    $(slides).append(indicators);
    // Add images
    const splitter = this.sliderarea.split('-');
    const imageroot= splitter[0] + splitter[1];
    for(let i = 0; i < allimages.length; ++i) {
      let oneimage = $("<li>").attr('id', `${imageroot}-${i}`).addClass('slider__box__elements__slide');
      if(i === 0 ){
        $(oneimage).addClass('active');
      }
      let h3 = $("<h5></h5>").text(`${i+1}/${allimages.length}`);
      // let newimg = $('<img>').addClass('sliderimage')
      let newimg = $('<img>').attr('src', this.imagespath+allimages[i]);
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
      // Position the zoom 
      const top = window.scrollY;
      const closezoom = $("<img>").attr('src',  `${$props.svgimageslocation()}/close-circle-outline.svg`)
                  .addClass("svg-white");
      // // Remove body scroll bar so user cant scroll up or down
      $("body").css("overflow", "hidden");
      $("#zoomer").css({ 'top': top, 'left': 0, 'z-index': 2000 } )
              .addClass("zoomon").removeClass('zoomoff');
              $("#zoomer").append($('<div>').attr('id', 'zoomer__image')
              .addClass('zoomon__image')
              .append($('<img>').attr('src', `${this.imagespath}${imagesrc}`))
              );
      $('#zoomer').append(closezoom);
      $("#zoomer").show();
      // Hide a few things
      $(`#${this.indicators}`).hide();
      $(".slidercontrol").hide();

      // Wait for the user to close the zoom
      $(closezoom).click( (e) => { 
        e.preventDefault();
        this.zoomactive = false;
        $("#zoomer").empty().removeClass("zoomon")
                        .addClass('zoomoff').hide();
        $(`#${this.indicators}`).show();
        $(".slidercontrol").show();
        // $("body").css("overflow", "auto"); No longer reactivate scroll in requesting parent
      });
    }
  }
  // ------------------------------------------------------------------------------------------------
  // Slider automatic display set/reset
  // ------------------------------------------------------------------------------------------------
  startSlider() {
    if(this.intervalid === 0) {
      this.intervalid = setInterval( () => {
        this.nextSlide();
      }, this.slideinterval);
      this.logger.debug(`##VISIBLE## ${this.homezone} : Delay for slides set to ${this.slideinterval} with interval ID: ${this.intervalid}` );
    }
  }
  stopSlider() {
    if(this.intervalid !== 0) {
      this.logger.debug(`##HIDDEN## ${this.homezone} : Stop slideware with interval ID: ${this.intervalid}` );
      clearInterval(this.intervalid);
      this.intervalid = 0;
    }
  }
  // ------------------------------------------------------------------------------------------------
  // Slider element screen position evaluation
  // ------------------------------------------------------------------------------------------------
  isSliderVisible(el) {
    var windowHeight = $(window).height();
    var scrollTop = $(window).scrollTop();
    var elementTop = el.offset().top;
    var elementBottom = elementTop + el.height();

    return (elementTop >= scrollTop && elementTop <= (scrollTop + windowHeight)) ||
           (elementBottom >= scrollTop && elementBottom <= (scrollTop + windowHeight));
  }  
  getBoundingClientRect(el) {
    let offset = el.offset();
    let width = el.width();
    let height = el.height();
    let windowHeight = $(window).height();
    let scrollTop = $(window).scrollTop();

    return {
      top: offset.top,
      left: offset.left,
      bottom: offset.top + height,
      right: offset.left + width,
      width: width,
      height: height
    };
  }
}
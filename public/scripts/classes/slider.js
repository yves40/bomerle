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
    Feb 06 2024     New slider frame
    Feb 09 2024     New slider frame
    Feb 11 2024     New slider frame
    Mar 21 2024     Fix problem with div name

    ----------------------------------------------------------------------------*/

  import Logger  from './logger.js';
  import $props  from '../properties.js';
  export default class Slider {

  // slidertype is used to determine images location.
  // As the slider can display diaporama images or knife images
  // and the location is /images/slideshow or /images/knife
  // Two accepted values : SHOW (the default) and KNIFE
  constructor(container, timing = 2, description = '', allimages, slidertype = 'SHOW') {
    // Init
      this.version = 'Slider:1.59, May 05 2024 ';
      this.container = container;
      this.containername = ($(container).attr('name')).replaceAll(' ', '-');
      this.slideinterval = timing * 1000;
      this.intervalid = 0;
      this.description = description;
      this.homezone = `${this.containername}-zone`;
      this.sliderarea = `${this.containername}-area`;
      this.windowx = $(window).width();
      this.windowy = $(window).height();
      this.zoomactive = false;
      this.currentzoom = '';
      this.allimages = allimages;
      this.activeindex = 0;
      this.previousindex = 0;
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
      // Arm handlers for next prev and close
      $('.slider__box__action').on('click', (event) => {
        event.stopPropagation();
        this.setActiveSlide(event);
      })
      // Arm handler for indicators
      $('.slider__box__indicators__flags').on('click', (event) => {
        event.stopPropagation();
        this.IndicatorSelected(event.target);
      });
      // Track any keyboard or mobile input
      $('html').keyup( (event) => { 
          event.stopPropagation();
      });
      // Some other handlers
      $(window).resize ( () =>  {
        if(this.zoomactive) {
          this.fullScreen(this.currentzoom);
        }
        this.windowx = $(window).width();
        this.windowy = $(window).height();
        this.stopSlider();
      });
      // Use later ???
      $(window).scroll( () => {
      })
  }
  // ------------------------------------------------------------------------------------------------
  setActiveSlide(event) {
    if(this.intervalid !== 0) {
      this.stopSlider();
    }
    const parent = $(event.target).parent();
    if($(parent).hasClass('next')) {
      this.updateSlide(1);
    }
    else {
      if($(parent).hasClass('prev')) {
        this.updateSlide(-1);
      }
      else {
        $(this.container).trigger('sliderclosed');
      }
    }
  }
  /**
   * 
   * @param {*} sliderbox The UI container
   * @param {*} action    , 0 display current slide, 1 for next slide, -1 for previous
   */
  updateSlide(action) {
    if(action !== 0) {
      this.previousindex = this.activeindex;
      this.activeindex = this.checkBoundaries(this.activeindex + action);
    } 
    $('.slider__box__slides').find(`[data-imgindex=${this.activeindex}]`).addClass('active');
    if(this.activeindex !== this.previousindex) {
      $('.slider__box__slides').find(`[data-imgindex=${this.previousindex}]`).removeClass('active');
    }
    this.updateActiveButton(this.previousindex, this.activeindex);
  }
  /**
   * 
   * @param {*} requestedindex The requested image index
   * @returns The validated new index
   */
  checkBoundaries(requestedindex) {
    if(requestedindex  === this.allimages.length) {
      requestedindex = 0;
    }
    if(requestedindex < 0) {
      requestedindex= this.allimages.length - 1;
    }
    return requestedindex;
  }
  /**
   * 
   * @param {*} previndex The previuosly active image index
   * @param {*} nextindex The new active image index
   */
  updateActiveButton(previndex, nextindex) {
    const currentindicator = $(`.slider__box__indicators`).find(`[data-imageindex=${previndex}]`);
    const newindicator = $(`.slider__box__indicators`).find(`[data-imageindex=${nextindex}]`);
    $(currentindicator).removeClass('active');
    $(newindicator).addClass('active');
  }
  /**
   * 
   * @param {*} targetindicator The indicator element clicked
   */
  IndicatorSelected(targetindicator) {
    this.stopSlider();
    this.previousindex = this.activeindex;
    this.activeindex = $(targetindicator).data('imageindex');
    this.updateSlide(0);
  }
  // ------------------------------------------------------------------------------------------------
  buildSliderFrame(container) {
    // Container and slider box, displayed as grid
    $(container).addClass('slider');
    const sliderzone = $("<div>").attr('id', this.homezone).addClass('slider__box');

    const kdesc = $('<h2></h2>').addClass('kdesc heroh2').text(this.description);
    $(sliderzone).append(kdesc);
    // Close and Nav button
    const closebutton = $("<a></a>").addClass('slider__box__action techzone close');
    const prev = $("<a></a>").addClass('slider__box__action techzone prev');
    const next = $("<a></a>").addClass('slider__box__action techzone next');
    // Close and Nav images
    const closeimage = $("<img>").attr('src',  `${$props.svgimageslocation()}/close-circle-outline.svg`)
        .addClass("svg-white");
    const previmage = $("<img>").attr('src', `${$props.svgimageslocation()}/arrow-back.svg`)
        .addClass("svg-white").addClass('techzone');
    const nextimage = $("<img>").attr('src', `${$props.svgimageslocation()}/arrow-forward.svg`)
        .addClass("svg-white").addClass('techzone');
    $(closebutton).append(closeimage);
    $(prev).append(previmage);
    $(next).append(nextimage);
    // Images section
    const slidesbox = $('<div></div>').attr('id', this.sliderarea).addClass('slider__box__slides');
    $(sliderzone).append(slidesbox);
    // Add the indicators
    const indicators = $("<div>").addClass('slider__box__indicators')
      .addClass('techzone');
    $(indicators).append(prev);
    for(let i = 0; i < this.allimages.length; ++i) {
      let buttonindic = $('<button>').addClass('slider__box__indicators__flags')
        .attr('type', 'button')
        .attr('data-imageindex', `${i}`);
      if (i == 0) $(buttonindic).addClass('active');
      $(indicators).append(buttonindic);
    }
    $(indicators).append(next);
    $(indicators).append(closebutton);
    $(sliderzone).append(indicators);
    $(container).append(sliderzone);
  }
  // ------------------------------------------------------------------------------------------------
  addImages(allimages) {
    // Get the container
    const slides = $(`#${this.sliderarea}`);
    // Add images
    const splitter = this.sliderarea.split('-');
    const imageroot= splitter[0] + splitter[1];
    for(let i = 0; i < allimages.length; ++i) {
      let oneimage = $("<div>").attr('id', `${imageroot}-${i}`)
                  .attr(`data-imgindex`, `${i}`)
                  .addClass('slider__box__slides__img' );
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
    this.updateSlide(0);
    this.startSlider();
  }
  // ------------------------------------------------------------------------------------------------
  // Slider automatic display set/reset
  // ------------------------------------------------------------------------------------------------
  startSlider() {
    if(this.intervalid === 0) {
      this.intervalid = setInterval( () => {
        this.updateSlide(1); // Next slide after interval
      }, this.slideinterval);
      this.logger.debug(`${this.homezone} : Delay for slides set to ${this.slideinterval} with interval ID: ${this.intervalid}` );
    }
  }
  stopSlider() {
    if(this.intervalid !== 0) {
      this.logger.debug(`${this.homezone} : Stop slideware with interval ID: ${this.intervalid}` );
      clearInterval(this.intervalid);
      this.intervalid = 0;
    }
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
      const kname = $('<h2></h2>').text(this.description).addClass('kname');
      // // Remove body scroll bar so user cant scroll up or down
      $("body").css("overflow", "hidden");
      $("#zoomer").css({ 'top': top, 'left': 0, 'z-index': 2000 } )
              .addClass("zoomer");
      $("#zoomer").append($('<div>').attr('id', 'zoomer__box')
              .addClass('zoomer__box'));
      const imgcontainer = $('<div></div>').addClass('zoomer__box__img');
      $(imgcontainer).append($('<img>').attr('src', `${this.imagespath}${imagesrc}`));
      $('.zoomer__box').append(imgcontainer);
      $('.zoomer__box').append(closezoom);
      $('.zoomer__box').append(kname);
      $("#zoomer").css('display', 'flex')
      // Hide a few things
      $(".slidercontrol").hide();
      // Wait for the user to close the zoom
      $(closezoom).click( (e) => { 
        e.preventDefault();
        this.zoomactive = false;
        $("#zoomer").removeClass("zoomer").empty().hide();
        $(".slidercontrol").show();
        // $("body").css("overflow", "auto"); No longer reactivate scroll in requesting parent
      });
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
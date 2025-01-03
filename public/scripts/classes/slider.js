/*----------------------------------------------------------------------------
    Slider
    ----------------------------------------------------------------------------*/

  import Logger  from './logger.js';
  import $props  from '../properties.js';
  export default class Slider {

    /**
     * 
     * @param {*} container     Container for the whole slider 
     * @param {*} initialindex  Which image to display first ?
     * @param {*} timing        Delay when in automatic slideware. Default to 10 sec
     * @param {*} description   The slideware description, displayed above images
     * @param {*} allimages     The images to be displayed
     * @param {*} imagesrotation Array of rotation values for images : 0, 90, 180... ? 
     * @param {*} slidertype    Used to determine images location. 
     *                          As the slider can display diaporama images or knife images
     *                          and the location is /images/slideshow or /images/knife
     *                          Two accepted values : SHOW (the default) and KNIFE
     */

  constructor(container,initialindex = 0, timing = 10, 
            description = '', 
            allimages, 
            imagesrotations = [], 
            slidertype = 'SHOW') {
    // Init
      this.version = 'Slider:1.63, Jun 23 2024 ';
      this.container = container;
      this.activeindex = initialindex;
      this.previousindex = 0;
      this.containername = ($(container).attr('name')).replaceAll(' ', '-');
      this.slideinterval = timing * 1000;
      this.intervalid = 0;
      this.description = description;
      this.homezone = `${this.containername}-zone`;
      this.sliderarea = `${this.containername}-area`;
      this.windowx = $(window).width();
      this.windowy = $(window).height();
      this.currentzoom = '';
      this.allimages = allimages;
      this.imagesrotations = imagesrotations;
      this.slidertype = slidertype;
      this.imagespath = "";
      this.touchstartY = 0;
      this.touchendY = 0;
      this.NEXT = 1;
      this.PREV = -1;
      this.evCache = new Array();
      this.media = null;
      this.zoomcounter = 0;
      this.removeZoomListener = null;
      this.zoomactive = false;

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
      $('.slidercommand').on('click', (event) => {
        event.stopPropagation();
        this.setActiveSlide(event);
      })
      // Arm handlers for next prev and close with drag
      $('.slider__pictures__img').on('touchmove', (event) => {
        this.stopSlider();     
        // event.stopPropagation();
        if(event.originalEvent.touches.length > 1) {
          this.stopSlider();
          this.zoomactive = true; // Disable sliding records with fingers on mobile
        }
      })
      $('.slider__pictures__img').on('touchstart', (event) => {
        if(!this.zoomactive) {
          this.evCache.push(event);
          this.stopSlider(); 
          this.touchstartY = event.originalEvent.touches[0].screenY;
        }
        event.stopPropagation();
      })
      $('.slider__pictures__img').on('touchend', (event) => {
        if(!this.zoomactive) {
          this.touchendY = event.originalEvent.changedTouches[0].screenY;
          const delta = this.touchendY - this.touchstartY;
          if(delta !== 0) {
            if(this.touchendY > this.touchstartY) {
              this.updateSlide(this.NEXT);
            }
            else {
              this.updateSlide(this.PREV);
            }
          } 
        }
        event.stopPropagation();
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
      $(window).resize ( (event) =>  {
        this.windowx = $(window).width();
        this.windowy = $(window).height();
        this.stopSlider();
      });
      // Use later ???
      $(window).scroll( (event) => {
      })
  }
  /**
   * No longer used. Many problems with window.devicePixelRatio
   */
  mediaChanged() {
    ++this.zoomcounter;
    if(this.removeZoomListener != null) {
      this.removeZoomListener();
    }
    this.removeZoomListener = function () {
      this.media.removeEventListener("change", this.mediaChanged);
    }
    this.media = matchMedia(`(resolution: ${window.devicePixelRatio}dppx)`);
    this.media.addEventListener("change", this.mediaChanged);
    if (this.media.matches) {
      console.log(`have a match ${this.zoomcounter} : ${window.devicePixelRatio}`);
    } else {
      console.log(`have a miss ${this.zoomcounter} : ${window.devicePixelRatio}`);
    }
  }
  /**
   * 
   * @param {*} elementclass The element class name which size is queried
   * @returns 
   */
  getElementSizes(elementclass) {
      // Play with element bounding rect
      const picturescontainer = document.getElementsByClassName(elementclass);
      return picturescontainer[0].getBoundingClientRect();
  }
  /**
   * 
   * @param {*} event Manage slideware display of active slide. 3 possible actions, next, prev, close
   */
  setActiveSlide(event) {
    if(this.intervalid !== 0) {
      this.stopSlider();
    }
    const parent = $(event.target).parent();
    if($(parent).hasClass('next')) {
      this.updateSlide(this.NEXT);
    }
    else {
      if($(parent).hasClass('prev')) {
        this.updateSlide(this.PREV);
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
    $('.slider__pictures').find(`[data-imgindex=${this.activeindex}]`).addClass('active');
    if(this.activeindex !== this.previousindex) {
      $('.slider__pictures').find(`[data-imgindex=${this.previousindex}]`).removeClass('active');
    }
    $('.slidercounter').text(`${this.activeindex + 1} / ${this.allimages.length}`);
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
    $(container).css('position', 'absolute');
    // Container and slider box, displayed as flex
    const slider = $('<div></div>').addClass('slider');
    const divdesc = $('<div></div>').addClass('slider__head');
    const kdesc = $('<h2></h2>').text(this.description);
    const count = $('<span></span>').addClass('slidercounter');
    $(divdesc).append(kdesc);
    $(slider).append(divdesc);
    // Close and Nav button
    const closebutton = $("<a></a>").addClass('slidercommand close');
    const prev = $("<a></a>").addClass('slidercommand  prev');
    const next = $("<a></a>").addClass('slidercommand  next');
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
    const pictures = $('<div></div>').addClass('slider__pictures');
    $(slider).append(pictures);
    // Commands section
    const commands = $('<div></div>').addClass('slider__command');
    $(commands).append(prev);
    $(commands).append(next);
    $(commands).append(closebutton);
    $(commands).append(count);

    $(slider).append(commands);
    // Add the indicators
    // const indicators = $("<div>").addClass('slider__box__indicators')
    //   .addClass('techzone');
    // $(indicators).append(prev);
    // for(let i = 0; i < this.allimages.length; ++i) {
    //   let buttonindic = $('<button>').addClass('slider__box__indicators__flags')
    //     .attr('type', 'button')
    //     .attr('data-imageindex', `${i}`);
    //   if (i == 0) $(buttonindic).addClass('active');
    //   $(indicators).append(buttonindic);
    // }
    // $(indicators).append(next);
    // $(indicators).append(closebutton);
    // $(sliderzone).append(indicators);
    $(container).append(slider);
    //
  }
  // ------------------------------------------------------------------------------------------------
  addImages(allimages) {
    // Get the container
    const slides = $(`.slider__pictures`);
    // Add images
    const splitter = this.sliderarea.split('-');
    const imageroot= splitter[0] + splitter[1];
    for(let i = 0; i < allimages.length; ++i) {
      let oneimage = $("<div>").attr('id', `${imageroot}-${i}`)
                  .attr(`data-imgindex`, `${i}`)
                  .addClass('slider__pictures__img' );
      let newimg;
      if(this.imagesrotations.length > 0) { // Check a rotation array has been required
        newimg = $('<img>').attr('src', this.imagespath+allimages[i])
                      .css('transform', `rotate(${this.imagesrotations[i]}deg)`);
      }
      else {
        newimg = $('<img>').attr('src', this.imagespath+allimages[i]);
      }
      $(oneimage).append(newimg);
      $(slides).append(oneimage);
    }
    // this.activeindex = 0;    
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
}
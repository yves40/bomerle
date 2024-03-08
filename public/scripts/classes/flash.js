/*----------------------------------------------------------------------------
    Flash message
    ----------------------------------------------------------------------------*/

  import Logger  from './logger.js';
  import $props  from '../properties.js';
  export default class Flash {

    /**
     * 
     * @param {*} title The message title
     * @param {*} message The short message 
     * @param {*} info Additiona information
     */
    constructor(title='alert', message='No message', info) {
    // Init
      this.version = 'Flash:1.02, mar 08 2024 ';
      const devmode = $('.debug').length === 1 ? 'dev' : 'prod';
      this.logger = new Logger(devmode);
      this.info = info == undefined ? '': info;

      this.windowx = $(window).width();
      this.windowy = $(window).height();

      const flashdiv = $('.flash');
      const flasharea = $('.flash__area');

      const checkPosition = this.getBoundingClientRect(flashdiv);
      $(flashdiv).css({ 'top': window.scrollY, 'left': 0, 'z-index': 2000 } );
      const closezoom = $("<img>")
            .attr('src',  `${$props.svgimageslocation()}/close-circle-outline.svg`)
            .addClass("svg-white");
      $(flasharea).append($('<h2>').addClass('flash__area__title').text(title));
      $(flasharea).append($('<p></p>').addClass('flash__area__message').text(message));
      $(flasharea).append($('<p></p>').addClass('flash__area__info').text(this.info));

      $("body").css("overflow", "hidden");
      $(flasharea).append(closezoom);
      $(flashdiv).show();
      $(closezoom).click( (e) => { 
        e.preventDefault();
        $(flasharea).find('.flash__area__title').empty()
        $(flasharea).find('.flash__area__message').empty();
        $(flasharea).find('.flash__area__info').empty();
        $(flasharea).find('.svg-white').remove();
        $(flashdiv).hide();
        $("body").css("overflow", "auto");
      });

  }

  // Utilities
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
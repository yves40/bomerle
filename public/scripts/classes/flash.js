/*----------------------------------------------------------------------------
    Flash message
    ----------------------------------------------------------------------------*/

  import Logger  from './logger.js';
  import $props  from '../properties.js';
  export default class Flash {

    constructor(modulename = 'Generic') {
      this.modulename = modulename;
      this.severity = 'Information'; // DIWEF model
      this.version = 'Flash:1.04, mar 10 2024 ';
      const devmode = $('.debug').length === 1 ? 'dev' : 'prod';
      this.logger = new Logger(devmode);
    }
    /**
     * 
     * @param {*} title The message title
     * @param {*} message The short message 
     * @param {*} info Additional information
     */
    flashSuccess(title='alert', message='No message', info) {
      this.flash(title, message, info, 'informational');
    }
    /**
     * 
     * @param {*} title The message title
     * @param {*} message The short message 
     * @param {*} info Additional information
     * @param {*} severity The message severity, according to DIWEF
     */
    flash(title='alert', message='No message', info, severity = 'informational') {
      this.info = info == undefined ? '': info;
      const flashdiv = $('.flash');
      const flasharea = $('<div></div>').addClass('flash__area');
  
      $(flashdiv).css({ 'top': window.scrollY, 'left': 0, 'z-index': 2000 } );
      const closezoom = $("<img>")
            .attr('src',  `${$props.svgimageslocation()}/close-circle-outline.svg`)
            .addClass("svg-white");
      $(flasharea).append($('<h2>').addClass('flash__area__title').text(title));
      $(flasharea).append($('<p></p>').addClass('flash__area__message').text(message));
      $(flasharea).append($('<p></p>').addClass('flash__area__info').text(this.info));
      $(flasharea).addClass(`flash__${severity}`);
      $("body").css("overflow", "hidden");
      $(flasharea).append(closezoom);
      $(flashdiv).append(flasharea);
      $(flashdiv).show();
      $(closezoom).click( (e) => { 
        e.preventDefault();
        $(flashdiv).hide().empty();
        $("body").css("overflow", "auto");
      });
    }

}
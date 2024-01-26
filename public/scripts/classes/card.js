/*----------------------------------------------------------------------------
    Card
 ----------------------------------------------------------------------------*/

    import Logger  from './logger.js';
    export default class Card {

      /**
       * 
       * @param {*} container The DOM element into which the card will be appended
       * @param {*} data Related to the new card
       * @param {*} cardindex The card position in the parent Dom element
       *                      It's used to know if the image will be in 1st or 2nd position
       * @param {*} externalcss Generate the css or rely on exterlan sass ?
       */
      constructor(container, data, cardindex, externalcss=true) {
        // Init
          this.version = 'Card:1.39, Jan 22 2024 ';
          this.container = container;
          this.data = data;
          this.cardindex = cardindex;
          this.externalcss = externalcss;
          // Determine if running in dev or prod mode
          const devmode = $('.debug').length === 1 ? 'dev' : 'prod';
          this.logger = new Logger(devmode);

          this.loadCard(container, data, externalcss);
          this.mandatorydelay = 10; // When card zoom on a slider, delay is forced
      }
      /**
       * 
       * @param {*} container The DOM element into which the card will be loaded
       * @param {*} data Card details
       * @param {*} externalcss Generate the css or rely on exterlan sass ?
       *                        Default is to rely on external css
       */
      loadCard(container, data, externalcss=true) {
        const cardtitle = $("<div></div>").addClass('cards__frame__title').html(this.data.knifeName);
        const textimagecontainer = $('<div>').addClass('cards__frame__txtimg');
        const cardtext = $("<p>").addClass('cards__frame__text').html(this.data.knifedesc);
        const img = $("<img>").attr('src', `${$props.knifeimageslocation()}/${this.data.images[0]}`);
        if(!externalcss) {
          // Check if we must alternate images and text
          // or always put img above text
          if($(window).width() < 1200) {
            $(cardtext).css('text-align', 'justify');
            $(textimagecontainer).css('align-items', 'center').css('display', 'flex');
          }
          else {
            if(this.cardindex % 2 !== 0) {
              $(img).css('float', 'left');
              $(cardtext).addClass('textright');
            }
            else {
              $(img).css('float', 'right');
              $(cardtext).addClass('textleft');
            }
          }
        }
        $(textimagecontainer).append(img).append(cardtext);
        // Pack the elements
        $(container).append(cardtitle);
        $(container).append(textimagecontainer);
      }
    }
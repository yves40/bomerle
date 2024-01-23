/*----------------------------------------------------------------------------
    Card

    Sep 02 2023     Initial
    Jan 03 2024     Remove img click handler

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
      loadCard(container, data, externalcss) {
        this.logger.debug(`Build a knife card in category zoom for [ ${this.data.knifeName} ]`);
        const cardtitle = $("<div></div>").addClass('cards__cardframe__title').html(this.data.knifeName);
        const textimagecontainer = $('<div>').addClass('cards__cardframe__txtimgcontainer');
        const cardtext = $("<p>").addClass('cards__cardframe__text').html(this.data.knifedesc);
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
      // ---------------------------------------------------------------------
      Zoom(event) {
        const cardzoom = $('#cardzoom');
        const zoomframe = $("<div></div>").addClass('cards__cardframe') 
                    .addClass('cardframe__zoom');
        const title = $("<div></div>").addClass('cards_cardframe__title').html(this.data.knifeName);
        const text = $("<div></div>").addClass('cards__cardframe__text').html(this.data.knifedesc);
        const sliderdiv = $("<div></div>").attr('name', 'cardzoom'); // To inject the slider 
        $(zoomframe).append(title).append(text).append(sliderdiv);
        $(cardzoom).addClass('cards').append(zoomframe);

        const framecontainer = $(event.target).closest('.cards__cardframe');
        const splitter = $(framecontainer).attr('id').split('-');

        // Hello Mr Ajax
        const payload = {
          "knifeid" :  `${splitter[1]}`,
        }
        $.ajax({
            type: "POST",
            url: '/knives/public/getimages',
            data: JSON.stringify(payload),
            dataType: "json",
            async: true,
            success: function (response) {
              this.logger.debug(`Got ${response.imagecount} images for ${response.knifeName}`);
              // Remove body scroll bar so user cant scroll up or down
              $("body").css("overflow", "hidden");
              const top = window.scrollY;
              $(cardzoom).css({'top': top, 'left': 0, 'z-index': 1000})
                            .show()
                            .click( (e) => {
                              e.preventDefault();
                              $(cardzoom).removeClass('cards');
                              $(cardzoom).empty();
                              $(cardzoom).hide();
                              $("body").css("overflow", "auto");
                            });
              let slider = new Slider(sliderdiv, 10, null, 
                                response.images,
                                'KNIFE');
            },
            error: function (xhr) {
              logger.error(xhr);
            }
        });    
      }
    }
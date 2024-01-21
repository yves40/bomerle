/*----------------------------------------------------------------------------
    Card

    Sep 02 2023     Initial
    Jan 03 2024     Remove img click handler

    ----------------------------------------------------------------------------*/
    export default class Card {

      /**
       * 
       * @param {*} container The DOM element into which the card will be appended
       * @param {*} data Related to the new card
       * @param {*} cardindex The card position in the parent Dom element
       *                      It's used to know if the image will be in 1st or 2nd position
       */
      constructor(container, data, cardindex) {
        // Init
          this.version = 'Card:1.38, Jan 16 2024 ';
          this.container = container;
          this.data = data;
          this.cardindex = cardindex;
          this.loadCard(container, data);
          this.mandatorydelay = 10; // When card zoom on a slider, delay is forced
      }
      loadCard(container, data) {
        const cardtitle = $("<div></div>").addClass('cardframe__title').html(this.data.knifeName);
        const textimagecontainer = $('<div>').addClass('cardframe__txtimgcontainer');
        const cardtext = $("<p>").addClass('cardframe__text').html(this.data.knifedesc);
        const img = $("<img>").attr('src', `${$props.knifeimageslocation()}/${this.data.images[0]}`);
        // Check if we must alternate images and text
        // or always put img above text
        const ww = $(window).width();
        if((this.cardindex % 2 !== 0)||(ww < 1200)) {
          if(ww < 1200) {
            $(cardtext).css('text-align', 'justify');
          }
          else {
            $(cardtext).addClass('textright');
          }
          $(textimagecontainer).append(img).append(cardtext);
        }
        else {
          $(cardtext).addClass('textleft');
          $(textimagecontainer).append(cardtext).append(img);
        }
        // Pack the elements
        $(container).append(cardtitle);
        $(container).append(textimagecontainer);
      }
      // ---------------------------------------------------------------------
      Zoom(event) {
        const cardzoom = $('#cardzoom');
        const zoomframe = $("<div></div>").addClass('cardframe') 
                    .addClass('cardframe__zoom');
        const title = $("<div></div>").addClass('cardframe__title').html(this.data.knifeName);
        const text = $("<div></div>").addClass('cardframe__text').html(this.data.knifedesc);
        const sliderdiv = $("<div></div>").attr('name', 'cardzoom'); // To inject the slider 
        $(zoomframe).append(title).append(text).append(sliderdiv);
        $(cardzoom).addClass('cards').append(zoomframe);

        const framecontainer = $(event.target).closest('.cardframe');
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
              console.log(`Got ${response.imagecount} images for ${response.knifeName}`);
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
              console.log(xhr);
            }
        });    
      }
    }
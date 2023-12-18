/*----------------------------------------------------------------------------
    Card

    Sep 02 2023     Initial

    ----------------------------------------------------------------------------*/
    class Card {

      constructor(container, data) {
        // Init
          this.version = 'Card:1.1, Oct 18 2023 ';
          this.container = container;
          this.data = data;
          this.loadCard(container, data);
          this.mandatorydelay = 10; // When card zoom on a slider, delay is forced
      }
      loadCard(container, data) {
        const cardimage = $("<div></div>").addClass('cardframe__image');
        const cardtitle = $("<div></div>").addClass('cardframe__title').html(this.data.knifeName);
        const cardtext = $("<p>").addClass('cardframe__text').html(this.data.knifedesc);
        const img = $("<img>").attr('src', `/images/knife/${this.data.images[0]}`);
        $(img).on('click', (event) => {
          this.Zoom(event);
        });
        $(cardimage).append(img);
        $(container).append(cardtitle);
        $(container).append(cardimage);
        $(container).append(cardtext);
      }
      // ---------------------------------------------------------------------
      Zoom(event) {
        const globalfullscreen = $('#globalfullscreen');
        const zoomframe = $("<div></div>").addClass('cardframe')
                    .addClass('cardframe__zoom');
        const title = $("<div></div>").addClass('cardframe__title').html(this.data.knifeName);
        const text = $("<div></div>").addClass('cardframe__text').html(this.data.knifedesc);
        const sliderdiv = $("<div></div>").attr('name', 'cardzoom'); // To inject the slider 
        $(zoomframe).append(title).append(text).append(sliderdiv);
        $(globalfullscreen).addClass('cards').append(zoomframe);

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
              $(globalfullscreen).css({'top': top, 'left': 0, 'z-index': 1000})
                            .show()
                            .click( (e) => {
                              e.preventDefault();
                              $(globalfullscreen).removeClass('cards');
                              $(globalfullscreen).empty();
                              $(globalfullscreen).hide();
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
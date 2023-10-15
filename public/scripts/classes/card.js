/*----------------------------------------------------------------------------
    Card

    Sep 02 2023     Initial

    ----------------------------------------------------------------------------*/
    class Card {

      constructor(container, data) {
        // Init
          this.version = 'Card:1.0, Sep 02 2023 ';
          this.container = container;
          this.data = data;
          this.loadCard(container, data);
      }
      loadCard(container, data) {
        // console.log(`Build the card for knife : ${this.data.knifeName}`);
        // console.log(`Description is : ${this.data.knifedesc}`);
        // console.log(`Knife currently has : ${this.data.imagecount}`);
        // console.log(`First image is  : ${this.data.images[0]}`);
        const cardimage = $("<div></div>").addClass('card-image');
        const cardtitle = $("<div></div>").addClass('card-title').html(this.data.knifeName);
        const cardtext = $("<div></div>").addClass('card-text').html(this.data.knifedesc);
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
        const cardscontainer = $(event.target).closest('.cards');
        const framecontainer = $(event.target).closest('.cardframe');
        const splitter = $(framecontainer).attr('id').split('-');
        const slidescontainer = $("<div>").attr('name', `zoomer-${splitter[1]}`)
                  .addClass('diapo');
        $(cardscontainer).append(slidescontainer);
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
              let slider = new Slider(slidescontainer, 5, response.knifedesc, 
                                                        response.images,
                                                        'KNIFE');
            },
            error: function (xhr) {
              console.log(xhr);
            }
        });    
      }
    }
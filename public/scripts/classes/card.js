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
        $(cardimage).append(img);
        $(container).append(cardtitle);
        $(container).append(cardimage);
        $(container).append(cardtext);
      }
    }
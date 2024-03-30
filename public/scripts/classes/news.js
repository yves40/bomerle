/*----------------------------------------------------------------------------
    News

    jun 17 2023     Initial
    jun 18 2023     Add div and text to the gallery template
    jun 19 2023     Get slideshow description text
    jun 21 2023     Gallery zoom for the 2nd time !!!
    Aug 29 2023     Reorg into single css file
    Mar 18 2024     Change on container name
    Mar 29 2024     Some tests on IDs and DIV names
    Mar 30 2024     WIP on gallery frame

----------------------------------------------------------------------------*/

import $props from '../properties.js';

export default class News {

    constructor(container, description, allimages) {
        // Init
        this.newsid = $(container).attr('id');
        this.newsimages = allimages;
        this.version = 'News:1.10, Mar 30 2024 ';
        this.container = container;
        this.description = description;
        this.containername = $(container).attr('name');
        this.news = `${this.containername}-zone`;
        this.newsarea = `${this.containername}-area`;
        this.windowx = $(window).width();
        this.windowy = $(window).height();
        this.zoomactive = false;
        this.currentzoom = '';
        this.buildnewsFrame(container);

        // Initialize handlers
        $(window).resize ( () =>  {
            if(this.zoomactive) {
              this.fullScreen(this.currentzoom);
            }
            this.windowx = $(window).width();
            this.windowy = $(window).height();
        })
    }
    // ------------------------------------------------------------------------------------------------
    buildnewsFrame(container) {
        const newszone = $("<div>").addClass('newszone');
        const divcontainer = $('<div></div>').addClass('div--bgtextlightblue');
        const h2 = $('<h2></h2>').text($(container).attr('name'));
        $(divcontainer).append(h2);
        if(this.description.length !== 0) {
          const text = $('<p>').text(this.description);
          $(divcontainer).append(text);
        }
        $(newszone).append(divcontainer);
        const news = $("<ul>").attr('id', this.news).addClass('news')
        $(newszone).append(news);
        $(container).append(newszone);
        $(container).append($('<div></div>').attr('id', 'fullscreen').addClass('zoomoff'));
    }
    // ------------------------------------------------------------------------------------------------
    displayImages() {
      // Get the container
      const news = $(`#${this.news}`);
      for(let i = 0; i < this.newsimages.length; ++i) {
        let newimg= $('<img>').attr('src', $props.slideimageslocation()+"/"+this.newsimages[i]);
        let theline = $('<li>');
        $(theline).append(newimg);
        $(news).append(theline);
        $(newimg).click( (e) => { // Arrow function mandatory here to use this
            console.log(`Clicked on ${e.target.src}`);
            e.preventDefault();
            // this.fullScreen(allimages[i]);
        });
      }
      console.log(`Loaded ${this.newsimages.length} images for ${this.newsid}`);
  }
    // ------------------------------------------------------------------------------------------------
    clearImages() {
      // Get the container
      const news = $(`#${this.news}`);
      $(news).empty();
      console.log(`Cleared ${this.newsimages.length} images for ${this.newsid}`);
  }
// ------------------------------------------------------------------------------------------------
    // Getters
    getID() {return this.newsid;}
    getName() {return this.containername;}
    getImagesList() {return this.newsimages;}
    getDescription() {return this.description;}
  // ------------------------------------------------------------------------------------------------
  fullScreen(imagesrc) {
    if(!this.zoomactive) {
      this.zoomactive = true;
      this.currentzoom = imagesrc;
    }
    // Position the zoom 
    const top = window.scrollY;
    // Remove body scroll bar so user cant scroll up or down
    $("body").css("overflow", "hidden");

    $("#zoomer").css({ 'top': top, 'left': 0, 'z-index': 2000 } )
          .addClass("zoomon").removeClass('zoomoff');
    $("#zoomer").append($('<div>').attr('id', 'zoomer_box')
          .addClass('zoomon__image')
          .append($('<img>').attr('src', `${$props.slideimageslocation()}/${imagesrc}`))
    );
    $("#zoomer").show()
    // Hide a few things
    $(`#${this.indicators}`).hide();
    $(".slidercontrol").hide();
    // Wait for the user to close the box
    $("#zoomer").click( () => { 
      this.zoomactive = false;
      $("#zoomer").removeClass("zoomon")
                      .addClass('zoomoff').hide().empty();
      $(`#${this.indicators}`).show();
      $(".slidercontrol").show();
      $("body").css("overflow", "auto");
    });
  }
}
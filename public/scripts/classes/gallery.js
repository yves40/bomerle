/*----------------------------------------------------------------------------
    Gallery

    jun 17 2023     Initial
    jun 18 2023     Add div and text to the gallery template
    jun 19 2023     Get slideshow description text
    jun 21 2023     Gallery zoom for the 2nd time !!!
    Aug 29 2023     Reorg into single css file
    Mar 18 2024     Change on container name
    Mar 29 2024     Some tests on IDs and DIV names

----------------------------------------------------------------------------*/

import $props from '../properties.js';

export default class Gallery {

    constructor(container, description) {
        // Init
        this.version = 'Gallery:1.06, Mar 29 2024 ';
        this.container = container;
        this.description = description;
        this.containername = $(container).attr('name');
        this.gallery = `${this.containername}-zone`;
        this.galleryarea = `${this.containername}-area`;
        this.windowx = $(window).width();
        this.windowy = $(window).height();
        this.zoomactive = false;
        this.currentzoom = '';
        this.buildGalleryFrame(container);

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
    buildGalleryFrame(container) {
        const galleryzone = $("<div>").addClass('galleryzone');
        const divcontainer = $('<div></div>').addClass('div--bgtextlightblue');
        const h2 = $('<h2></h2>').text($(container).attr('name'));
        $(divcontainer).append(h2);
        if(this.description.length !== 0) {
          const text = $('<p>').text(this.description);
          $(divcontainer).append(text);
        }
        $(galleryzone).append(divcontainer);
        const gallery = $("<ul>").attr('id', this.gallery).addClass('gallery')
        $(galleryzone).append(gallery);
        $(container).append(galleryzone);
        $(container).append($('<div></div>').attr('id', 'fullscreen').addClass('zoomoff'));
    }
    // ------------------------------------------------------------------------------------------------
    addImages(allimages, imagetype = 'SLIDESHOW', associatedknivesids = null) {
        // Get the container
        const gallery = $(`#${this.gallery}`);
        for(let i = 0; i < allimages.length; ++i) {
          let newimg;
          if(imagetype === 'SLIDESHOW') {
            newimg = $('<img>').attr('src', $props.slideimageslocation()+"/"+allimages[i]);
          }
          else {
            newimg = $('<img>').attr('src', $props.knifeimageslocation()+"/"+allimages[i]);
          }
          if(associatedknivesids) { // In some cases it's usefull to store the associated 
                                    // Knifeid. When clicking on the image, we can get all 
                                    // images for this knife, in a slide ;-)
            $(newimg).attr('data-knifeid', associatedknivesids[i]);
          }
          let theline = $('<li>');
          $(theline).append(newimg);
          $(gallery).append(theline);
          if(imagetype === 'SLIDESHOW') {
            $(newimg).click( (e) => { // Arrow function mandatory here to use this
                e.preventDefault();
                this.fullScreen(allimages[i]);
            });
          }
        }
    }
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
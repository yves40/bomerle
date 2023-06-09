/*----------------------------------------------------------------------------
    Slider

    jun 05 2023     Initial
    jun 06 2023     Build the code
    jun 07 2023     Slider and zoom
    jun 08 2023     Slider and zoom, cont
    jun 09 2023     Add slider timing set into the UI

    ----------------------------------------------------------------------------*/
class Slider {

  constructor(container, timing = 2) {
    // Init
      this.version = 'Slider:1.07, Jun 09 2023 ';
      this.container = container;
      this.containername = $(container).attr('name');
      this.slideinterval = timing * 1000;
      this.homezone = `${this.containername}-zone`;
      this.indicators = `${this.containername}-indicators`;   // Used to manage inidicators when sliding
      this.sliderarea = `${this.containername}-area`;
      this.windowx = $(window).width();
      this.windowy = $(window).height();
      this.zoomactive = false;
      this.currentzoom = '';
      // Slider parameters
      const VERYLONGTIMESLIDER = 60000;
      const automove = true;        // manage later
      if(!automove) {
        this.slideinterval = VERYLONGTIMESLIDER; // Almost disable auto scroll
      }
      this.buildSliderFrame(container);

      // Initialize handlers
      $(window).resize ( () =>  {
        if(this.zoomactive) {
          this.fullScreen(this.currentzoom);
        }
        this.windowx = $(window).width();
        this.windowy = $(window).height();
        // console.log(`Resized new width : ${this.windowx}`);
      });
      // const mousehovermsg = $(".sliderdata").data('mousehovermsg');
      // const mouseoutmsg = $(".sliderdata").data('mouseoutmsg');
      // const sliderstatus = $("#sliderstatus p");
      //
  }
  // ------------------------------------------------------------------------------------------------
  buildSliderFrame(container) {
      const div = $("<div>");
      let span = $("<span>");
      $(span).text(`Container name is ${this.containername}`);
      $(div).append(span);
      const sliderzone = $("<div>").attr('id', this.homezone)
                                          .addClass('carousel')
                                          .addClass('slide')
                                          .addClass('sliderframe')
                                          .attr('data-bs-ride', 'carousel');
      const inner = $('<div></div>').attr('id', this.sliderarea)
                                    .addClass('carousel-inner')
                                    .addClass('sliderarea');
      const indicators = $('<div></div>').attr('id', this.indicators).addClass('carousel-indicators');
      const spanprev = $('<span></span>').addClass('carousel-control-prev-icon');
      const spannext = $('<span></span>').addClass('carousel-control-next-icon');
      const buttonprev = $('<button></button>').addClass('carousel-control-prev')
                                                  .addClass('slidercontrol')
                                                  .attr('data-bs-target', `#${this.homezone}`)
                                                  .attr('data-bs-slide', 'prev')
                                                  .append(spanprev);
      const buttonnext = $('<button></button>').addClass('carousel-control-next')
                                                  .addClass('slidercontrol')
                                                  .attr('data-bs-target', `#${this.homezone}`)
                                                  .attr('data-bs-slide', 'next')
                                                  .append(spannext);
      $(sliderzone).append(inner)
                      .append(indicators)
                      .append(buttonprev)
                      .append(buttonnext);

      $(div).append(sliderzone);
      $(container).append(div);
      $(container).append($('<div></div>').attr('id', 'fullscreen').addClass('zoomoff'));
  }
  // ------------------------------------------------------------------------------------------------
  addImages(allimages) {
    // Get the container
    const slides = $(`#${this.sliderarea}`);
    for(let i = 0; i < allimages.length; ++i) {
      // console.log(`${this.version} Adding image ${allimages[i]} to the slider`);
      let item = $("<div>").addClass('carousel-item');
      $(item).attr('data-bs-interval', this.slideinterval);
      let caption = $("<div>").addClass('carousel-caption');
      let h3 = $("<h5></h5>").text(`${i+1}/${allimages.length}`);
      caption.append(h3);
      let buttonindicator = $("<button>").attr('type', 'button');
      buttonindicator.attr('data-bs-target', `#${this.homezone}` )
                        .attr('data-bs-slide-to', i); 
      if(i === 0) {
        $(item).addClass('active');
        buttonindicator.addClass('active');
      }
      const indic = $(`#${this.indicators}`);
      indic.append(buttonindicator);
      let newimg = $('<img>').addClass('sliderimage rounded')
      .attr('src', "/images/slideshow/"+allimages[i]);
      $(newimg).click( (e) => { // Arrow function mandatory here to use this
        e.preventDefault();
        this.fullScreen(allimages[i]);
      });
      item.append(caption);
      $(item).append(newimg);
      $(slides).append(item);      
    }
  }
  // ------------------------------------------------------------------------------------------------
  fullScreen(imagesrc) {
    if(!this.zoomactive) {
      this.zoomactive = true;
      this.currentzoom = imagesrc;
    }
    // Remove body scroll bar so user cant scroll up or down
    $("body").css("overflow", "hidden");
    // Position the zoom 
    const top = window.scrollY;
    $("#fullscreen").css("background-image", "url(/images/slideshow/" + imagesrc + ")");
    $("#fullscreen").css({'top': top, 'left': 0, 'z-index': 1000});
    $("#fullscreen").addClass("zoomon").removeClass('zoomoff');
    // Hide a few things
    $(`#${this.indicators}`).hide();
    $(".slidercontrol").hide();

    // Wait for the user to close the box
    $("#fullscreen").click( () => { 
      this.zoomactive = false;
      $("#fullscreen").removeClass("zoomon")
                      .addClass('zoomoff');
      $(`#${this.indicators}`).show();
      $(".slidercontrol").show();
      $("body").css("overflow", "auto");
      $('.diapo').show();
    });
  }
}
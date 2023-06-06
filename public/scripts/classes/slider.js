/*----------------------------------------------------------------------------
    Slider

    jun 05 2023     Initial
    jun 06 2023     Build the code

    ----------------------------------------------------------------------------*/
class Slider {

  constructor(container) {
    // Init
      this.version = 'Slider:1.03, Jun 06 2023 ';
      this.container = container;
      this.containername = $(container).attr('name');
      this.slideinterval = 2000;
      this.homezone = `${this.containername}-zone`;
      this.indicators = `${this.containername}-indicators`;   // Used to manage inidicators when sliding
      this.sliderarea = `${this.containername}-area`;

      console.log(`[ ${$props.sliderclass()}  ]` );
      // Slider parameters
      const VERYLONGTIMESLIDER = 60000;
      const automove = true;        // manage later
      if(!automove) {
        this.slideinterval = VERYLONGTIMESLIDER; // Almost disable auto scroll
      }
      this.buildSliderFrame(container);

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
      const buttonprev = $('<button></button>').addClass('carousel-control-prev').
                                                  addClass('slidercontrol').
                                                  append(spanprev);
      const buttonnext = $('<button></button>').addClass('carousel-control-next').
                                                  addClass('slidercontrol').
                                                  append(spannext);
      $(sliderzone).append(inner)
                      .append(indicators)
                      .append(buttonprev)
                      .append(buttonnext);

      $(div).append(sliderzone);
      $(container).append(div);
  }
  // ------------------------------------------------------------------------------------------------
  addImages(allimages) {
    // Get the container
    const slides = $(`#${this.sliderarea}`);
    for(let i = 0; i < allimages.length; ++i) {
      console.log(`${this.version} Adding image ${allimages[i]} to the slider`);
      let item = $("<div>").addClass('carousel-item');
      $(item).attr('data-bs-interval', this.slideinterval);
      let caption = $("<div>").addClass('carousel-caption');
      let h3 = $("<div><h3").text(`${i+1}/${allimages.length}`);
      caption.append(h3);
      if(i === 0) {
        $(item).addClass('active');
      }      
      let newimg = $('<img>').addClass('sliderimage').attr('src', "/images/slideshow/"+allimages[i]);
      $(newimg).click( (e) => { // Arrow function mandatory here to use this
        e.preventDefault();
        this.fullScreen(allimages[i]);
      });
      $(item).append(newimg);
      item.append(caption);
      $(slides).append(item);      
    }
  }
  // ------------------------------------------------------------------------------------------------
  fullScreen(imagesrc) {
    console.log(`Clicked on ${imagesrc}`);
  }

}
/*----------------------------------------------------------------------------
    Slider

    jun 05 2023     Initial
    jun 06 2023     Build the code

    ----------------------------------------------------------------------------*/
class Slider {

  constructor(container) {
    // Init
      this.version = 'Slider:1.02, Jun 06 2023 ';
      this.container = container;
      this.containername = $(container).attr('name');
      this.slideinterval = 2000;
      console.log(`[ ${$props.sliderclass()}  ]` );
      // Slider parameters
      const VERYLONGTIMESLIDER = 60000;
      const slidescontainer = $(`#${this.containername}zone`);
      const automove = true;        // manage later
      if(!automove) {
        this.slideinterval = VERYLONGTIMESLIDER; // Almost disable auto scroll
      }
      this.buildSliderFrame(container);
      const indic = `#${this.containername}-indicators`;
      this.indicators = $(indic);   // Used to manage inidicators when sliding
      console.log(this.indicators);

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
        const sliderzone = $("<div>").addClass('carousel')
                                            .addClass('slide')
                                            .addClass('sliderframe')
                                            .attr('id', this.containername + 'zone')
                                            .attr('data-bs-ride', 'carousel');
        const inner = $('<div></div>').attr('id', 'sliderarea').addClass('carousel-inner');
        const indicators = $('<div></div>').attr('id', `${this.containername}-indicators`).addClass('carousel-indicators');
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
      for(let i = 0; i < allimages.length; ++i) {
        console.log(`${this.version} Adding image ${allimages[i]} to the slider`);
      }
    }

}
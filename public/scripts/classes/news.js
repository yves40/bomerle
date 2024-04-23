/*----------------------------------------------------------------------------
    News.js
----------------------------------------------------------------------------*/

import $props from '../properties.js';
import Sliderzoom from './sliderzoom.js';

export default class News {

    constructor(container, description, allimages, newsname, newsindex) {
        // Init
        this.newsid = `news-${newsindex}`;
        this.newsindex = newsindex;
        this.newsimages = allimages;
        this.newsname = newsname;
        this.version = 'News:1.12, Apr 02 2024 ';
        this.container = container;
        this.description = description;
        this.news = `${this.newsname.replaceAll(' ', '-')}-zone`;
        this.newsarea = `${this.newsname.replaceAll(' ', '-')}-area`;
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
      let newsdiv = $('<div>')
            .attr('id', this.newsid)
            .attr('name', this.newsname.replaceAll(' ', '-'))
            .addClass('news__details');
      $(container).append(newsdiv);
      $(newsdiv).attr('inactive','').removeAttr('active');
      
      const newsheader = $('<div></div>').addClass('news__details__header');
      $(newsdiv).append(newsheader);
      const newsimages = $("<ul>").attr('id', this.news).addClass('news__details__images')
      $(newsdiv).append(newsimages);
      $(container).append(newsdiv);
    }
    // ------------------------------------------------------------------------------------------------
    displayImages() {
      // Get the container
      const news = $(`#${this.news}`).parent();
      const h2 = $('<h2></h2>').text(this.newsname);
      news.prepend(h2);
      const imagesloaded = $(`#${this.news}`).find('img');
      
      if(imagesloaded.length === 0) { // Images already loaded ?
        for(let i = 0; i < this.newsimages.length; ++i) {
          let newimg= $('<img>').attr('src', $props.slideimageslocation()+"/"+this.newsimages[i])
                .attr('data-index', i);
          if(i === 0 ) { // 1st image displayed in header
            const imagetarget = $(news).find('.news__details__header');
            // Order of element insertions important here!
            // Image first otherwise float does not work
            imagetarget.append(newimg);
          }
          else {         // Other ones in the image section
            const imagetarget = $(news).find('.news__details__images');
            let theline = $('<li>');
            $(theline).append(newimg);
            imagetarget.append(theline);
          }
          $(newimg).click( (e) => { // Arrow function mandatory here to use this
              console.log(`Clicked on ${e.target.src} with index ${$(e.target).data('index')}`);
              e.preventDefault();
              $(slider).attr('name', this.newsname);
              let dynslider = new Sliderzoom($(slider),
                    10,
                    this.newsname,
                    this.newsimages);
              $("body").css("overflow", "hidden");
              $(slider).css({'top': window.scrollY,'left': 0, 'z-index': 1000}).css('display', 'flex');
            });
        }
        // Add a description if any, even if no image 
        if(this.description.length !== 0) {
          const text = $('<p>').text(this.description);
          $(news).find('.news__details__header').append(text);
        }  
    }
  }
  // ------------------------------------------------------------------------------------------------
  clearImages() {
    // Get the container
    const news = $(`#${this.news}`);
    $(news).empty();
    console.log(`Cleared ${this.newsimages.length} images for ${this.newsname}`);
}
// ------------------------------------------------------------------------------------------------
// Getters
  getID() {return this.newsid;}
  getName() {return this.newsname;}
  getImagesList() {return this.newsimages;}
  getDescription() {return this.description;}
}
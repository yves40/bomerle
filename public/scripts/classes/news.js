/*----------------------------------------------------------------------------
    News.js
----------------------------------------------------------------------------*/

import $props from '../properties.js';

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
      let newsection = $('<div>')
            .attr('id', this.newsid)
            .attr('name', this.newsname.replaceAll(' ', '-'))
            .addClass('news__details');
      $(container).append(newsection);
      $(newsection).attr('inactive','').removeAttr('active');
      
      const newszone = $("<div>").addClass('news__zone');
      const divcontainer = $('<div></div>').addClass('div--bgtextlightblue');
      const h2 = $('<h2></h2>').text(this.newsname);
      $(divcontainer).append(h2);
      if(this.description.length !== 0) {
        const text = $('<p>').text(this.description);
        $(divcontainer).append(text);
      }
      $(newszone).append(divcontainer);
      const news = $("<ul>").attr('id', this.news).addClass('news__zone__images')
      $(newszone).append(news);
      $(newsection).append(newszone);
      $(container).append(newsection);
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
      console.log(`Loaded ${this.newsimages.length} images for ${this.newsname}`);
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
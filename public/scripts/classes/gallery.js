/*----------------------------------------------------------------------------
    Gallery

    jun 17 2023     Initial
    jun 18 2023     Add div and text to the gallery template

----------------------------------------------------------------------------*/
class Gallery {

    constructor(container) {
        // Init
        this.version = 'Gallery:1.01, Jun 18 2023 ';
        this.container = container;
        this.containername = $(container).attr('name');
        this.gallery = `${this.containername}-zone`;
        this.galleryarea = `${this.containername}-area`;
        this.buildGalleryFrame(container);
    }
    // ------------------------------------------------------------------------------------------------
    buildGalleryFrame(container) {
        const galleryzone = $("<div>").addClass('galleryzone');
        const gallerytext = $("<div>").addClass('gallerytext');
        const spantext = $('<span>').text('Ceci est un texte bidon');
        $(gallerytext).append(spantext);
        const gallery = $("<ul>").attr('id', this.gallery).addClass('gallery')
        $(galleryzone).append(gallerytext);
        $(galleryzone).append(gallery);
        $(container).append(galleryzone);
    }
    // ------------------------------------------------------------------------------------------------
    addImages(allimages) {
        // Get the container
        const gallery = $(`#${this.gallery}`);
        for(let i = 0; i < allimages.length; ++i) {
            console.log(`${this.version} Adding image ${allimages[i]} to the gallery`);
            let newimg = $('<img>').attr('src', "/images/slideshow/"+allimages[i]);
            let theline = $('<li>');
            $(theline).append(newimg);
            $(gallery).append(theline);
        }
    }
}
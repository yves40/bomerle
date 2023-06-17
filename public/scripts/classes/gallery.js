/*----------------------------------------------------------------------------
    Gallery

    jun 17 2023     Initial

----------------------------------------------------------------------------*/
class Gallery {

    constructor(container) {
        // Init
        this.version = 'Gallery:1.00, Jun 17 2023 ';
        this.container = container;
        this.containername = $(container).attr('name');
        this.galleryzone = `${this.containername}-zone`;
        this.galleryarea = `${this.containername}-area`;
        this.buildGalleryFrame(container);
    }
    // ------------------------------------------------------------------------------------------------
    buildGalleryFrame(container) {
        const div = $("<div>");
        const galleryzone = $("<ul>").attr('id', this.galleryzone).addClass('gallery')
        $(div).append(galleryzone);
        $(container).append(div);
    }
    // ------------------------------------------------------------------------------------------------
    addImages(allimages) {
        // Get the container
        const galleryzone = $(`#${this.galleryzone}`);
        for(let i = 0; i < allimages.length; ++i) {
            console.log(`${this.version} Adding image ${allimages[i]} to the gallery`);
            let newimg = $('<img>').attr('src', "/images/slideshow/"+allimages[i]);
            let theline = $('<li>');
            $(theline).append(newimg);
            $(galleryzone).append(theline);
        }
    }
}
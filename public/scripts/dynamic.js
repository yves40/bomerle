//  -------------------------
//  dynamic.js
//  -------------------------
$(document).ready(function () {
    $props.load();
    console.log(`[${$props.version()} ]` );
    findDiapoSections();    
    // ---------------------------------------- Find a dynamic section in the page
    function findDiapoSections() {
        $('.diapo').each( function (index, element) {
            let name = $(this).attr('name');
            const payload = {
                "diaponame" :  name,
            }
            $.ajax({
                type: "POST",
                url: '/bootadmin/slides/getdiapos',
                data: JSON.stringify(payload),
                dataType: "json",
                async: false,
                success: function (response) {
                    console.log(response);
                    if(response.slidermode) {
                        if(response.timing === null) {
                            response.timing = 2;
                        }
                        buildImagesSlider(response.images, response.timing, element);
                    }
                    else {
                        buildImagesGallery(response.images, response.description,  element);
                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                }
            });    
        })
    }
    // ----------------------------------------
    function buildImagesSlider(allimages, timing, container) {
        console.log(`Container name is ${$(container).attr('name')} for ${allimages.length} images`);
        let slider = new Slider(container, timing);     // Build the slider frame
        slider.addImages(allimages);                    // Load images
    }
    // ----------------------------------------
    function buildImagesGallery(allimages, description,  container) {
        console.log(`Container name is ${$(container).attr('name')} for ${allimages.length} images`);
        let gallery = new Gallery(container, description);
        gallery.addImages(allimages);
    }
})

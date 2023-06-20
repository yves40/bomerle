//  -------------------------
//  dynamic.js
//  -------------------------
$(document).ready(function () {
    $props.load();
    console.log(`[${$props.version()} ]` );
    getActiveDiaporamas();
    // findDiapoSections();    
    // ---------------------------------------- Request the active diaporamas from the DB
    function getActiveDiaporamas() {
        $.ajax({
            type: "POST",
            url: '/bootadmin/slides/getactivediaporamas',
            dataType: "json",
            async: false,
            success: function (response) {
                console.log(response.activediaporamas);
                loadDiapoSections(response.activediaporamas);                 
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });    
    }
    // ---------------------------------------- Find a dynamic section in the page
    function loadDiapoSections(allactive) {
        let gallerysection = $('#thegallery');
        allactive.forEach(diapo => {
            let diaposection = $('<div>').addClass('diapo').attr('name', diapo.name);
            $(gallerysection).append(diaposection);
            const payload = {
                "diaponame" :  diapo.name,
            }
            $.ajax({
                type: "POST",
                url: '/bootadmin/slides/getdiapos',
                data: JSON.stringify(payload),
                dataType: "json",
                async: false,
                success: function (response) {
                    if(response.slidermode) {
                        if(response.timing === null) {
                            response.timing = 2;
                        }
                        buildImagesSlider(response.images, response.timing, response.description, diaposection);
                    }
                    else {
                        buildImagesGallery(response.images, response.description,  diaposection);
                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                }
            });    
        });
        // $('.diapo').each( function (index, element) {
        //     let name = $(this).attr('name');
        // })
    }
    // ----------------------------------------
    function buildImagesSlider(allimages, timing, description, container) {
        console.log(`Container name is ${$(container).attr('name')} for ${allimages.length} images`);
        let slider = new Slider(container, timing, description);     // Build the slider frame
        slider.addImages(allimages);                    // Load images
    }
    // ----------------------------------------
    function buildImagesGallery(allimages, description,  container) {
        console.log(`Container name is ${$(container).attr('name')} for ${allimages.length} images`);
        let gallery = new Gallery(container, description);
        gallery.addImages(allimages);
    }
})

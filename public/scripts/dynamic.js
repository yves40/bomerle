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
            console.log(`Diapo element : ${name}`)
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
                        buildImagesSlider(response.images, element);
                    }
                    else {
                        buildImagesGallery(response.images, element);
                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                }
            });    
        })
    }
    // ----------------------------------------
    function buildImagesSlider(allimages, container) {
        console.log(`Slider for ${allimages.length} images`);
        console.log(`Container name is ${$(container).attr('name')}`);
        const template = getSliderTemplate();
        const div = $("<div>");
        let span = $("<span>");
        $(span).text(`Container name is ${$(container).attr('name')}`);
        $(div).append(span);
        $(container).append(div);
        $(div).append(template);
    }
    // ----------------------------------------
    function buildImagesGallery(allimages, container) {
        console.log(`Gallery for ${allimages.length} images`);
        console.log(`Container name is ${$(container).attr('name')}`);
        const template = getGalleryTemplate();
        const div = $("<div>");
        let span = $("<span>");
        $(span).text(`Container name is ${$(container).attr('name')}`);
        $(div).append(span);
        $(container).append(div);
        $(div).append(template);
    }
    // ----------------------------------------
    function getSliderTemplate() {
        $.ajax({
            url: '/bootadmin/slides/slidertemplate',
            dataType: 'json',
            async: false,
            success: function(data) {
                // 'data' variable contains the HTML content of the file
                console.log(data);
                return data;
            },
            error: function(xhr, status, error) {
                console.error(error);
                return '';
            }
        }); 
    }
    // ----------------------------------------
    function getGalleryTemplate() {
        $.ajax({
            url: '/bootadmin/slides/slidertemplate',
            dataType: 'json',
            success: function(data) {
                // 'data' variable contains the HTML content of the file
                console.log(data);
                return data;
            },
            error: function(xhr, status, error) {
                console.error(error);
                return '';
            }
        }); 
    }
})

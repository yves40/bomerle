//  -------------------------
//  dynamic.js
//  -------------------------
$(document).ready(function () {
    $props.load();
    console.log(`[${$props.version()} ]` );
    const globalmenu = $('#globalmenu');
    const catalogsection = $("#catalog");
    const menuopen = $('#menuopen');
    const menuclose = $('#menuclose');
    $(menuopen).click(function (e) { 
        e.preventDefault();
        console.log(`Toggle menu`);
        const currentshift = $('#globalmenu').css('right');
        $('#globalmenu').css('right', '0px');
        $(menuopen).hide();
    });
    $(menuclose).click(function (e) { 
        e.preventDefault();
        console.log(`Close menu`);
        $('#globalmenu').css('right', '-200px');
        $(menuopen).show();
    });
    $('#globalmenu a').click(function (e) { 
        $('#globalmenu').css('right', '-200px');
        $(menuopen).show();
    });

    getActiveDiaporamas();
    getPublishedKnives();
    getHtmlTemplates();
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
    // ---------------------------------------- Request the active diaporamas from the DB
    function getPublishedKnives() {
        $.ajax({
            type: "POST",
            url: '/bootadmin/knives/getpublished',
            dataType: "json",
            async: false,
            success: function (response) {
                console.log(response);
                $(catalogsection).hide();
                if(response.publishedcount != 0) {
                    $(catalogsection).show();
                    loadPublishedCatalog(response.published);
                }
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });    
    }
    // ---------------------------------------- Find a dynamic section in the page
    function loadPublishedCatalog(allpublished) {
        allpublished.forEach( knife => {
            console.log(`Got a knife to be published ${knife.name}`);
            const li = $("<li></li>") ;
            const a = $("<a></a>").addClass('dropdown-item').text(knife.name);
            li.append(a);
            $('#publishedknives').append(li);
        })
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
    }
    // ---------------------------------------- Find dynamic html pieces
    function getHtmlTemplates() {
        $(".htmltemplate").each(function (index, element) {
            const thelang = $(this).data('lang');
            // element == this
            const templatename = $(this).data('templatename');
            $(this).load(`/templates/${thelang}/${templatename}.html`);
        });
    }
    // ---------------------------------------- Activate a slider
    function buildImagesSlider(allimages, timing, description, container) {
        console.log(`Container name is ${$(container).attr('name')} for ${allimages.length} images`);
        let slider = new Slider(container, timing, description);     // Build the slider frame
        slider.addImages(allimages);                    // Load images
    }
    // ---------------------------------------- Activate a gallery
    function buildImagesGallery(allimages, description,  container) {
        console.log(`Container name is ${$(container).attr('name')} for ${allimages.length} images`);
        let gallery = new Gallery(container, description);
        gallery.addImages(allimages);
    }
})

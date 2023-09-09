//  -------------------------
//  dynamic.js
//  -------------------------
$(document).ready(function () {
    $props.load();
    console.log(`[${$props.version()} ]` );
    const cardsmenu = $("#cardsmenu");
    const menuHamburger = $(".menu-hamburger");
    const navLinks = $(".nav-links");
    const infoknife = $('.knife');

    $(menuHamburger).on('click', function () {
        $(navLinks).toggleClass('mobile-menu');
    });
    $('nav-links, a').each(function (index, element) {
        // element == this
        $(element).on('click', () => {
            if($(navLinks).hasClass('mobile-menu')) {
                $(navLinks).toggleClass('mobile-menu');
            }
        })
    });
    // Some UI initial state
    $(infoknife).hide();
    // Handle the user choice for the object request type
    $('.object').change( function() {
        $('select option:selected').each( function(index, element) {
            if(index == 0){
                if($(this).val() == 'knife_personalisation' 
                        || $(this).val() == 'knife_reservation'){
                    $(infoknife).show();
                }else{
                    $(infoknife).hide();
                }
            }
        })
    })

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
                $(cardsmenu).hide();
                if(response.publishedcount != 0) {
                    $(cardsmenu).show();
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
        let cardssection = $('#cards');
        allpublished.forEach( knife => {
            const payload = {
                "knifeid" :  knife.id,
            }
            $.ajax({
                type: "POST",
                url: '/bootadmin/knives/getimages',
                data: JSON.stringify(payload),
                dataType: "json",
                async: false,
                success: function (response) {
                    buildCard(response, cardssection);
                },
                error: function (xhr) {
                    console.log(xhr);
                }
            });    
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
    // ---------------------------------------- Activate a card
    function buildCard(response, container ) {
        let thecard = $('<div>').attr('id', `knifeid-${response.knifeId}`).addClass('cardframe');
        let card  = new Card(thecard, response);
        container.append(thecard);
    }
})

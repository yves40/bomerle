//  -------------------------
//  dynamic.js
//  -------------------------
$(document).ready(function () {
    $props.load();
    console.log(`[${$props.version()} ]` );
    const cardsmenu = $("#cardsmenu");
    const cardsection = $('#cards');
    const gallerymenu = $("#gallerymenu");
    const gallerysection = $('#thegallery');
    const categoriesmenu = $('#categoriesmenu');
    const categorysection = $('#categories');
    const categoryslider = $('#categoryslider');    // 1st gallery zoom test
    const categorygallery = $('#categorygallery');  // 2nd gallery zoom implementation
    const menuHamburger = $(".menu-hamburger");
    const navLinks = $(".nav-links");
    const infoknife = $('.knife');
    // Initial state of UI
    $('#globalfullscreen').hide();
    $('#cardzoom').hide();
    $('#zoomer').hide();
    $(infoknife).hide();
    $(gallerymenu).hide();
    $(cardsmenu).hide();
    $(categoriesmenu).hide();
    $(gallerysection).hide();
    $(cardsection).hide();
    $(categorysection).hide();
    $(categoryslider).hide();

    let validEmail = false;
    let validText = false;
    let categorygalleryactive = false;

    $(menuHamburger).on('click', function () {
        $(navLinks).toggleClass('mobile-menu');
    });
    $(`.nav-links, .mobile-menu` ).on('click', (element) => {
        $(navLinks).toggleClass('mobile-menu');
    });
    // Handle the user choice for the object request type
    $('.object').change( function() {
        $('select option:selected').each( function(index, element) {
            if(index == 0){
                if($(this).val() == 'infoknife' ) {
                    $(infoknife).show();
                }else{
                    $(infoknife).hide();
                }
            }
        })
    })
    // Email checker
    buttonActivation();
    $("#contact_email").on('keyup', function (event){
        buttonActivation();
    })
    // Message checker
    $("#contact_text").on('keyup', function (event){
        buttonActivation();
    })
    // Button activation for message request
    $("#contactrequest").on('click', function (event){
        event.preventDefault();
        buttonClicked();
    })
    getActiveDiaporamas();
    getPublishedKnives();
    getHtmlTemplates();

    // ---------------------------------------- Request the active diaporamas from the DB
    function getActiveDiaporamas() {
        $.ajax({
            type: "GET",
            url: '/slides/public/getactivediaporamas',
            dataType: "json",
            async: true,
            success: function (response) {
                console.log(response);
                if(response.activecount != 0) {
                    $(gallerymenu).show();
                    $('#thegallery').show();
                    loadDiapoSections(response.activediaporamas);                 
                }
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });    
    }
    // ---------------------------------------- Request the active diaporamas from the DB
    function getPublishedKnives() {
        $.ajax({
            type: "GET",
            url: '/knives/public/getpublished',
            dataType: "json",
            async: true,
            success: function (response) {
                console.log(response);
                if(response.categoriescount != 0) {
                    $(categoriesmenu).show();
                    $(categorysection).show();
                    loadCategoriesCatalog(categorysection, response.categories);
                }
                if(response.knivescount != 0) {
                    loadPublishedCatalog(response.knives);
                    $(cardsmenu).show();
                    $(cardsection).show();
                }
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });    
    }
    // ---------------------------------------- 
    function loadCategoriesCatalog(container, categories) {
        const dedup = [...new Map(categories.map((m) => [m.catid, m])).values()]
            .sort( (cat1, cat2) => {
                let x = cat1.catname.toLowerCase();
                let y = cat2.catname.toLowerCase();
                if (x < y) {return -1;}
                if (x > y) {return 1;}
                return 0;
            });

        const catzone = $('<div></div>').addClass('catzone');
        $(container).append(catzone);
        console.log(`${dedup.length} categories used in published knives`);
        for(let idx = 0; idx < dedup.length; ++idx) {
            const payload = {
                'catid': dedup[idx].catid,
                'single': true
            }
            $.ajax({
                type: "POST",
                url: '/knives/public/categoryimages',
                dataType: "json",
                async: false,
                data: JSON.stringify(payload),
                success: function (response) {
                    AddCategory(catzone, response, dedup[idx]);
                },
                error: function (xhr) {
                    console.log(xhr);
                }
            });    
        }
    }
    // ----------------------------------------
    function AddCategory(container, response, category) {
        const div = $('<div></div>').addClass('catcard');
        const h2 = $('<h2>').text(category.catfullname).addClass('heroh2');
        const img = $('<img>').attr('src', `/images/knife/${response.filenames[0]}`)
                                .attr('data-catid', category.catid)
                                .attr('data-catname', category.catname)
                                .attr('data-catdesc', category.catdesc);
        $(img).on('click', (event) => {
            event.preventDefault();
            // zoomCategory(event.target, 'SLIDER');
            if (categorygalleryactive) {
                $(categorygallery).empty().hide();
                categorygalleryactive = false;
            }
            zoomCategory(event.target, 'GALLERY');
            window.location = '#categorygallery';
        })
        $(div).append(h2);
        $(div).append(img);
        $(container).append(div);
    }
    /** ----------------------------------------
     * @param targetcategory : The category UI object 
     * @param zoomode : 'SLIDER' or 'GALLERY'
     *                   Drives the way category photos are displayed
     ---------------------------------------- */
    function zoomCategory(targetcategory, zoomstyle = 'GALLERY') {
        console.log(`${$(targetcategory).data('catname')}\
                         catid:  ${$(targetcategory).data('catid')}`);
        const payload = {
            'catid': $(targetcategory).data('catid'),
            'single': false
        }
        $.ajax({
            type: "POST",
            url: '/knives/public/categoryimages',
            dataType: "json",
            async: false,
            data: JSON.stringify(payload),
            success: function (response) {
                if(zoomstyle === 'SLIDER') {
                    buildSlider(targetcategory, response);
                }
                else {
                    buildGallery(targetcategory, response);
                }
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });    
    }
    /** 
     *  @var targetcategory : The category UI object 
     *  @var response : Images files list from the json call
     **/
    function buildSlider(targetcategory, response) {
        const h2 = $('<h2>').text($(targetcategory).data('catname'))
                            .addClass('heroh2');
        const p = $('<p>').text($(targetcategory).attr('data-catdesc'));
        $(categoryslider).append(h2).append(p);
        let slider = new Slider($(categoryslider), 10, '',
            response.filenames, 'KNIFE');
        $("body").css("overflow", "hidden");
        $(categoryslider).css({'top': window.scrollY,
            'left': 0, 'z-index': 1000})
            .show()
            .on('click', (event) => {
                event.preventDefault();
                $(categoryslider).empty();
                $(categoryslider).hide();
                $("body").css("overflow", "auto");
            });
    }
    /** 
     *  @var targetcategory : The category UI object 
     *  @var response : Images files list from the json call
     **/
    function buildGallery(targetcategory, response) {
        const h2 = $('<h2>').text($(targetcategory).data('catname'))
                            .addClass('heroh2');
        const close = $('<img>').attr('src','/images/svg/close-outline.svg')
                                .addClass('svgbig-white')
                                .css({ 'margin-bottom' : '2rem',
                                        'margin-top' : 0,
                                        'cursor': 'pointer' });
        const p = $('<p>').text($(targetcategory).attr('data-catdesc'));
        $(categorygallery).append(h2).append(close).append(p);
        let gallery = new Gallery($(categorygallery),'');
        gallery.addImages(response.filenames, 'KNIFE');
        $(categorygallery).show();
        categorygalleryactive = true;
        $(close).on('click', (event) => {
                event.preventDefault();
                $(categorygallery).empty();
                $(categorygallery).hide();
                window.location = '#categories';
            });
    }
    // ----------------------------------------
    function loadPublishedCatalog(allpublished) {
        allpublished.forEach( knife => {
            const payload = {
                "knifeid" :  knife.id,
            }
            $.ajax({
                type: "POST",
                url: '/knives/public/getimages',
                data: JSON.stringify(payload),
                dataType: "json",
                async: true,
                success: function (response) {
                    buildCard(response, cardsection);
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
                url: '/slides/public/getdiapos',
                data: JSON.stringify(payload),
                dataType: "json",
                async: true,
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
        let slider = new Slider(container, timing, description, allimages);     // Build the slider frame
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
    // ---------------------------------------- Can send contact request ?
    function buttonActivation() {
        const maregex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        validEmail = maregex.test($("#contact_email").val());
        const message = $("#contact_text").val();
        if(message.length < 10 || message.length > 200) {
            validText = false;
        }
        else {
            validText = true;
        }

        if(validEmail & validText){
            $("#contactrequest").addClass('active').removeClass('inactive')
                    .prop('disabled', false);
        }else{
            $("#contactrequest").addClass('inactive').removeClass('active')
                    .prop('disabled', true);
        }  
    }
    // ---------------------------------------- Send the email
    function buttonClicked() {
        let choosedid = 0;
        let choosedname = '';
        switch($('select option:selected').val()) {
            case 'infoknife':
                choosedid = $('#contact_reservation option:selected').val();
                choosedname = $('#contact_reservation option:selected').html();
                break;
        }
        if(choosedid != 0) {
            console.log(`For knife : ${choosedname} / ${choosedid}`);
        }
        // Build the request object
        if($('#contact_object option:selected').val() === 'infoknife') {
            chooseid = $('#contact_reservation option:selected').val();
        }
        let payload = {
            'email': $("#contact_email").val(),
            'infotype': $('#contact_object option:selected').val(),
            'message': $("#contact_text").val(),
            'knifeid': choosedid,
            'adminmail': $props.getAdministratorEmail()
        }
        let ticker = '>';
        $('#feedback').text(ticker).css("color",  "yellow");
        let tid = setTimeout(() => {
            ticker = ticker + '>';
            $('#feedback').text(ticker);
        }, 1000);
        $.ajax({
            type: "POST",
            url: '/public/contactrequest',
            data: JSON.stringify(payload),
            dataType: "json",
            async: true,
            success: function (response) {
                clearTimeout(tid);
                console.log(response);
                $('#feedback').text(response.message)
                    .css("color",  "green");
                $('#contact_email').val('');
                $('#contact_text').val('');
                $('#contact_object').val('info');
                $(infoknife).hide();
                $("#contactrequest").addClass('inactive').removeClass('active')
                    .prop('disabled', true);
                setTimeout(() => {
                    $('#feedback').text('');
                }, 5000);
            },
            error: function (xhr) {
                clearTimeout(tid);
                console.log(xhr);
                $('#feedback').text(response.message)
                    .css("color",  "red");
                ;
                setTimeout(() => {
                    $('#feedback').val('');
                }, 5000);
            }
        });    
    }
})

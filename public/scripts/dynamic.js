//  -------------------------
//  dynamic.js
//  -------------------------
$(document).ready(function () {
    $props.load();
    console.log(`[${$props.version()} ]` );

    const cardsmenu = $("#cardsmenu");
    const cardsgallery = $('#cardsgallery');
    const gallerymenu = $("#gallerymenu");
    const gallerysection = $('#thegallery');
    const categoriesmenu = $('#categoriesmenu');
    const categorysection = $('#categories');
    const categoryslider = $('#categoryslider');    // 1st gallery zoom test
    const categorygallery = $('#categorygallery');  // 2nd gallery zoom implementation
    const menuHamburger = $(".menu-hamburger");
    const navLinks = $(".nav-links");
    const infoknife = $('.knife');
    
    let allcategoriesknives = [];
    let allcategoriesimage = [];

    // Initial state of UI
    $('#globalfullscreen').hide();
    $('#cardzoom').hide();
    $('#zoomer').hide();
    $(infoknife).hide();
    $(gallerymenu).hide();
    $(cardsmenu).hide();
    $(categoriesmenu).hide();
    $(gallerysection).hide();
    $(cardsgallery).hide();
    $(categorysection).hide();
    $(categoryslider).hide().attr('name', 'dynamic');

    let validEmail = false;
    let validText = false;
    let categorygalleryactive = false;
    let knifeslideractive = false;

    // various handlers
    $('.langselector').on('click', (event) => {
        const choice = $(event.target).data('lang');
        console.log(`Switch lang to ${choice}`);
        $props.set('applang', choice);
        $props.save();
    })
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
    getPublishedKnives()
    getHtmlTemplates();

    /** --------------------------------------------------------
     * @param container
     *      div element used to load all categories 
     *      The categories list depends of published knives
     * @param categories
     *      The categories list, related to published knives
     *      It's built by getPublishedKnives()
    */
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
    // --------------------------------------------------------
    /** 
     * @param container <div> to be used for loading category cards 
     * @param response json data for the category images
     * @param category The category object
     */
    function AddCategory(container, response, category) {
        const div = $('<div></div>').addClass('catcard');
        const h2 = $('<h2>').text(category.catfullname).addClass('heroh2');

        const img = $('<img>')
                .attr('data-catid', category.catid)
                .attr('data-catname', category.catname)
                .attr('data-catdesc', category.catdesc)
                .attr('data-related', response.relatedcategories);
        // Check the category has an associated image, otherwise get a default
        if(response.catimage === null) {
            response.catimage = `/BastosBG3.webp`;
            $(img).attr('src',`${$props.rootimageslocation()}/${response.catimage}`)
        }
        else {
            $(img).attr('src',`${$props.categoryimageslocation()}/${response.catimage}`)
        }
        allcategoriesimage.push({
            'catid': category.catid,
            'catname': category.catname,
            'catdesc': category.catdesc,
            'catphoto': response.catimage,
            'knifeid': response.knivesid[0],
            'related': response.relatedcategories
        });
        $(img).on('click', (event) => {
            event.preventDefault();
            if (categorygalleryactive) {
                $(categorygallery).empty().hide();
                categorygalleryactive = false;
            }
            // zoomCategory(event.target, 'SLIDER');
            // zoomCategory(event.target, 'GALLERY');
            zoomCategory(event.target);
        })
        $(div).append(h2);
        $(div).append(img);
        $(container).append(div);
    }
    /** --------------------------------------------------------
     * @param targetcategory : The category DOM element 
     * @param zoomode : 'NOTHING' or 'SLIDER' or 'GALLERY'
     *                   Drives the way category photos are displayed
    */
    function zoomCategory(targetcategory, zoomstyle = 'NOTHING') {
        const categoryid = $(targetcategory).data('catid');
        const catname = $(targetcategory).data('catname');
        const catdesc = $(targetcategory).data('catdesc');
        let catrelated = [];
        try {
            catrelated = $(targetcategory).data('related').split(',');
        }
        catch(error ) {
            // Any associated category ?
            let singleassoc = $(targetcategory).data('related');
            if(singleassoc.length !== 0) {
                catrelated[0] = singleassoc;
            }
        }
        const payload = {
            'catid': categoryid,
            'single': false
        }
        $.ajax({
            type: "POST",
            url: '/knives/public/categoryimages',
            dataType: "json",
            async: false,
            data: JSON.stringify(payload),
            success: function (response) {
                switch(zoomstyle) {
                    case 'SLIDER': buildSlider(targetcategory, response);
                                    break;
                    case 'GALLERY': buildGallery(targetcategory, response);
                                    break;
                }
                // --------------------------------------------------------
                // Get the related categories and display a small link 
                // in the card section header
                // Update the card section with deduplicated knives list
                // images and descriptions
                const dedupkniveslist = sortedUnique(response.knivesid);
                console.log(`Now update the card section with these knives ${dedupkniveslist}`);
                let activeknives = [];
                dedupkniveslist.forEach(knifeId => {
                    knifeincard = allcategoriesknives.find((knife) => knife.id === knifeId);
                    console.log(`Add ${knifeincard.knifename} with ID ${knifeincard.id} to the card section`);
                    activeknives.push(knifeincard);
                });
                if(loadPublishedCatalog(activeknives, categoryid,catname, catdesc, catrelated)) {
                    $(cardsgallery).show().fadeIn(2000);
                }
                window.location = '#cardsgallery';
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });    
    }
    /** -------------------------------------------------------------
     *  @param targetcategory : The category UI object 
     *  @param response : Images files list from the json call
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
    /** -------------------------------------------------------------
     *  @param targetcategory : The category UI object 
     *  @param response : Images files list from the json call
     **/
    function buildGallery(targetcategory, response) {
        const container = $('<div>').attr('id', 'gallerycontainer');
        const h2 = $('<h2>').text($(targetcategory).data('catname'))
                            .addClass('heroh2');
        const close = $('<img>').attr('src',$props.svgimageslocation()+'/close-outline.svg')
                                .addClass('svglarge-white')
                                .css({ 'margin-bottom' : '2rem',
                                        'margin-top' : 0,
                                        'cursor': 'pointer' });
        const p = $('<p>').text($(targetcategory).attr('data-catdesc'));
        $(container).append(h2).append(p);
        $(categorygallery).append(container);
        let gallery = new Gallery($(categorygallery),'');
        gallery.addImages(response.filenames, 'KNIFE', response.knivesid);
        window.location = '#categorygallery';
        $(categorygallery).fadeIn(2000);
        categorygalleryactive = true;
        $(categorygallery).off('mousedown').on('mousedown', (event) => {
            event.preventDefault();
            if(event.target.nodeName !== 'IMG') {   // Close the gallery zoom?
                $(categorygallery).fadeOut(800, () => {
                    $(categorygallery).empty();
                    $(cardsgallery).empty().hide();
                    window.location = '#categories';
                });
            }
            else{
                console.log(`Now display photos for ${$(event.target).data('knifeid')}`);
                const payload = {
                    "knifeid" :  $(event.target).data('knifeid'),
                }
                $.ajax({
                    type: "POST",
                    url: '/knives/public/getimages',
                    data: JSON.stringify(payload),
                    dataType: "json",
                    async: true,
                    success: function (response) {
                        displayKnifeSlider(response.knifeName,
                                                response.knifedesc,
                                                response.images);
                    },
                    error: function (xhr) {
                        console.log(xhr);
                    }
                });        
            }
        });
    }
    /** -------------------------------------------------------------
     * @param knifename     The knife name
     * @param knifedesc     Description
     * @param knifeimages   Related images
     */
    function displayKnifeSlider(knifename, knifedesc, knifeimages) {
        const h2 = $('<h2>').text(knifename)
                    .addClass('heroh2');
        const p = $('<p>').text(knifedesc);
        $(categoryslider).append(h2).append(p);
        let slider = new Slider($(categoryslider), 10, '', knifeimages, 'KNIFE');
        $("body").css("overflow", "hidden");
        $(categoryslider).css({'top': window.scrollY,
            'left': 0, 'z-index': 1000})
            .show()
            .on('click', (event) => {
                event.preventDefault();
                if(event.target.nodeName !== 'IMG') {   // Close the gallery zoom?
                    knifeslideractive = false;
                    $(categoryslider).empty();
                    $(categoryslider).hide();
                    $("body").css("overflow", "auto");
                }
            });
    }
    /** -------------------------------------------------------------
     * @param {*} allpublished List of published knives for a given category
     * @param {*} categoryid The category ID to which the knives belong
     * @param {*} catname Category name
     * @param {*} catdesc Category full description
     *                    These 2 strings are displayed in the zoomed
     *                    category header
     * @returns 
     *          false if the category is already displayed
     *          true if the category has been displayed
     */
    function loadPublishedCatalog(allpublished, categoryid,
                                    catname, catdesc, catrelated) {
        // -------------------------------------------------------------
        // Track double click or double request
        let displayedcateoryid = parseInt($(cardsgallery).attr('data-catid'));
        console.log(`*** Current : ${displayedcateoryid} requested : ${categoryid}`);
        if( !isNaN(displayedcateoryid) && displayedcateoryid === categoryid) {
            console.log(`Category ${categoryid} already display`);
            return false;
        }
        else {
            console.log(`Category ${categoryid} display`);
            $(cardsgallery).empty().attr('data-catid', categoryid);
        }
        // -------------------------------------------------------------
        // Build the cards gallery
        $(cardsgallery).append($('<div>').attr('id', 'cardscontainer'));
        $('#cardscontainer').append($('<h2>').text(catname))
        $('#cardscontainer').append($('<p>').text(catdesc))
        // Add in the header the image of any related category
        let relcatcontainer = $('<div>').attr('id', 'relatedcategories').addClass('related');
        for( let idx = 0; idx < catrelated.length; ++idx) {
            /**
             * Search for associated categories. This association will be ignored if  
             * the associated category is currently not used by by any knife !!!!!
            */
           allcategoriesimage.find( (current, index) => {
               if(current.catid === parseInt(catrelated[idx])) {
                   console.log(`************ ${catname} is associated to : ${current.catname} with ${current.catphoto}`);
                   let relcard = $('<div>').addClass('relatedcard');
                   // Data attributes on the container, to be used by zoomCaegory()
                   $(relcard).append($('<p>').text(current.catname))
                            .append($('<img>')
                                    .attr('src',  `${$props.categoryimageslocation()}/${current.catphoto}`)
                                    .attr('data-knifeid', current.knifeid))
                            .attr('data-catname', current.catname)
                            .attr('data-catdesc', current.catdesc)
                            .attr('data-knifeid', current.knifeid)
                            .attr('data-catid', current.catid)
                            
                            .attr('data-related', current.related);
                   $(relcatcontainer).append(relcard);
                   $(relcard).on('mousedown', (event) => {
                        event.stopImmediatePropagation();
                        let target = event.target;
                        console.log(`***** ${event.target.nodeName}`);
                        // Check the image or the paragraph have not been clicked
                        // If so, switch to parent DIV, which holds the data
                        if((event.target.nodeName === 'P')||
                                (event.target.nodeName === 'IMG')){
                            target = $(event.target).parent();
                        }
                        $(cardsgallery).fadeOut(800, () => {
                            // Now reload the category zoom
                            zoomCategory(target);
                        });
                   })
                }    
            });    
        };
        if($(relcatcontainer).children().length > 0)   {
            $(relcatcontainer).prepend($('<p>').text($labels.get('interested')));
            $('#cardscontainer').append(relcatcontainer);
        }      
        allpublished.forEach( knife => {    // Cards loop
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
                    buildCard(response, $('#cardscontainer'));
                },
                error: function (xhr) {
                    console.log(xhr);
                }
            });    
        })
        $(cardsgallery).off('mousedown').on('mousedown', (event) => {
            event.preventDefault();
            if(event.target.nodeName !== 'IMG') {   // Close the gallery ?
                $(cardsgallery).fadeOut(800, () => {
                    $(cardsgallery).empty().hide().attr('data-catid', 0);
                    window.location = '#categories';
                });
            }
            else {
                if(!knifeslideractive) {
                    knifeslideractive = true;                    
                    const payload = {
                        "knifeid" :  $(event.target).data('knifeid'),
                    }
                    $.ajax({
                        type: "POST",
                        url: '/knives/public/getimages',
                        data: JSON.stringify(payload),
                        dataType: "json",
                        async: true,
                        success: function (response) {
                            displayKnifeSlider(response.knifeName,
                                                    response.knifedesc,
                                                    response.images);
                        },
                        error: function (xhr) {
                            console.log(xhr);
                        }
                    });        
                }
            }
        });
        return true;
    }
    /**
     * Build and display a slider 
     * @param {*} allimages Associated images
     * @param {*} timing  Delay when automatic sliding is active
     * @param {*} description A short slider description displayed above the images
     * @param {*} container The target element where the slider will be instanciated
     */
    function buildImagesSlider(allimages, timing, description, container) {
        console.log(`Container name is ${$(container).attr('name')} for ${allimages.length} images`);
        let slider = new Slider(container, timing, description, allimages);     // Build the slider frame
    }
    /**
     * Build and display a gallery of images
     * @param {*} allimages Associated images
     * @param {*} description A short gallery description displayed above the images
     * @param {*} container The target element where the gallery will be instanciated
     */
    function buildImagesGallery(allimages, description,  container) {
        console.log(`Container name is ${$(container).attr('name')} for ${allimages.length} images`);
        let gallery = new Gallery(container, description);
        gallery.addImages(allimages);
    }
    /**
     * Build a single card to be displayed in a parent element
     * @param {*} response json data containing some knife information
     * @param {*} container The target element where the card will be displayed
     */
    function buildCard(response, container ) {
        let thecard = $('<div>').attr('id', `knifeid-${response.knifeId}`)
                                    .addClass('cardframe');
        let card  = new Card(thecard, response);
        container.append(thecard);
        // Set a knife ID for future zoom
        $(thecard).find('img').attr('data-knifeid', response.knifeId);
    }
    // ---------------------------------------------------------------
    //  data getters
    // ---------------------------------------------------------------
    /**
     * Request the active diaporamas from the DB
     */
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
    /**
     * Request the active knives from the DB
     */
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
                    allcategoriesknives = response.knives;
                }
            },
            error: function (xhr) {
                console.log(xhr);
            }
        });    
    }
    /**
     * Find dynamic html pieces
     */
    function getHtmlTemplates() {
        $(".htmltemplate").each(function (index, element) {
            const thelang = $(this).data('lang');
            // element == this
            const templatename = $(this).data('templatename');
            $(this).load(`/templates/${thelang}/${templatename}.html`);
        });
    }
    /**
     * Find and load active diaporamas in the page
     * @param {*} allactive The diaporamas list to be displayed
     */
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
    //----------------------------------------------------------------
    // Utilities
    //----------------------------------------------------------------
    /**
     * Fields checker used to activate a send button when the proper data
     * has been entered
     */
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
    /**
     * Buils and send a contact email
     */
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
    /**
     * Sort knives ID
     * @param {*} thearray Some numeric data to be sorted
     * @returns the sorted array
     */
    function sortedUnique(thearray) {
        return thearray.sort().filter(function(item, pos, ary) {
            return !pos || item != ary[pos - 1];
        });
    }
})

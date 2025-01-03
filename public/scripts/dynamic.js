//  -------------------------
//  dynamic.js
//  -------------------------

import Logger  from './classes/logger.js';
import Slider from './classes/slider.js';
import News from './classes/news.js';
import Card from './classes/card.js';
import Timer from './classes/timer.js';
import Flash from './classes/flash.js';
import $props from './properties.js'
import $labels from './translations/labels.js';

$(document).ready(function () {
    $props.load();

    const cardsmenu = $("#cardsmenu");
    const categoriesmenu = $('#categoriesmenu');
    const newsmenu = $('#newsmenu');
    const categorygallery = $('#categorygallery');
    let allcategories = [];                         // Store the active categories list found in DB
    let allcategoriesLoaded = false;                // Check the category section is displayed
    let menucontactornews = false;                  // Used to track contact or news menu entry
    const knivesgallery = $('#knivesgallery');
    const newsgallery = $('#newsgallery');
    const slider = $('#slider');
    const menuHamburger = $(".menu-hamburger");
    const navLinks = $(".nav-links");
    const infoknife = $('.knife');
    const flashzone = $('.flash');
    const flash = new Flash();
    let newslist = [];
    let activenews = [];

    $(flashzone).hide();
    // Single page zones management
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            switch($(entry.target).attr('id')) {
                case 'knivesgallery': 
                    if(entry.isIntersecting) {
                        $(knivesgallery).attr('active','').removeAttr('inactive');
                    }
                    else{
                        $(knivesgallery).attr('inactive','').removeAttr('active');
                    }   
                    break;
                case 'categorygallery': 
                    if(entry.isIntersecting) {
                        console.log(`Categories should be displayed`);
                        if(!allcategoriesLoaded && !menucontactornews) {
                            displayActiveCategories(allcategories);
                            console.log(`Categories are now displayed `);
                            allcategoriesLoaded = true;
                        }
                        else {
                            menucontactornews = false;
                        }
                    }
                    break;
                case 'newsgallery': 
                    if (!window.location.href.endsWith('contactrequest')) {                        
                        if(entry.isIntersecting) {
                            if(newslist.length === 0) {
                                loadActiveNews();
                            }
                        }
                    }
                    break;
                default:    // Did a news card become visibile or invisible ?
                    if($(entry.target).hasClass('news__details') && newslist.length !== 0) {
                        // Find the related news card in the array
                        let newscard = newslist.find( n => n.id === $(entry.target).attr('id'));                     
                        if(entry.isIntersecting && !newscard.active) {
                            if(!newscard.displayed) {
                                newscard.newsobject.displayImages();
                            }
                            newscard.active = true;
                            newscard.displayed = true;
                            $(entry.target).attr('active','').removeAttr('inactive');
                        }
                        // else{
                        //     $(entry.target).attr('inactive','').removeAttr('active');
                        //     newscard.active = false;
                        // }   
                    }
                    break;
            }
        })
    });
    observer.observe(document.querySelector('#knivesgallery'));
    observer.observe(document.querySelector('#newsgallery'));
    observer.observe(document.querySelector('#categorygallery'));
    observer.observe(document.querySelector('.flash'));
    
    // Initial state of UI
    $('#zoomer').hide();
    $(infoknife).hide();
    $(cardsmenu).hide();
    $(newsmenu).hide();
    $(categoriesmenu).hide();
    $(knivesgallery).hide().attr('inactive', '').removeAttr('active');
    $(categorygallery).hide();
    $(slider).hide();
    // Shall we display the news menu
    countActiveNews();

    let validEmail = false;
    let validText = false;
    let categorygalleryactive = false;
    let knivesgalleryactive = false;
    let knifeslideractive = false;

    // Determine if running in dev or prod mode
    const devmode = $('.debug').length === 1 ? 'dev' : 'prod';
    const logger = new Logger(devmode);
    logger.info(`[${$props.version()} ]` );

    // Handlers
    $('#contactmenu').on('click', (event) => {
        console.log('Contact requested');
        menucontactornews = true;
    })
    $('#categoriesmenu').on('click', (event) => {
        console.log('Categories requested');
        menucontactornews = false;
        if(!allcategoriesLoaded) {
            displayActiveCategories(allcategories);
            console.log(`Categories are now displayed `);
            allcategoriesLoaded = true;
        }
    })
    $('#newsmenu').on('click', (event) => {
        menucontactornews = true;
        console.log('News requested');
        if(newslist.length === 0) {
            loadActiveNews();
        }
    })
    /**
     * sliderclosed is sent by the slider class
     * when the slider is destroyed
     */
    $(slider).on('sliderclosed', function () {
        knifeslideractive = false;
        closeSlider();
    });
    $('.langselector').on('click', (event) => {
        const choice = $(event.target).data('lang');
        logger.debug(`Switch lang to ${choice}`);
        $props.set('applang', choice);
        $props.save();
    })
    $(menuHamburger).on('click', function () {
        $(navLinks).toggleClass('mobile-menu');
    });
    $(`.nav-links, .mobile-menu` ).on('click', (element) => {
        $(navLinks).toggleClass('mobile-menu');
    });
    // The browser back action
    $(window).on('popstate', function(event) {
        //console.log(event);
        if(knifeslideractive) {
            knifeslideractive = false;
            closeSlider();
        }
        if($('#zoomer').hasClass('zoomer')) {
            $("#zoomer").removeClass("zoomer").empty().hide();
        }
    });
    $(window).resize( () => {
        knifeslideractive = false;
        closeSlider();
        if($('#zoomer').hasClass('zoomer')) {
            $("#zoomer").removeClass("zoomer").empty().hide();
        }
    })
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

    getHtmlTemplates();
    getActiveCategories();
    /**
     * Request the active categories from the DB
     * To be considered active, a category must hold at least 
     * one active knife
     */
    function getActiveCategories() {
        let timer = new Timer();
        timer.startTimer();
        $.ajax({
            type: "GET",
            url: '/knives/public/getactivecategories',
            dataType: "json",
            async: true,
            success: function (response) {
                if(response.categoriescount != 0) {
                    allcategories = response.activecategories;  // Store for later use whenthe page section
                                                                // becomes visible
                    timer.stopTimer();
                    logger.info(`Found ${response.categoriescount} active categories in ${timer.getElapsedString()}`);
                    $(categoriesmenu).show();
                    $(categorygallery).show();
                }
            },
            error: function (xhr) {
                logger.error(xhr);
            }
        });    
    }
    /**
     * 
     * @param {*} activecategories  The array of active categories to be displayed
     *                              To be considered active, a category must hold at least 
     *                              one active knife
     */
    function displayActiveCategories(activecategories) {
        // Build the cards gallery
        const catzone = $('<div></div>').addClass('catzone');
        activecategories.forEach(cat => {
            const div = $('<div></div>').addClass('catzone__card');
            $(div).append($('<h2>').text(cat.catname));
            // Check the category has an associated image, otherwise get a default
            if(cat.catimage === null) {
                cat.catimage = `${$props.rootimageslocation()}/${$props.defaultcategoryimage()}`;
            }
            const img = $('<img>').attr('src',`${$props.categoryimageslocation()}/${cat.catimage}`)
                .attr('data-catid', cat.catid)
                .attr('data-catname', cat.catname)
                .attr('data-catdesc', cat.catdesc)
                .css('transform', `rotate(${cat.rotation}deg)`);
            $(div).append(img);
            $(img).on('click', (event) => {
                event.preventDefault();
                if (categorygalleryactive) {
                    $(categorygallery).empty().hide();
                    categorygalleryactive = false;
                }
                displayOneCategory(event.target);
            })
            $(catzone).append(div);
        });
        $(categorygallery).append(catzone);
    }
    /**
     *
     * @param {*} target A DOM element containing all category parameters
     *                      to be detailed 
     */
    function displayOneCategory(target) {
        const categoryid = $(target).data('catid');
        const catname = $(target).data('catname');
        const catdesc = $(target).data('catdesc');
        // Track double click or double request
        let displayedcateoryid = parseInt($(knivesgallery).attr('data-catid'));
        if( !isNaN(displayedcateoryid) && displayedcateoryid === categoryid) {
            window.location = '#knivesgallery';
        }
        else {
            $(knivesgallery).css('display', 'none').empty().attr('data-catid', categoryid);
            // The category page header
            const gallerycontainer = $('<div>').addClass('cards');
            const cardsheader = $('<div>').attr('id', 'cards__header').addClass('cards__header');
            $(cardsheader).append($('<h2>').text(catname));
            $(cardsheader).append($('<p>').text(catdesc));
            $(gallerycontainer).append(cardsheader);
            $(knivesgallery).append(gallerycontainer);
            window.location = '#knivesgallery';
            $(knivesgallery).attr('active','').removeAttr('inactive').css('display', 'block');
            // The knives
            displayKnives(gallerycontainer, categoryid);
        }
    }
    /**
     * 
     * @param {*} container The DOM element into which the knives cards 
     *                      will be displayed
     * @param {*} categoryid The category containing the knives
     */
    function displayKnives(container, categoryid) {
        //window.history.replaceState({'SPAloc': 'displayKnives'}, "", 'main');
        knivesgalleryactive = true;
        const payload = {
            'catid': categoryid,
        }
        let timk = new Timer(); timk.startTimer();
        // Get knives
        $.ajax({
            type: "POST",
            url: '/knives/public/getcategoryknives',
            dataType: "json",
            async: false,
            data: JSON.stringify(payload),
            success: function (response) {
                response.knivesids.forEach( oneknife => {       
                    // Get knife images and build the display card
                    if(oneknife.published === true) { // Is the knife published on site ? 
                        $.ajax({
                            type: "POST",
                            url: '/knives/public/getimages',
                            dataType: "json",
                            async: false,
                            data: JSON.stringify( { 'knifeid': oneknife.id } ),
                            success: function (response) {
                                buildCard(response, container);
                            },
                            error: function (xhr) {
                                logger.error(xhr);
                            }
                        });    
                    }
                })
                timk.stopTimer();
                logger.debug(`Loaded knives cards in ${timk.getElapsedString()}`);
                // Now the linked categories
                let linkcontainer = $('<div>').attr('id', 'relatedcategories').addClass('cards__links');
                response.relatedcategories.forEach(element => {
                    let relcard = $('<div>').addClass('cards__links__details');
                    $(relcard).append($('<p>').text(element.catname))
                            .append($('<img>')
                                    .attr('src',  `${$props.categoryimageslocation()}/${element.catphoto}`)
                                    .css('transform', `rotate(${element.rotation}deg)`)
                                    .attr('data-catname', element.catname)
                                    .attr('data-catdesc', element.catdesc)
                                    .attr('data-catid', element.catid));
                   $(linkcontainer).append(relcard);
                   $(relcard).on('click', (event) => {
                        event.stopImmediatePropagation();
                        let target = event.target;
                        // Check the image or the paragraph have not been clicked
                        // If so, switch to parent DIV, which holds the data
                        if(event.target.nodeName === 'P'){
                            target = $(event.target).next();
                        }
                        $(knivesgallery).attr('inactive', '').removeAttr('active');
                        $(knivesgallery).empty().hide().attr('data-catid', 0);
                        displayOneCategory(target);
                   })
                });
                if($(linkcontainer).children().length > 0)   {
                    $(linkcontainer).prepend($('<p>').text($labels.get('interested')));
                    $('.cards').append(linkcontainer);
                }
                armKnivesGalleryHandlers();        
            },
            error: function (xhr) {
                logger.error(xhr);
            }
        });

    }
    // To manage user inter actions with images & text
    function armKnivesGalleryHandlers() {
        $(knivesgallery).off('mousedown').on('mousedown', (event) => {
            event.preventDefault();
            if(event.target.nodeName !== 'IMG') {   // Close the gallery ?
                $(knivesgallery).attr('inactive', '').removeAttr('active');
                $(knivesgallery).empty().hide().attr('data-catid', 0);
                knivesgalleryactive = false;
                window.location = '#categorygallery';
            }
            else {
                if(!knifeslideractive && ($(event.target).data('knifeid') != undefined)) {
                    knifeslideractive = true;                    
                    const payload = {
                        "knifeid" :  $(event.target).data('knifeid'),
                    }
                    const timer = new Timer();
                    timer.startTimer();
                    logger.debug(`Load SLIDER knife images from the DB`);
                    $.ajax({
                        type: "POST",
                        url: '/knives/public/getimages',
                        data: JSON.stringify(payload),
                        dataType: "json",
                        async: true,
                        success: function (response) {
                            timer.stopTimer();
                            logger.debug(`Loaded SLIDER images for ${response.knifeName}  from the DB in ${timer.getElapsedString()}`);
                            displayKnifeSlider(response.knifeName,
                                                    response.knifedesc,
                                                    response.images,
                                                    response.imagesrotations);
                        },
                        error: function (xhr) {
                            logger.error(xhr);
                        }
                    });        
                }
            }
        });
    }
    /** -------------------------------------------------------------
     * @param knifename     The knife name
     * @param knifedesc     Description
     * @param knifeimages   Related images
     */
    function displayKnifeSlider(knifename, knifedesc, knifeimages, imagesrotations) {
        $(slider).attr('name', 'slider');
        let dynslider = new Slider($(slider),
                            0, // New initial image index introduced : YT Jun 12 2024
                            10,
                            knifename,
                            knifeimages,
                            imagesrotations,
                            'KNIFE');
        $("body").css("overflow", "hidden");
        const { scrollTop, scrollHeight, clientHeight } = document.documentElement;
        // console.log(parseInt(scrollTop) , clientHeight, scrollHeight);
        $(slider).css({'top': window.scrollY,
            'left': 0, 'z-index': 1000})
            .css('display', 'flex');
    }
    /**
     * Destroy the slider window
     */
    function closeSlider() {
        $(slider).empty().hide();
        $("body").css("overflow", "auto");
    }
    /**
     * Build and display a slider 
     * @param {*} allimages Associated images
     * @param {*} timing  Delay when automatic sliding is active
     * @param {*} description A short slider description displayed above the images
     * @param {*} container The target element where the slider will be instanciated
     */
    function buildImagesSlider(allimages, timing, description, container) {
        let slider = new Slider(container, 0,  timing, description, allimages);     // Build the slider frame
    }
    /**
     * Build a single card to be displayed in a parent element
     * @param {*} response json data containing some knife information
     * @param {*} container The target element where the card will be displayed
     */
    function buildCard(response, container, cardindex=0 ) {
        let thecard = $('<div>').attr('id', `knifeid-${response.knifeId}`)
                                    .addClass('cards__frame');
        let card  = new Card(thecard, response, cardindex);
        container.append(thecard);
    }
    /**
     * Request the active news from the DB
     * Display the news menu if any
     */
    function countActiveNews() {
        let ttx = new Timer();
        ttx.startTimer();
        $.ajax({
            type: "GET",
            url: '/slides/public/getactivenews',
            dataType: "json",
            async: true,
            success: function (response) {
                ttx.stopTimer();
                logger.info(`Found ${response.activecount} news in ${ttx.getElapsedString()}`);
                if(response.activecount != 0) {
                    $(newsmenu).show();
                    activenews = response.activenews;
                }
            },
            error: function (xhr) {
                logger.console.error();(xhr);
            }
        });    
    }

    /**
     * The news Gallery is now visible, load its structure
     */
    function loadActiveNews() {
        if(activenews.length !== 0) {
            let ttx = new Timer();
            ttx.startTimer();
            loadNewsSections(activenews);                 
            ttx.stopTimer();
            logger.info(`Get images for ${activenews.length} news in ${ttx.getElapsedString()}`);
        }
    }
    /**
     * Find and load active news in the page
     * @param {*} allactive The news list to be displayed
     */
    function loadNewsSections(allactive) {
        let newscontainer = $('<div>').addClass('news');
        $(newsgallery).append(newscontainer);
          allactive.forEach(news => {
            const payload = {
                "newsname" :  news.name,
            }
            $.ajax({
                type: "POST",
                url: '/slides/public/getnews',
                data: JSON.stringify(payload),
                dataType: "json",
                async: false,       // can perform synchronously, not in a hurry and preserve news order on screen
                success: function (response) {
                    if(response.slidermode) {
                        if(response.timing === null) {
                            response.timing = 2;
                        }
                        buildImagesSlider(response.images, response.timing, response.description, newsgallery);
                    }
                    else {
                        buildNewsGallery(response.newsimages,
                                            response.imagesrotation,
                                            response.newsdescription,
                                            response.newsname,
                                            newscontainer);
                    }
                },
                error: function (xhr) {
                    logger.error(xhr);
                }
            });    
        });
        $(newsgallery).show();
    }
    /**
     * Build and display a news card. Store the card in an array
     * 
     * @param {*} allimages Associated images
     * @param {*} imagesrotation Images rotation factor for each image
     * @param {*} description A short news description displayed above the images
     * @param {*} diapo Data on the news to build (name....)
     * @param {*} container The target element where the news will be appended
     */
    function buildNewsGallery(allimages, imagesrotation, description, diapo, container) {
        const newsindex = newslist.length;
        let news = new News(container, description, allimages, imagesrotation, diapo, newsindex);
        observer.observe(document.querySelector(`#news-${newsindex}`));
        newslist.push({ id: news.getID(),
            newsobject: news,
            active: false,
            displayed: false
        });
    }
    /**
     * Find dynamic html pieces
     */
    function getHtmlTemplates() {
        let timer = new Timer();
        timer.startTimer();
        $(".htmltemplate").each(function (index, element) {
            const thelang = $(this).data('lang');
            // element == this
            const templatename = $(this).data('templatename');
            $(this).load(`/templates/${thelang}/${templatename}.html`);
        });
        logger.info(`Loaded template(s) in ${timer.getElapsedString()}`);
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
            logger.debug(`For knife : ${choosedname} / ${choosedid}`);
        }
        let messagetext = $("#contact_text").val();
        // Bypass the jquery interpreter which tries to transform 
        // any double ??
        messagetext = messagetext.replaceAll(/\?{1,}/gi, '?');
        let payload = {
            'email': $("#contact_email").val(),
            'infotype': $('#contact_object option:selected').val(),
            'message': messagetext,
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
                $('#feedback').text('');
                flash.flashSuccess('email', 
                                    $labels.get('emailok'),
                                    $labels.get('emailadvice'));
                $('#contact_email').val('');
                $('#contact_text').val('');
                $('#contact_object').val('info');
                $(infoknife).hide();
                $("#contactrequest").addClass('inactive').removeClass('active')
                    .prop('disabled', true);
            },
            error: function (xhr) {
                clearTimeout(tid);
                $('#feedback').text('');
                logger.error(xhr);
                flash.flashError('email', $labels.get('emailko'));
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

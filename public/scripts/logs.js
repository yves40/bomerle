// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();

    const zoom = $(".msgzoom");
    const showall = $('#showall');
    const hideall = $('#hideall');
    const nextpage = $('#nextpage');
    const previouspage = $('#previouspage');
    const zemessage= $('#zemessage');
    let pagenum = 1;

    hideAll();

    $('.levelselector').each(function (index, element) {
        // element == this
        $(this).click( (e) => { levelSelected(this); });
    });

    zoom.click(function (e) { e.preventDefault(); zoomMessage(this); });
    showall.click( (e) => { e.preventDefault(); showAll(); });
    hideall.click( (e) => { e.preventDefault(); hideAll(); });
    nextpage.click( (e) => {e.preventDefault(); page(1); });
    previouspage.click( (e) => {e.preventDefault(); page(-1); });
    // get some info on one or more dates fields.
    let alldatefields = [];
    getDateFields('date_range_startDate');
    getDateFields('date_range_endDate');
    // Set current date for startDate
    let currentdate = new Date().toISOString();
    const now = new Date();
    let enddate = new Date(new Date(now).setDate(now.getDate() + 31)).toISOString();
    updateDateFields(alldatefields[0], getDayMonthYear(currentdate));
    handleMonthSelection(alldatefields[0].twigmonth);
    updateDateFields(alldatefields[1], getDayMonthYear(enddate));
    handleMonthSelection(alldatefields[1].twigmonth);
    // ----------------------------------------------------------------------------
    // Level selection
    // ----------------------------------------------------------------------------
    function levelSelected(element) {
        let id = $(element).attr('id');
        if($(element).prop('checked')) {
            $(element).remove('checked');
        }
        // Now build the selector array
        let selectors = [];
        $('.levelselector').each(function (index, scan) {
            if($(scan).prop('checked')) {
                console.log(` ID : ${$(scan).attr('id')} ON`);
            }
            else{
                console.log(` ID : ${$(scan).attr('id')} OFF`);
            }
        });
    }
    // ----------------------------------------------------------------------------
    // pagination
    // ----------------------------------------------------------------------------
    function page(direction) {
        switch(direction) {
            case 1: console.log('Next');
                    ++pagenum;
                    break;
            case -1: console.log('Previous');
                    --pagenum;
                    break;
            default: console.log('Arghhhhh !!!');
                    break;
        }
        $(zemessage).text(`On page ${pagenum}`);
    }
    // ----------------------------------------------------------------------------
    // Zoom in, zoom out for log message details
    // ----------------------------------------------------------------------------
    function zoomMessage(element) {
        let msgzone = $(element).siblings('.msgdetails');
        let currentstate = $(msgzone).data('visible');
        console.log(`Currentstate : ${currentstate}`);
        if(currentstate) {
            $(msgzone).data('visible', false);
            $(msgzone).hide();
        }
        else {
            $(msgzone).data('visible', true);
            $(msgzone).show();
        }     
    }
    // ----------------------------------------------------------------------------
    // Show all messages details
    // ----------------------------------------------------------------------------
    function showAll() {
        $('.msgdetails').each(function (index, element) {
            // element == this
            $(this).data('visible', true);
            $(this).show();            
        });
    }
    // ----------------------------------------------------------------------------
    // Hide all messages details
    // ----------------------------------------------------------------------------
    function hideAll() {
        $('.msgdetails').each(function (index, element) {
            // element == this
            $(this).data('visible', false);
            $(this).hide();            
        });
    }
    // ------------------------------------------------------------------------------
    // Receives a base selector string concatenated with the TWIG generated IDs
    // Hope these IDs will not change in future versions
    // Returns an object with all relevant element handlers
    // ------------------------------------------------------------------------------
    function getDateFields(selector) {
        let dateUI = {};
        dateUI.selector = selector;
        dateUI.twigday = $(`#${selector}_day`);
        dateUI.twigmonth = $(`#${selector}_month`);
        dateUI.twigyear = $(`#${selector}_year`);
        dateUI.day29 = $(dateUI.twigday).find('option[value="29"]');
        dateUI.day30 = $(dateUI.twigday).find('option[value="30"]');
        dateUI.day31 = $(dateUI.twigday).find('option[value="31"]');
        $(dateUI.twigmonth).change(function (e) {  // Monitor month selection
            e.preventDefault();
            handleMonthSelection(this);
        });
        $(dateUI.twigyear).change(function (e) {  // Also Monitor year selection in case Feb is selected
            e.preventDefault();
            handleMonthSelection(this);
        });
        alldatefields.push(dateUI);
        return;
    }
    // ------------------------------------------------------------------------------
    // Based on previously identified fields, update the list selection with the 
    // event object data
    // ------------------------------------------------------------------------------
    function updateDateFields(dateUI, datevalues) {
        $(dateUI.twigday).val(datevalues.d);
        $(dateUI.twigmonth).val(datevalues.m);
        $(dateUI.twigyear).val(datevalues.y);
    }
    // ------------------------------------------------------------------------------
    function getDayMonthYear(thedate) {
        // 2023-02-15T18:59:45+01:00
        // The date is set to current by the controller before calling 
        // the form rendering, so we always have one in the proper format
        // even whe creating a new event
        let onlydate = thedate.split('T')[0];
        let theyear = onlydate.split('-')[0];
        let theday = onlydate.split('-')[2];
        let themonth = onlydate.split('-')[1];
        return { 
            y: parseInt(theyear),
            m: parseInt(themonth) ,
            d: parseInt(theday)
        }
    }
    // ------------------------------------------------------------------------------
    function handleMonthSelection(element) {
        // The ID analysis is based on the current TWIG behaviour !!!
        // It concatenates the name of the entity class and attributes names
        // with _day, _month, _year
        // Look into DateRange.php to understand.
        let elementid = $(element).attr('id');
        let selectorpieces = elementid.split('_');
        elementid = `${selectorpieces[0]}_${selectorpieces[1]}_${selectorpieces[2]}`;
        let dateUItarget = {};
        // Scan the array of date fields
        alldatefields.forEach( (val) => {
            if(val.selector === elementid) {
                dateUItarget = val;
            }
        });
        // Did the action come from the year field ? 
        // If yes, evaluate the proper month field
        if(($(element).attr('id')).includes('year')) {
            element = dateUItarget.twigmonth;
        }
        switch (parseInt($(element).val())) {
            case 4:
            case 6:
            case 9:
            case 11:
                dateUItarget.day29.show();
                dateUItarget.day30.show();
                dateUItarget.day31.hide();
                break;
            case 2:
                dateUItarget.day30.hide();
                dateUItarget.day31.hide();
                if(isLeapYear($(dateUItarget.twigyear).val())) {
                    dateUItarget.day29.show();
                }
                else {
                    dateUItarget.day29.hide();
                }
                break;
            default:
                dateUItarget.day29.show();
                dateUItarget.day30.show();
                dateUItarget.day31.show();
                break;
        }
    }
    // ------------------------------------------------------------------------------
    function isLeapYear(year) {
        return 0 == year % 4 && (year % 100 != 0 || year % 400 == 0);
    }
})


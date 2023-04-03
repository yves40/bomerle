// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();

    const timehelper = new timeHelper();


    // get some info on one or more dates fields.
    let alldatefields = [];
    searchDateFields();
    // Set current date for startDate and an earlier date for endDate
    // The offest is spsecified in properties.js
    let currentdate = new Date().toISOString();
    const now = new Date();
    let enddate = new Date(new Date(now).setDate(now.getDate() - $props.getLogsDateOffest())).toISOString();
    // Now, depending on the number of select date found, set them up
    // The 1st one will always be considered as the most future date
    alldatefields.forEach((element, index) => {
        if(index === 0)
            updateDateFields(element, getDayMonthYear(currentdate));
        else
            updateDateFields(element, getDayMonthYear(enddate));
    });
    tuneDates();

    // End of initialization 

    // ------------------------------------------------------------------------------
    // Search all select fields related to dates
    // ------------------------------------------------------------------------------
    function searchDateFields() {
        $("[id$=_day]").each(function (index, element) {
            // This parsing is highly dependent of the current TWIG field ID generation ;-(
            let selectorpieces = $(element).attr('id').split('_');  
            let selector = `#${selectorpieces[0]}_${selectorpieces[1]}_${selectorpieces[2]}`;
            console.log(`max : ${$(selector).attr('max')}`);
            getDateFields(selector);
        });
    }
    // ------------------------------------------------------------------------------
    // Receives a base selector string concatenated with the TWIG generated IDs
    // Hope these IDs will not change in future versions 
    // ------------------------------------------------------------------------------
    function getDateFields(selector) {
        let dateUI = {};
        dateUI.selector = selector;
        dateUI.twigday = $(`${selector}_day`);
        dateUI.twigmonth = $(`${selector}_month`);
        dateUI.twigyear = $(`${selector}_year`);
        dateUI.day29 = $(dateUI.twigday).find('option[value="29"]');
        dateUI.day30 = $(dateUI.twigday).find('option[value="30"]');
        dateUI.day31 = $(dateUI.twigday).find('option[value="31"]');
        $(dateUI.twigmonth).change(function (e) {  // Monitor month selection
            e.preventDefault();
            tuneDates();
        });
        $(dateUI.twigyear).change(function (e) {  // Monitor Year selection
            e.preventDefault();
            tuneDates();
        });
        $(dateUI.twigday).change(function (e) {  // Monitor Day selection
            e.preventDefault();
            tuneDates();
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
    // alldatefields[0] must normally contain the start date
    // which is the most recent and is set by default to now
    // ------------------------------------------------------------------------------
    function tuneDates() {
        let today = getDayMonthYear(new Date().toISOString());
        let firstdate = {};
        let notfirstdate = {};
        let datecollision = false;
        alldatefields.forEach( (element, index) => {
            const elementyear = $(element.twigyear).val();   // Save selected values
            const elementmonth = $(element.twigmonth).val();
            const elementday = $(element.twigday).val();
            if(index === 0) {
                firstdate.d = parseInt(elementday);
                firstdate.m = parseInt(elementmonth);
                firstdate.y = parseInt(elementyear);
                firstdate.date = new Date(elementyear, elementmonth - 1, elementday );
                firstdate.ms = firstdate.date.getTime();
            }
            else {
                notfirstdate.d = parseInt(elementday);
                notfirstdate.m = parseInt(elementmonth);
                notfirstdate.y = parseInt(elementyear);
                notfirstdate.date = new Date(elementyear, elementmonth - 1, elementday );
                notfirstdate.ms = notfirstdate.date.getTime();
            }
            refillYears(element.twigyear, today);           // Refill 
            $(element.twigyear).val(elementyear);           // Put it back
            refillMonths(element.twigmonth, elementyear, today);
            (element.twigmonth).val(elementmonth);
            refillDays(element.twigday, elementmonth, elementyear, today);
            (element.twigday).val(elementday);            
            // Now verify dates are properly set. The 1st one must be the latest
            if(firstdate.ms <= notfirstdate.ms) {
                datecollision = true;
                $('#before').addClass('yerror');
                $('#after').addClass('yerror');
                return false;   // Break the forEach loop
            }
            else {
                $('#before').removeClass('yerror');
                $('#after').removeClass('yerror');
            }
        });
        return;
    }
    // ------------------------------------------------------------------------------
    function refillDays(element, selectedmonth, selectedyear, today) {
        $(element).empty();
        smonth = parseInt(selectedmonth);
        syear = parseInt(selectedyear);
        let daylimit = 31;
        switch(smonth) {
            case 4:
            case 6:
            case 9:
            case 11:
                    daylimit = 30;
                    break;
            case 2:
                if(isLeapYear(selectedyear)) { daylimit = 29; }
                else { daylimit = 28;}    
                break;
            default:
                daylimit = 31;
        }
        // Final check to remove days over current day if year and month are the same
        if((smonth === today.m)&&(syear === today.y)) {
            daylimit = today.d;
        }
        for(let i = 1; i < daylimit + 1; ++i) {
            $(element).append($('<option>').val(i).text(i));
        }
    }
    // ------------------------------------------------------------------------------
    function refillMonths(element, selectedyear, today) {
        $(element).empty();
        syear = parseInt(selectedyear);
        let monthlimit = 13;
        if(syear === today.y) {
            monthlimit = today.m + 1;
        }
        for(let i = 1; i < monthlimit; ++i) {
            $(element).append($('<option>').val(i).text(timehelper.getMonthLabel(i)));
        }
    }
    // ------------------------------------------------------------------------------
    function refillYears(element, currentdate) {
        $(element).empty();
        for(let i = currentdate.y - 2; i < currentdate.y + 1; ++i) {
            $(element).append($('<option>').val(i).text(i));
        }
    }
    // ------------------------------------------------------------------------------
    function isLeapYear(year) {
        return 0 == year % 4 && (year % 100 != 0 || year % 400 == 0);
    }
})


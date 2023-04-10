// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();

    const timehelper = new timeHelper();


    // get some info on one or more dates fields.
    let alldatefields = [];
    searchDateFields();
    // Now, depending on the number of select date found, set them up
    // The 1st one will always be considered as the most future date
    let currentdate = new Date().toISOString();
    let currentyear;
    alldatefields.forEach((element, index) => {
        switch (element.initvalue) {
            case "today":
                updateDateFields(element, getDayMonthYear(currentdate));
                break;
            case "soy":
                currentyear = getDayMonthYear(currentdate).y;
                let soy = new Date(currentyear, 0, 2, 0, 1).toISOString();
                updateDateFields(element, getDayMonthYear(soy));
                break;
            case "eoy":
                currentyear = getDayMonthYear(currentdate).y;
                let eoy = new Date(currentyear, 11, 31, 23, 59).toISOString();
                updateDateFields(element, getDayMonthYear(eoy));
                break;
            case "nm":
                break;
            case "pm":
                break;
            case "asis":
            default:
                // Don't change currently set date
                break;
        }
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
            //
            // init is a parameter passed from the TWIG page, used to setup the initial select date value
            //      Possible values are : 
            //          today : Today (default)
            //          soy   : Start Of Year
            //          eoy   : End Of Year
            //          nm    : Next Month starting from today
            //          pm    : Previous Month starting from today
            //          asis  : Do not change
            // nofuture
            //          true: means no date can be set beyond the current date
            //          false: choose any date in the future
            // chronology
            //          n: Is the date position in the chronology order
            //          0: Means no matter
            //          1: is the first i.e Jan 01 2023 
            //          2: is the second i.e Jun 01 2023 
            //          3: is the third i.e Sep 01 2023 
            //          1 must be <= 2 <= 3
            let initialvalue = $(selector).attr('init');
            let nofuture = $(selector).attr('nofuture');
            let chronoposition = $(selector).attr('chronoposition');
            if(initialvalue === undefined) { initialvalue = 'today'; }
            if(chronoposition === undefined) { chronoposition = 0; }
            if(nofuture === undefined) { 
                nofuture = true; 
            }
            else {
                nofuture = (nofuture === 'true');   // Convert 
            }
            getDateFields(selector, initialvalue, nofuture, chronoposition);    // Pushed into an array all date fields
        });
    }
    // ------------------------------------------------------------------------------
    // Receives a base selector string concatenated with the TWIG generated IDs
    // Hope these IDs will not change in future versions 
    // ------------------------------------------------------------------------------
    function getDateFields(selector, initialvalue, nofuture, chronoposition) {
        let dateUI = {};
        dateUI.initvalue = initialvalue;
        dateUI.nofuture = nofuture;
        dateUI.selector = selector;
        dateUI.chronoposition = chronoposition;
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
    // This function refills all 3 date fields ( y, m, d ) and then check 
    // that the chronological dates order, if used,  is valid.
    // alldatefields contains all the displayed dates.
    // Each date has a chronology order set to 0 (no order) or 1, 2, 3...
    // ------------------------------------------------------------------------------
    function tuneDates() {
        let today = getDayMonthYear(new Date().toISOString());
        let dateslist = [];
        // Check dates fields
        alldatefields.forEach( (element, index) => {
            const elementyear = $(element.twigyear).val();   // Save selected values
            const elementmonth = $(element.twigmonth).val();
            let elementday = $(element.twigday).val();
            dateslist.push( { 'date': new Date(elementyear, elementmonth - 1, elementday ),
                              'ms': new Date(elementyear, elementmonth - 1, elementday ).getTime(),
                              'chronoposition': element.chronoposition,
                              'selector': element.selector
                            }
                          );
            refillYears(element.twigyear, today, element.nofuture);   // Refill 
            $(element.twigyear).val(elementyear);                     // Put it back
            refillMonths(element.twigmonth, elementyear, today, element.nofuture);
            (element.twigmonth).val(elementmonth);
            let daylimit = refillDays(element.twigday, elementmonth, elementyear, today, element.nofuture);
            // Check 31 was not selected before switching to a 30 or 28 month
            if(elementday > daylimit) elementday = daylimit;
            (element.twigday).val(elementday);            
        });
        // Check dates chronology order
        dateslist.sort( (d1, d2) => parseInt(d1.chronoposition) - parseInt(d2.chronoposition));
        dateslist.forEach( (element, index) => {
            if((index !== 0) && 
                ( parseInt(element.chronoposition) !== 0) &&
                (parseInt(dateslist[index-1].chronoposition) !== 0)) 
            {
                if(dateslist[index-1].ms > dateslist[index].ms) {
                    $(`${dateslist[index-1].selector} .input-group`).addClass('errordate').removeClass('noerrordate');
                    $(`${dateslist[index].selector} .input-group`).addClass('errordate').removeClass('noerrordate');
                    $('button').prop('disabled', true);
                }
                else { 
                    $(`${dateslist[index-1].selector} .input-group`).addClass('noerrordate').removeClass('errordate');
                    $(`${dateslist[index].selector} .input-group`).addClass('noerrordate').removeClass('errordate');
                    $('button').prop('disabled', false);
                }
            }
        });
        return;
    }
    // ------------------------------------------------------------------------------
    function refillDays(element, selectedmonth, selectedyear, today, nofuture) {
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
        if(nofuture) {
            if((smonth === today.m)&&(syear === today.y)) {
                daylimit = today.d;
            }
        }
        for(let i = 1; i < daylimit + 1; ++i) {
            $(element).append($('<option>').val(i).text(i));
        }
        return daylimit;    // To check the currently selected value
    }
    // ------------------------------------------------------------------------------
    function refillMonths(element, selectedyear, today, nofuture) {
        $(element).empty();
        syear = parseInt(selectedyear);
        let monthlimit = 13;
        if(nofuture) {
            if(syear === today.y) {
                monthlimit = today.m + 1;
            }    
        }
        for(let i = 1; i < monthlimit; ++i) {
            $(element).append($('<option>').val(i).text(timehelper.getMonthLabel(i)));
        }
    }
    // ------------------------------------------------------------------------------
    function refillYears(element, currentdate, nofuture) {
        $(element).empty();
        let loweryear = currentdate.y - 2;
        let upperyear = currentdate.y + 2;
        if(nofuture) {
            upperyear = currentdate.y + 1;
            loweryear = currentdate.y - 2;
        }
        for(let i = loweryear; i < upperyear; ++i) {
            $(element).append($('<option>').val(i).text(i));
        }
    }
    // ------------------------------------------------------------------------------
    function isLeapYear(year) {
        return 0 == year % 4 && (year % 100 != 0 || year % 400 == 0);
    }
})


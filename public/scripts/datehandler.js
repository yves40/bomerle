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
            default:
                updateDateFields(element, getDayMonthYear(currentdate));
                break;
        }
        
        // let now = new Date();
        // let enddate = new Date(new Date(now).setDate(now.getDate() - $props.getLogsDateOffest())).toISOString();
        
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
            // nofuture
            //          true: means no date can be set beyond the current date
            //          false: choose any date in the future
            let initialvalue = $(selector).attr('init');
            let nofuture = $(selector).attr('nofuture');
            if(initialvalue === undefined) { initialvalue = 'today'; }
            if(nofuture === undefined) { 
                nofuture = true; 
            }
            else {
                nofuture = (nofuture === 'true');   // Convert 
            }
            getDateFields(selector, initialvalue, nofuture);    // Pusheds into an array all date fields
        });
    }
    // ------------------------------------------------------------------------------
    // Receives a base selector string concatenated with the TWIG generated IDs
    // Hope these IDs will not change in future versions 
    // ------------------------------------------------------------------------------
    function getDateFields(selector, initialvalue, nofuture) {
        let dateUI = {};
        dateUI.initvalue = initialvalue;
        dateUI.nofuture = nofuture;
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
            refillYears(element.twigyear, today, element.nofuture);           // Refill 
            $(element.twigyear).val(elementyear);           // Put it back
            refillMonths(element.twigmonth, elementyear, today, element.nofuture);
            (element.twigmonth).val(elementmonth);
            refillDays(element.twigday, elementmonth, elementyear, today, element.nofuture);
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


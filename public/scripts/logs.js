// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();

    const zoom = $(".msgzoom");
    const showall = $('#showall');
    const hideall = $('#hideall');
    const nextpage = $('#nextpage');
    const previouspage = $('#previouspage');
    const zemessage= $('#zemessage');
    const searchtext = $('#searchtext');
    const pagesize = $props.getLogsPageSize();
    const dateoffset = $props.getLogsDateOffest();
    const slidingtime = $props.getSlidingTime();
    const nohurry = waitplease(() => getLogs());
    const timehelper = new timeHelper();


    let pagenum = 1;
    let logsnumber = 0;

    hideAll();

    $('.levelselector').each(function (index, element) {
        // element == this
        $(this).click( (e) => { levelSelected(this); });
    });
    // Some handlers
    zoom.click(function (e) { e.preventDefault(); zoomMessage(this); });
    showall.click( (e) => { e.preventDefault(); showAll(); });
    hideall.click( (e) => { e.preventDefault(); hideAll(); });
    nextpage.click( (e) => {e.preventDefault(); page(1); });
    previouspage.click( (e) => {e.preventDefault(); page(-1); });
    $(searchtext).keyup(function (e) {e.preventDefault(); nohurry();}); // Call DB with delay when user is typing

    $(previouspage).hide();
    // get some info on one or more dates fields.
    let alldatefields = [];
    getDateFields('date_range_startDate');
    getDateFields('date_range_endDate');
    // Set current date for startDate and an earlier date for endDate
    // The offest is spsecified in properties.js
    let currentdate = new Date().toISOString();
    const now = new Date();
    let enddate = new Date(new Date(now).setDate(now.getDate() - $props.getLogsDateOffest())).toISOString();
    updateDateFields(alldatefields[0], getDayMonthYear(currentdate));
    updateDateFields(alldatefields[1], getDayMonthYear(enddate));
    tuneDates();
    // Get and display the number of logs in the DB table
    getLogsNumber();
    console.log($props.logshandler());
    console.log(`Pagesize : ${pagesize}`);
    console.log(`Initial second date will be ${dateoffset} days earlier`);

    // End of initialization 

    // ----------------------------------------------------------------------------
    // Level selection
    // ----------------------------------------------------------------------------
    function levelSelected(element) {
        let id = $(element).attr('id');
        if($(element).prop('checked')) {
            $(element).remove('checked');
        }
        nohurry();
    }
    // ----------------------------------------------------------------------------
    // pagination
    // ----------------------------------------------------------------------------
    function page(direction) {
        switch(direction) {
            case 1:
                if(pagenum === 1) $(previouspage).show();
                ++pagenum;
                break;
            case -1: 
                if(pagenum !== 1) {
                    --pagenum;
                    if(pagenum === 1) {
                        $(previouspage).hide();
                    } 
                }
                break;
            default: 
                break;
        }
        getLogs();
    }
    // ----------------------------------------------------------------------------
    // Request new data based on all criterias
    // ----------------------------------------------------------------------------
    function getLogs() {
        let arguments = buildArguments();
        $.ajax({
            type: "POST",
            url: `/bootadmin/logs/page/${pagenum}`,
            dataType: "json",
            contentType: 'application/json',
            async: true,
            data: JSON.stringify({ 'allargs' : arguments}),
            success: function (response) {
                // Check the number of returned lines and manage the next page button
                if(response.logs.length < pagesize) {
                    $(nextpage).hide();
                }
                else { $(nextpage).show();}
                console.log(response.dql);
                console.log(response);
                updatePage(response);
            },
            error: (xhr) => {
                console.log(xhr.responseJSON.errmess);
            }
        });
        $(zemessage).text(`Page ${pagenum}`);
    }
    // ----------------------------------------------------------------------------
    // Update the logs list
    // ----------------------------------------------------------------------------
    function updatePage(data) {
        $('#loglist').empty();
        data.logs.forEach(element => {
            let newli = $('<li>');
            $(newli).addClass('list-group-item pt-0 pb-0');
            let row1 = $('<div>');
            
            row1.addClass('row mt-1 mb-0 pt-0 pb-0 textsmall');
            let datecol = $('<div>').addClass('col-2');
            let severitycol = $('<div>').addClass('col-2');
            let mailcol = $('<div>').addClass('col-3');
            let actioncol = $('<div>').addClass('col-2');
            let zoomcol = $('<div>').addClass('col msgzoom');
            // Some date formatting takes place here...
            // data.locale is provided by the AdminControllerLogs controller 
            // in the JSON call
            let th = new timeHelper();
            let eventdatetime = th.getDateTimeFromDate(element.logtime, data.locale);
            
            $(datecol).text(eventdatetime);
            $(severitycol).text(element.severityLabels[element.severity]);
            switch(element.severity) {
                case 2:
                    $(severitycol).addClass('ywarning');
                    break;
                case 3: 
                case 4: 
                        $(severitycol).addClass('yerror');
                        break;
            }
            $(mailcol).text(element.useremail);
            $(actioncol).text(element.action);
            let image = $('<img>').addClass('svgsmall-lightblue')
                .attr('src', '/images/svg/magnifying-glass-solid.svg' );
            $(zoomcol).append(image);
            $(zoomcol).click(function (e) { e.preventDefault(); zoomMessage(this); });
            $(row1).append(datecol).append(severitycol).append(mailcol)
                .append(actioncol).append(zoomcol);

            row2 = $('<div>').addClass('row mt-0 textsmall');
            detailcol = $('<div>').addClass('col msgdetails mt-0 mb-0');
            $(detailcol).data('visible', false).hide();

            let span = $('<span>');
            $(span).text(`Log Id: `).addClass('label');
            $(detailcol).append(span);
            span = $('<span>');
            $(span).text(`${element.id}`);
            $(detailcol).append(span);

            span = $('<span>');
            $(span).text(` Message : `).addClass('label');
            $(detailcol).append(span);
            span = $('<span>');
            $(span).text(`${element.message}`);
            $(detailcol).append(span);
            
            span = $('<span>');
            $(span).text(` Module : `).addClass('label');
            $(detailcol).append(span);
            span = $('<span>');
            $(span).text(`${element.module}`);
            $(detailcol).append(span);

            $(row2).append(detailcol);

            $(newli).append(row1).append(row2);
            $('#loglist').append(newli);
        });
    }
    // ----------------------------------------------------------------------------
    // Zoom in, zoom out for log message details
    // ----------------------------------------------------------------------------
    function zoomMessage(element) {
        let msgzone = $(element).parent().parent().find('.msgdetails');
        let currentstate = $(msgzone).data('visible');
        if(currentstate) {
            $(msgzone).data('visible', false);
            $(msgzone).slideUp(slidingtime);
        }
        else {
            $(msgzone).data('visible', true);
            $(msgzone).slideDown(slidingtime);
        }     
    }
    // ----------------------------------------------------------------------------
    // Show all messages details
    // ----------------------------------------------------------------------------
    function showAll() {
        $('.msgdetails').each(function (index, element) {
            // element == this
            $(this).data('visible', true);
            $(this).slideDown(slidingtime);            
        });
    }
    // ----------------------------------------------------------------------------
    // Hide all messages details
    // ----------------------------------------------------------------------------
    function hideAll() {
        $('.msgdetails').each(function (index, element) {
            // element == this
            $(this).data('visible', false);
            $(this).slideUp(slidingtime);            
        });
    }
    // ------------------------------------------------------------------------------
    // Receives a base selector string concatenated with the TWIG generated IDs
    // Hope these IDs will not change in future versions 
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
            $(element.twigyear).val(elementyear);            // Put it back
            refillMonths(element.twigmonth, elementyear, today);
            (element.twigmonth).val(elementmonth);
            refillDays(element.twigday, elementmonth, elementyear, today);
            (element.twigday).val(elementday);            
            // Now verify dates are properly set. The 1st one must be the latest
            if(firstdate.ms <= notfirstdate.ms) {
                datecollision = true;
                $(zemessage).text('!!!!!!!! date problem here');
                return false;   // Break the forEach loop
            }
            else {
                $(zemessage).text('!!!!!!!! Will call DB with new date criteria');
            }
    });

        // Call the DB is delay expired
        if(!datecollision) nohurry();          // Call DB with delay if no problem with dates
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
    // ------------------------------------------------------------------------------
    function buildArguments() {  
        // The arguments to be used for the Ajax call
        let allargs = [];
        // First, consider check boxes
        let levelarray = [];
        $('.levelselector').each(function (index, scan) {
            levelarray.push({ "id":$(scan).attr('id'),
                              "state": $(scan).prop('checked')
                            });
        });
        allargs.push( { 'levels': levelarray });
        console.log('----------------------------------------------------------------------');
        // Search text now
        allargs.push({ 'searchtext' : $(searchtext).val() });
        // Date fields now
        alldatefields.forEach(element => {
            let dateid = `${element.selector}`;
            let day = `${$(element.twigday).val()}`;
            let month = `${$(element.twigmonth).val()}`;
            let year = `${$(element.twigyear).val()}`;
            allargs.push(
                { 
                    'dateid' : dateid,
                    'date' : {
                        'day' : day,
                        'month': month,
                        'year': year
                    }
                }  
            );
        });
        // Now refresh data
        console.log(allargs);
        return allargs;
    }
    // ------------------------------------------------------------------------------
    function getLogsNumber() {
        $.ajax({
            type: "GET",
            async: true,
            url: "/bootadmin/logs/getcount",
            success: function (response) {
                $(zemessage).text(`Total of ${response.nblogs} logs in the DB`);
                logsnumber = response.nblogs;
            },
            error: function (xhr) {
                console.log(`KO **********  ${xhr.responseText}` );
            }
        });    
    }
    // ------------------------------------------------------------------------------
    // Delay DB call until user stops typing or selecting checkboxex, list...
    // ------------------------------------------------------------------------------
    function waitplease(func, timeout =  $props.getInputDelay()){
        let timerid;
        return (...args) => {
            clearTimeout(timerid);
            timerid = setTimeout(() => { func.apply(this, args); },  timeout);
        };
    }
})


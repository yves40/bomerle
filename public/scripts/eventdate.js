
// ------------------ Global elements handles            -------------------------
let dateUI = {
    twigday : null,
    twigmonth : null,
    twigyear : null,
    day29: null,
    day30: null,
    day31: null,
}
// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();
    console.log(`[${$props.datehandler()} ]` );
    // In the events TWIG form, IDs are refixed by events
    getDateFields('events');                    // events if the root string for TWIG generated IDs
    updateDateFields('eventdata', 'eventobj');  // eventdata is the ID of the div containing object data
                                                // Look into events.html.twig
    handleMonthSelection();                     // Ensure number of days in the select list is correct
})

// ------------------------------------------------------------------------------
// Receives a base selector string concatenated with the TWIG generated IDs
// Hope these IDs will not change in future versions
// ------------------------------------------------------------------------------
function getDateFields(selector) {
    dateUI.twigday = $(`#${selector}_date_day`);
    dateUI.twigmonth = $(`#${selector}_date_month`);
    dateUI.twigyear = $(`#${selector}_date_year`);
    dateUI.day29 = $(dateUI.twigday).find('option[value="29"]');
    dateUI.day30 = $(dateUI.twigday).find('option[value="30"]');
    dateUI.day31 = $(dateUI.twigday).find('option[value="31"]');
    $(dateUI.twigmonth).change(function (e) {  // Monitor month selection
        e.preventDefault();
        handleMonthSelection();
    });
    $(dateUI.twigyear).change(function (e) {  // Also Monitor year selection in case Feb is selected
        e.preventDefault();
        handleMonthSelection();
    });
}
// ------------------------------------------------------------------------------
// Based on previously identified fields, update the list selection with the 
// event object data
// ------------------------------------------------------------------------------
function updateDateFields(dataselector, objectselector) {

    let eventobj = $(`.${dataselector}`).data(`${objectselector}`);
    let datefields = getDayMonthYear(eventobj.date);

    $(dateUI.twigday).val(datefields.d);
    $(dateUI.twigmonth).val(datefields.m);
    $(dateUI.twigyear).val(datefields.y);
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
function handleMonthSelection() {
    switch (parseInt($(dateUI.twigmonth).val())) {
        case 4:
        case 6:
        case 9:
        case 11:
            console.log('30 days month');
            dateUI.day29.show();
            dateUI.day30.show();
            dateUI.day31.hide();
            break;
        case 2:
            dateUI.day30.hide();
            dateUI.day31.hide();
            if(isLeapYear($(dateUI.twigyear).val())) {
                console.log('29 days month');
                dateUI.day29.show();
            }
            else {
                console.log('28 days month');
                dateUI.day29.hide();
            }
            break;
        default:
            console.log('31 days month');
            dateUI.day29.show();
            dateUI.day30.show();
            dateUI.day31.show();
            break;
    }
}
// ------------------------------------------------------------------------------
function isLeapYear(year) {
    return 0 == year % 4 && (year % 100 != 0 || year % 400 == 0);
}


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
// ------------------------------------------------------------------------------
function selectBirthday(yearSelector, monthSelector, daySelector, config) {
    if (config == undefined) {
        config = {};
    }

    if (config.year == undefined) {
        config.year = new Date().getFullYear();
    }

    if (config.month == undefined) {
        config.day = new Date().getMonth() + 1;
    }

    if (config.month == undefined) {
        config.day = new Date().getDate();
    }

    if (config.yearRange == undefined) {
        config.yearRange = 100;
    }

    if (config.endYear == undefined) {
        config.endYear = new Date().getFullYear();
    }

    var birthdayYear = $(yearSelector);
    if (birthdayYear.length == 0) {
        throw "can not find 'year' select control";
    }
    for (var y = config.endYear; y > config.endYear - config.yearRange; y--) {
        if (y == config.year) {
            $('<option value="' + y + '" selected="selected">' + y + '</option>').appendTo(birthdayYear);
        } else {
            $('<option value="' + y + '" >' + y + '</option>').appendTo(birthdayYear);
        }
    }

    var birthdayMonth = $(monthSelector);
    if (birthdayMonth.length == 0) {
        throw "can not find 'month' select control";
    }
    for (var m = 1; m <= 12; m++) {
        if (m == config.month) {
            $('<option value="' + m + '" selected="selected">' + m + '</option>').appendTo(birthdayMonth);
        } else {
            $('<option value="' + m + '">' + m + '</option>').appendTo(birthdayMonth);
        }
    }

    var birthdayDay = $(daySelector);
    if (birthdayDay.length == 0) {
        throw "can not find 'day' select control";
    }
    for (var d = 1; d <= 31; d++) {
        if (d == config.day) {
            $('<option value="' + d + '" selected="selected">' + d + '</option>').appendTo(birthdayDay);
        } else {
            $('<option value="' + d + '">' + d + '</option>').appendTo(birthdayDay);
        }
    }

    birthdayYear.change(onBirthdayChanged);
    birthdayMonth.change(onBirthdayChanged);
    birthdayDay.change(onBirthdayChanged);

    var day29 = birthdayDay.find('option[value="29"]');
    var day30 = birthdayDay.find('option[value="30"]');
    var day31 = birthdayDay.find('option[value="31"]');

    function onBirthdayChanged() {
        var year = parseInt(birthdayYear.val());
        var month = parseInt(birthdayMonth.val());
        var day = parseInt(birthdayDay.val());

        switch (month) {
            case 4:
            case 6:
            case 9:
            case 11:
                if (day > 30) {
                    birthdayDay.val(30);
                }
                day29.show();
                day30.show();
                day31.hide();
                break;
            case 2:
                if (!isLeapYear(year)) {
                    if (day > 28) {
                        birthdayDay.val(28);
                    }
                    day29.hide();
                } else {
                    if (day > 29) {
                        birthdayDay.val(29);
                    }
                    day29.show();
                }
                day30.hide();
                day31.hide();
                break;
            default:
                day29.show();
                day30.show();
                day31.show();
                break;
        }
    }

    function isLeapYear(year) {
        return 0 == year % 4 && (year % 100 != 0 || year % 400 == 0);
    }
}
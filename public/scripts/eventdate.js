// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();
    console.log(`[${$props.datehandler()} ]` );
    // In the events TWIG form, IDs are refixed by events
    getDateFields('events');
})

// ------------------------------------------------------------------------------
function getDateFields(selector) {
    let twigday = $(`#${selector}_date_day`);
    let twigmonth = $(`#${selector}_date_month`);
    let twigyear = $(`#${selector}_date_year`);
    let theevent = $(".eventdata");
    let eventobj = $(theevent).data('eventobj');
    if(eventobj.name !== null) {
        console.log(`${eventobj.name}`);
        console.log(`${eventobj.date}`);
        let datefields = getDayMonthYear(eventobj.date);
        console.log(datefields);
        $(twigday).val(datefields.d);
        $(twigmonth).val(datefields.m);
        $(twigyear).val(datefields.y);
    }
    else {
        console.log("Clean event");
    }
}
// ------------------------------------------------------------------------------
function getDayMonthYear(thedate) {
    // 2023-02-15T18:59:45+01:00
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
//----------------------------------------------------------------------------
//    datetime.js
//----------------------------------------------------------------------------
export default class DateHelper {
    static Version = 'datetime:1.14, Jan 21 2024';
    static months = [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ];

    getDateTime() {
        let d = new Date();
        return DateHelper.months[d.getMonth()] + '-' + d.getDate() + '-' + d.getFullYear() + ' ' 
                + d.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1") ;
    }
    getDate() {
        let d = new Date();
        return DateHelper.months[d.getMonth()] + '-' + d.getDate() + '-' + d.getFullYear() + ' ';
    }    
    getTime() {
        let d = new Date();
        return d.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1") ;
    }
    getShortTime() {
        return new Date().toTimeString().slice(0,5);
    }
    getHoursMinutes() {
        let d = new Date();
        let time = d.getHours() + ':' + d.getMinutes();
        return time;
    }
    getHoursMinutesSeconds() {
        let d = new Date();
        return d.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");
    }
    convertDateTime(thedate) {
        if (thedate) {
            let computedate = new Date(thedate);
            let day = computedate.getDate();
            let days = '';
            let datetime = null;
            if (day < 10) days = day.toString().replace(/.*(^\d{1}).*/, "0$1");
                else days = day.toString();
                    datetime = DateHelper.months[computedate.getMonth()] + '-' +  days + '-' 
                    + computedate.getFullYear() + ' ' 
                    + computedate.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1"); 
            return datetime;
        }
        else {
            return "Unset"
        }
    }
    convertSecondsToHMS(seconds) {
        let computedate = new Date(1970,0,1);
        computedate.setSeconds(seconds);
        return computedate.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");    
    }
}

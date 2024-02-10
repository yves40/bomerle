/*----------------------------------------------------------------------------
    timeHelper

    Dec 23 2022     Initial
    Dec 27 2022     Add msec 
    Mar 22 2023     Add msec 
    Mar 25 2023     Get months labels

----------------------------------------------------------------------------*/
class timeHelper {

    constructor() {
        this.version = 'timeHelper:1.03, Mar 25 2023';
        this.months = [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ];
        this.monthsfr = [ 'Jan', 'Feb', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aout', 'Sep', 'Oct', 'Nov', 'Dec' ];
        this.start = 0;
        this.end = 0;
    }
    getMonthLabel(monthindex, locale='fr') { 
        if(locale === 'fr') return this.monthsfr[--monthindex]; else return this.months[--monthindex];
    }
    getDateTime() {
        let d = new Date();
        return this.months[d.getMonth()] + '-' + d.getDate() + '-' + d.getFullYear() + ' ' 
                + d.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1") ;
    }
    getDateTimeFromDate(toconvert, locale='fr') {
        let d = new Date(toconvert);
        let dateformat;
        if(locale === 'fr') {
            dateformat = 'dd/mm/yyyy hh:mi:ss';
        }
        else { // en, us, uk, ....
            dateformat = 'mm/dd/yyyy hh:mi:ss';
        }
        let formattedDT = dateformat.replace(/dd/i, String(d.getDate()).padStart(2, '0'));
        formattedDT = formattedDT.replace(/mm/i, String(d.getMonth()+1).padStart(2, '0'));
        formattedDT = formattedDT.replace(/yyyy/i, d.getFullYear());
        formattedDT = formattedDT.replace(/hh:mi:ss/i, 
                            d.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1"));
        return formattedDT;
    }
    getDate() {
        let d = new Date();
        return this.months[d.getMonth()] + '-' + d.getDate() + '-' + d.getFullYear() + ' ';
    }
    getTime() {
        let d = new Date();
        return d.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1") ;
    }
    getTimeMsec() {
        let dd = new Date().toISOString(); 
        let d = dd.match(/T.*\d{0,2}:\d{0,2}.\d{0,3}/); // T is time delimiter in ISO format
        return d[0].substring(1);
    }
    getShortTime() {
        return new Date().toTimeString().slice(0,5);
    }
    startTimer() {
        this.start = new Date();
    }
    stopTimer() {
        this.end = new Date();
    }
    getElapsed() {
        if(this.end > this.start)
            return this.end - this.start;
        else return 0;
    }
    getElapsedString() {
        if(this.end <= this.start)
            return 0;
        let elapsed = this.end - this.start;
        if(elapsed >= 1000) // More than 1 sec ? 
        {
            return (this.end - this.start)/1000 + ' sec';
        }
        else {
            return this.end - this.start + ' msec';
        }
    }
}
export default timeHelper;

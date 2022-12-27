/*----------------------------------------------------------------------------
timeHelper

    Dec 23 2022     Initial
    Dec 27 2022     Add msec 

----------------------------------------------------------------------------*/
class timeHelper {

    constructor() {
        this.version = 'timeHelper:1.00, Dec 23 2022';
        this.months = [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ];
        this.start = 0;
        this.end = 0;
    }
    getDateTime() {
        let d = new Date();
        return this.months[d.getMonth()] + '-' + d.getDate() + '-' + d.getFullYear() + ' ' 
                + d.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1") ;
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
        let d = dd.match(/T.*\d{0,2}:\d{0,2}.\d{0,3}/);
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
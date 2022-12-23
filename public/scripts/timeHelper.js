/*
    timeHelper

    Dec 23 2022     Initial

*/
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
        return this.end - this.start;
    }
}
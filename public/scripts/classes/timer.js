
export default class Timer {

    constructor() {
        this.version = 'Timer:1.00, Jan 21 2024';
        this.start = 0;
        this.end = 0;
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

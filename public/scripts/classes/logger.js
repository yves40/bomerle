/* eslint-disable no-unused-vars */
//----------------------------------------------------------------------------
//    logger.js
//----------------------------------------------------------------------------
import DateHelper from './datetime.js';

export default class Logger {

    // logger levels
    static DEBUG = 0;
    static INFORMATIONAL = 1;
    static WARNING = 2;
    static ERROR = 3;
    static FATAL = 4;
    static Version = 'logger:1.52, Oct 10 2024';
    static OUTFILE = '/tmp/' + this.Version.replace(/[,:]/g,'_').replace(/ /g, '_') + '.log'

    /**
     * 
     * @param {*} runmode 'dev' or 'prod'. In prod, no log output produced
     */
    constructor(runmode = 'dev') {
        this.runmode = runmode;
        this.datetime = new DateHelper();
        if(runmode === 'dev') {
            this.loggerlevel = 0;
        }
        else {  // In production, only trace INFORMATIONAL, WARNING, ERROR and FATAL
            this.loggerlevel = 1;
        }
    }
    /**
     * 
     * @param {*} mess The message
     * @returns 
     */
    debug(mess) {
        this.log(mess, Logger.DEBUG);
        return;
    }
    info(mess) {
        this.log(mess, Logger.INFORMATIONAL);
        return;
    }
    warning(mess) {
        this.log(mess, Logger.WARNING);
        return;
    }
    error(mess) {
        this.log(mess, Logger.ERROR);
        return;
    }
    fatal(mess) {
        this.log(mess, Logger.FATAL);
        return;
    }
    /**
     * @param {*} level Convert numeric level to a DIWEF string
     * @returns The corresponding string
     */
    levelToString(level = LOGGERLEVEL) {
        switch (level) {
            case Logger.DEBUG: return 'DBG';
            case Logger.INFORMATIONAL: return 'INF';
            case Logger.WARNING: return 'WRN';
            case Logger.ERROR: return 'ERR';
            case Logger.FATAL: return 'FTL';
            default: return 'FTL';
        }
    }
    /**
     * @param {*} mess The message 
     * @param {*} level The message level in the DIWEF specification
     * @returns 
     */
    log(mess, level) {
        if (level >= this.loggerlevel) {
            // Get the caller
            let stack;
            let  caller = '';
            try {throw new Error('');}
            catch (error) {stack = error.stack || '';}    
            stack = stack.split('\n').map(function (line) { return line.trim(); });
            if (stack.length >= 4) {
                if(stack[0] === 'Error') {  // Is it a chrome browser ?
                    caller = stack[3];
                }
                else {                      // No, Error entry does not exists
                    caller = stack[2];
                }
                // Manage some browser specific behavior
                // Chrome adds parenthesis () around the log message, instead of Edge and Firefox
                if(caller.indexOf('(') != -1) {
                    caller = caller.substring(caller.indexOf('(') + 1, caller.indexOf(')'));
                }
                // let pointparser = caller.split(':');
                let slashparser = caller.split('/');
                caller = `${slashparser[slashparser.length-1]}`;
            }
            // Output message
            let logstring = `${this.datetime.getDateTime()} [ ${this.levelToString(level)} ] [${caller}] ${mess} `;
            console.log(logstring);
            return;
        }
    }
}

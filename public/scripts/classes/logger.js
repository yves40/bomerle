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
    static Version = 'logger:1.51, Jan 22 2024';
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
            let logstring = this.datetime.getDateTime()
                    + ' [' + this.levelToString(level) + '] '
                    + ' ' + mess ;
            console.log(logstring);
            return;
        }
    }
}

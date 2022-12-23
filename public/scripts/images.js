// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    console.log('Images handler');
    let th = new timeHelper();
    console.log('getDateTime : ' + th.getDateTime());
    console.log('getDate : ' + th.getDate());
    console.log('getTime : ' + th.getTime());
    console.log('getShortTime : ' + th.getShortTime());
    let sessiondelay  = sessionStorage.getItem("sessiondelay");
    if(sessiondelay === null) { 
        sessiondelay = 2000;      // Default delay to be used between images load
        sessionStorage.setItem('sessiondelay', sessiondelay);
        sessionStorage.setItem('checkdelay', true);
        console.log('Session delay has been set to ' + sessiondelay + ' msec');
    }
    else {
        console.log('Session previously set to ' + sessiondelay + ' msec');
    }
    $("img").each(function (indexInArray, element) { 
        let url = element.src;
        let elemwidth = element.width;
        let elemheight = element.height;
        element.src = "/images/gif/loading.gif";
        $(element).height("20px").width("20px").attr( { 'object-fit': 'cover', 'object-position': 'center'} );
        $(element).on("load", () => {     // Here we track the end of the wait gif load
            timeoutid = setTimeout( () => {
                console.log('Loading ' + url);
                $(element).off("load");     // Removes event handlers that were attached with .on()
                element.src = url;          // Now load the desired image
                startTime = new Date();
                let timer = new timeHelper();
                timer.startTimer();
                $(element).on("load", () => {
                        $(element).height(elemheight).width(elemwidth);
                        endTime = new Date();
                        timer.stopTimer();
                        console.log('************* ' + timer.getElapsed());
                        var timeDiff = endTime - startTime; //in ms
                        console.log('Loaded ' + url + ' in ' + timeDiff + " msec");
                        if(sessionStorage.getItem('checkdelay')) {
                            sessionStorage.setItem('checkdelay', false);
                            sessionStorage.setItem('sessiondelay', timeDiff * 1.1); // New timeout
                        }
                })
            }, sessiondelay * indexInArray, url);       // Will load the image in a few time. Image url passed
            console.log(url + ' will start in ' + ((sessiondelay/1000) * indexInArray) + ' msec');
        })
        $(element).on("abort", () => {
            console.log('Abort...');
        })
        $(element).on("error", () => {
            console.log('Error...');
        })
    });
})
 
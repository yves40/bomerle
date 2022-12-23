// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    console.log('Images handler');
    let sessiondelay  = sessionStorage.getItem("sessiondelay");
    if(sessiondelay === null) { 
        sessiondelay = 1000;      // Default delay to be used between images load
        sessionStorage.setItem('sessiondelay', sessiondelay);
        sessionStorage.setItem('checkdelay', true);
        console.log('Session delay has been set to ' + sessiondelay + ' msec');
    }
    else {
        console.log('Session previously set to ' + sessiondelay + ' msec');
    }
    $("img").each(function (indexInArray, element) { 
        let url = element.src;
        let filename = url.replace(/^.*[\\\/]/, '')
        let elemwidth = element.width;
        let elemheight = element.height;
        element.src = "/images/gif/loading.gif";
        $(element).height("20px").width("20px").attr( { 'object-fit': 'cover', 'object-position': 'center'} );
        $(element).on("load", () => {     // Here we track the end of the wait gif load
            timeoutid = setTimeout( () => {
                $(element).off("load");     // Removes event handlers that were attached with .on()
                element.src = url;          // Now load the desired image
                let timer = new timeHelper();
                timer.startTimer();
                $(element).on("load", () => {
                        $(element).height(elemheight).width(elemwidth);
                        timer.stopTimer();
                        console.log('Image Index: ' + indexInArray + 
                            ' Loaded ' + filename + ' in ' + timer.getElapsedString());
                        if(sessionStorage.getItem('checkdelay')) {
                            sessionStorage.setItem('checkdelay', false);
                            sessionStorage.setItem('sessiondelay', timer.getElapsed() * 1.1); // New timeout
                        }
                })
            }, sessiondelay * indexInArray, url, filename, indexInArray);       // Will load the image in a few time. Image url passed
        })
        $(element).on("abort", () => {
            console.log('Abort...');
        })
        $(element).on("error", () => {
            console.log('Error...');
        })
    });
})
 
// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    console.log('Images handler');
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
    // $(".mainpicture > img").each(function (indexInArray, element) { 
    $("img").each(function (indexInArray, element) { 
        let url = element.src;
        let elemwidth = element.width;
        let elemheight = element.height;
        element.src = "/images/gif/loading.gif";
        $(element).height("20px").width("20px").attr( { 'object-fit': 'cover', 'object-position': 'center'} );
        $(element).on("load", () => {
            timeoutid = setTimeout( () => {
                console.log('Loading ' + url);
                $(element).unbind("load");
                element.src = url;
                startTime = new Date();
                $(element).on("load", () => {
                        $(element).height(elemheight).width(elemwidth);
                        endTime = new Date();
                        var timeDiff = endTime - startTime; //in ms
                        console.log('Loaded ' + url + ' in ' + timeDiff + " msec");
                        if(sessionStorage.getItem('checkdelay')) {
                            sessionStorage.setItem('checkdelay', false);
                            sessionStorage.setItem('sessiondelay', timeDiff * 1.1); // New timeout
                        }
                })
            }, sessiondelay * indexInArray);
            console.log('Will start in ' + ((sessiondelay/1000) * indexInArray) + ' seconds');
        })
        $(element).on("abort", () => {
            console.log('Abort...');
        })
        $(element).on("error", () => {
            console.log('Error...');
        })
    });
})
 
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
        // -------------------------
        // Load document images
        // -------------------------
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
                    getImage(element, url)
                        .then( (timer) => {
                            $(element).height(elemheight).width(elemwidth);
                            let elapsed = timer.getElapsedString();
                            console.log(`Image Index : ${indexInArray} loaded ${filename} in ${elapsed}`)
                        })
                        .catch( (message) => {
                            console.log(`Error : ${message}`)
                        })

                }, sessiondelay * indexInArray, url, filename, indexInArray);       // Will load the image in a few time. Image url passed
            })
        });
    }
)

function getImage(element, url) {
    return new Promise((resolve, reject) => {
            let timer = new timeHelper();
            timer.startTimer();
            element.src = url;          // Load the desired image
            $(element).on("load", () => {
                    timer.stopTimer();
                    resolve(timer);
                    // if(sessionStorage.getItem('checkdelay')) {
                    //     sessionStorage.setItem('checkdelay', false);
                    //     sessionStorage.setItem('sessiondelay', timer.getElapsed() * 1.1); // New timeout
                    // }
            })    
            $(element).on("abort", () => {
                reject('Abort...');
            })
            $(element).on("error", () => {
                reject('Error...');
            })
    });
}
 
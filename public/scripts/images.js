// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    console.log(getStaticProperty('version'));
    console.log('Images loader');
    let sessiondelay  = sessionStorage.getItem("sessiondelay");
        if(sessiondelay === null) { 
            sessiondelay = get('imageloadingdelay');      // Default delay to be used between images load
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
        let nbimages = 0;
        $("img").each(function (indexInArray, element) { 
            ++ nbimages;
            let url = element.src;
            let filename = url.replace(/^.*[\\\/]/, '')
            let elemwidth = element.width;
            let elemheight = element.height;
            element.src = "/images/gif/loading.gif";
            $(element).height("20px").width("20px").attr( { 'object-fit': 'cover', 'object-position': 'center'} );
            $(element).on("load", () => {     // Here we track the end of the wait gif load
                // Will load images with a delay
                timeoutid = setTimeout( () => {
                    $(element).off("load");     // Removes event handlers that were attached with .on()
                    getImage(element, url)      // Load the real image
                        .then( (timer) => {
                            $(element).height(elemheight).width(elemwidth);
                            let elapsed = timer.getElapsedString();
                            let timestamp = timer.getTime();
                            console.log(`${timestamp} Image Index : ${indexInArray} loaded ${filename} in ${elapsed}`)
                        })
                        .catch( (message) => {
                            console.log(`Error : ${message}`)
                        })

                }, 
                sessiondelay * indexInArray, url, filename, indexInArray);
            })
        });
        console.log(`Loading ${nbimages} images`);
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
            })    
            $(element).on("abort", () => {
                reject('Abort...');
            })
            $(element).on("error", () => {
                reject('Error...');
            })
    });
}

// if(sessionStorage.getItem('checkdelay')) {
//     sessionStorage.setItem('checkdelay', false);
//     sessionStorage.setItem('sessiondelay', timer.getElapsed() * 1.1); // New timeout
// }

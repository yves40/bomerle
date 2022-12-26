// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();
    console.log('Application version : ' + $props.version());
    const handlerversion = $props.imagehandler();
    let imagedelay  = $props.imageloadingdelay();
    console.log(`${handlerversion} : Delay between images currently set to ${imagedelay} msec`);
    console.log(`You loaded ${$props.imageloadcount()} images in this session`);
    // -------------------------
    // Load document images
    // -------------------------
    let nbimages = 0;
    $("img").each(function (indexInArray, element) { 
        ++nbimages;
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
                        // Ajust timeout delay for the next image loop
                        $props.set('imageloadingdelay', ($props.imageloadingdelay() + timer.getElapsed()) / 2);
                        $props.set('imageloadcount', $props.imageloadcount() + 1);
                        $props.save();
                        let timestamp = timer.getTime();
                        console.log(`${timestamp} Image Index : ${indexInArray} loaded ${filename} in ${elapsed}`)
                    })
                    .catch( (message) => {
                        console.log(`Error : ${message}`)
                    })

            }, 
            imagedelay * indexInArray, url, filename, indexInArray);
        })
    });
    console.log(`Loading ${nbimages} images`);
})

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

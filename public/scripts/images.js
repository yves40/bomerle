// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();
    console.log(`[${$props.version()} ]` );
    const handlerversion = $props.imagehandler();
    let totalimagesloaded = parseInt($props.imageloadcount());
    let avgloadtime  = parseInt($props.imageavgloadtime());
    console.log(`[${handlerversion}] : You loaded ${$props.imageloadcount()} images in this session`);
    console.log(`[${handlerversion}] : Average images load time since session started: ${avgloadtime} msec`);
    // -------------------------
    // Load document images
    // -------------------------
    let nbimages = 0;
    $("img").each(function (indexInArray, element) { 
        let url = element.src;
        let filename = url.replace(/^.*[\\\/]/, '');
        let elemwidth = element.width;
        let elemheight = element.height;
        element.src = "/images/gif/loading.gif";
        $(element).height("20px").width("20px").attr( { 'object-fit': 'cover', 'object-position': 'center'} );
        $(element).on("load", () => {    // End of the wait gif load
            $(element).off("load");      // Removes event handlers that were attached with .on()
            element.src = url;
            let timer = new timeHelper();
            timer.startTimer();
            $(element).on("load", (theurl) => {
                $(element).src = theurl;
                timer.stopTimer();  // End of the requested image load
                $(element).off("load");
                $(element).height(elemheight).width(elemwidth);
                let elapsed = timer.getElapsedString();
                avgloadtime = (avgloadtime + timer.getElapsed()) / 2;
                ++totalimagesloaded;
                let timestamp = timer.getTimeMsec();
                console.log(`${timestamp} Image Index : ${indexInArray} loaded ${filename} in ${elapsed}`)
                $props.set('imageavgloadtime', avgloadtime);
                $props.set('imageloadcount', totalimagesloaded);
                $props.save();
            })    
            $(element).on("abort", () => {
                console.log(`Upload Abort for : ${element.src}`)
            })
            $(element).on("error", () => {
                console.log(`Upload Error for : ${element.src}`)
            })
        })
    });
    // console.log(`Loaded ${nbimages} images`);
})

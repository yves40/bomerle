// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();
    console.log(`[${$props.version()} ]` );
    const handlerversion = $props.imagehandler();
    let imagedelay  = $props.imageloadingdelay().toFixed(2);
    console.log(`[${handlerversion}] : Delay between images currently set to ${imagedelay} msec`);
    console.log(`You loaded ${$props.imageloadcount()} images in this session`);
    // -------------------------
    // Load document images
    // -------------------------
    let nbimages = 0;
    $("img").each(function (indexInArray, element) { 
        ++nbimages;
        let url = element.src;
        let filename = url.replace(/^.*[\\\/]/, '');
        let elemwidth = element.width;
        let elemheight = element.height;
        element.src = "/images/gif/loading.gif";
        $(element).height("20px").width("20px").attr( { 'object-fit': 'cover', 'object-position': 'center'} );
        $(element).on("load", () => {     // Here we track the end of the wait gif load
            // Will load images with a delay
            timeoutid = setTimeout( () => {
                $(element).off("load");     // Removes event handlers that were attached with .on()
                element.src = url;
                let timer = new timeHelper();
                timer.startTimer();
                $(element).on("load", () => {
                    timer.stopTimer();
                    $(element).height(elemheight).width(elemwidth);
                    let elapsed = timer.getElapsedString();
                    // Ajust timeout delay for the next image loop
                    $props.set('imageloadingdelay', ($props.imageloadingdelay() + timer.getElapsed()) / 2);
                    $props.set('imageloadcount', $props.imageloadcount() + 1);
                    $props.save();
                    // let timestamp = timer.getTime();
                    let timestamp = timer.getTimeMsec();
                    console.log(`${timestamp} Image Index : ${indexInArray} loaded ${filename} in ${elapsed}`)
                })    
                $(element).on("abort", () => {
                    console.log(`Error : ${message}`)
                })
                $(element).on("error", () => {
                    console.log(`Error : ${message}`)
                })
            }, 
            imagedelay * indexInArray, 
            url, filename, indexInArray, elemwidth, elemheight);
        })
    });
    console.log(`Loading ${nbimages} images`);
})

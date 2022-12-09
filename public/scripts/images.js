// ------------------ Init loop to trap all mouse clicks -------------------------
const delay = 2000; // Milliseconds
$(document).ready(function () {
    console.log('Images handler');
    // $(".mainpicture > img").each(function (indexInArray, element) { 
    $("img").each(function (indexInArray, element) { 
        let url = element.src;
        let elemwidth = element.width;
        let elemheight = element.height;
        element.src = "/images/gif/loading.gif";
        $(element).height("20px").width("20px").attr( { 'object-fit': 'cover', 'object-position': 'center'} );
        $(element).on("load", () => {
            // Simulate a (very) slow network
            timeoutid = setTimeout( () => {
                console.log('Loading ' + url);
                $(element).unbind("load");
                element.src = url;
                $(element).height(elemheight).width(elemwidth);
            }, delay * indexInArray);
            console.log('Will start in ' + (2 * indexInArray) + ' seconds');
        })
        $(element).on("abort", () => {
            console.log('Abort...');
        })
        $(element).on("error", () => {
            console.log('Error...');
        })
    });
})

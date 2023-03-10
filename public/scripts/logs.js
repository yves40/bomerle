// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();
    let zoomstate = false;
    const zoom = $(".msgzoom");
    const messagezone = $(".msgdetails");

    $(messagezone).hide();
    zoom.click(function (e) { 
        e.preventDefault();
        zoomMessage(this);
    });;

    // ----------------------------------------------------------------------------
    // Zoom in, zoom out for log message details
    // ----------------------------------------------------------------------------
    function zoomMessage(element) {
        let msgzone = $(element).siblings('.msgdetails');
        let currentstate = $(msgzone).data('visible');
        console.log(`Currentstate : ${currentstate}`);
        if(currentstate) {
            $(msgzone).data('visible', false);
            $(msgzone).hide();
        }
        else {
            $(msgzone).data('visible', true);
            $(msgzone).show();
        }     
    }
})


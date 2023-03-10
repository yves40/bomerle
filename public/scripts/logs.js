// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();
    let zoomstate = false;
    const zoom = $(".msgzoom");
    const showall = $('#showall');
    const hideall = $('#hideall');
    const messagezone = $(".msgdetails");

    $(messagezone).hide();
    zoom.click(function (e) { 
        e.preventDefault();
        zoomMessage(this);
    });
    showall.click( (e) => { e.preventDefault(); showAll()});
    hideall.click( (e) => { e.preventDefault(); hideAll()});

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
    // ----------------------------------------------------------------------------
    // Show all messages details
    // ----------------------------------------------------------------------------
    function showAll() {
        $('.msgdetails').each(function (index, element) {
            // element == this
            $(this).data('visible', true);
            $(this).show();            
        });
    }
    // ----------------------------------------------------------------------------
    // Hide all messages details
    // ----------------------------------------------------------------------------
    function hideAll() {
        $('.msgdetails').each(function (index, element) {
            // element == this
            $(this).data('visible', false);
            $(this).hide();            
        });
    }
})


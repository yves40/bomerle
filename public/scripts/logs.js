// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();

    const zoom = $(".msgzoom");
    const showall = $('#showall');
    const hideall = $('#hideall');
    const messagezone = $(".msgdetails");

    hideAll();

    $('.levelselector').each(function (index, element) {
        // element == this
        $(this).click( (e) => { levelSelected(this); });
    });

    zoom.click(function (e) { e.preventDefault(); zoomMessage(this); });
    showall.click( (e) => { e.preventDefault(); showAll(); });
    hideall.click( (e) => { e.preventDefault(); hideAll(); });

    // ----------------------------------------------------------------------------
    // Level selection
    // ----------------------------------------------------------------------------
    function levelSelected(element) {
        let id = $(element).attr('id');
        if($(element).prop('checked')) {
            $(element).remove('checked');
        }
        // Now build the selector array
        let selectors = [];
        $('.levelselector').each(function (index, scan) {
            if($(scan).prop('checked')) {
                console.log(` ID : ${$(scan).attr('id')} ON`);
            }
            else{
                console.log(` ID : ${$(scan).attr('id')} OFF`);
            }
        });
    }
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

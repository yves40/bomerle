// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();
    let zoomstate = false;
    const zoom = $(".msgzoom");
    const messagezone = $(".msgdetails");

    $(messagezone).hide();
    zoom.click(function (e) { 
        e.preventDefault();
        if(zoomstate) {
            zoomstate = false;
            $(messagezone).hide();
        }
        else {
            zoomstate = true;
            $(messagezone).show();
        }
        console.log(`Message details ${zoomstate}`);
    });;

    $(window).resize ( () =>  {
        let hsize = $(window).width();
        console.log(`Resized new width : ${hsize}`);
    });
})
// console.log(`[ ${$props.sliderhandler()}  ]` );

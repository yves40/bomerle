/*
    Removes the menu entry corresponding to the active page
*/
$(document).ready(function () {
    let thepath = $('.menudata').data('pathinfo');
    $('.dynmenu li.nav-item a').each(function (index, element) {
        let sticky = $(element).hasClass('sticky');
        if(!sticky) {
            let path = $(element).attr('href');
            if(path.includes('?')) {
                path = path.split('?')[0];
            }
            if(thepath.includes(path)){
                $(this).parent().hide();
            }
        }
    });
})

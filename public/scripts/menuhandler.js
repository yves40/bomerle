/*
    Removes the menu entry corresponding to the active page
*/
// $(document).ready(function () {
//     let thepath = $('#adminpath p').text();
//     console.log(thepath);
//     $('li.nav-item a').each(function (index, element) {
//         let path = $(element).attr('href');
//         if(path.includes('?')) {
//             path = path.split('?')[0];
//         }
//         if(thepath.includes(path)){
//             $(this).parent().hide();
//         }
//     });
// })
$(document).ready(function () {
    let thepath = $('.menudata').data('pathinfo');
    console.log(thepath);
    $('li.nav-item a').each(function (index, element) {
        let path = $(element).attr('href');
        if(path.includes('?')) {
            path = path.split('?')[0];
        }
        if(thepath.includes(path)){
            $(this).parent().hide();
        }
    });
})

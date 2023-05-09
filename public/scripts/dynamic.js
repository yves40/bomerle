//  -------------------------
//  dynamic.js
//  -------------------------
$(document).ready(function () {
    $props.load();
    console.log(`[${$props.version()} ]` );
    findDiapoSections();
    // ---------------------------------------- Find a dynamic section in the page
    function findDiapoSections() {
        $('.diapo').each( function (index, element) {
            let name = $(this).attr('name');
            console.log(`Diapo element : ${name}`)
            const payload = {
                "diaponame" :  name,
            }
            $.ajax({
                type: "POST",
                url: '/bootadmin/slides/getdiapos',
                data: JSON.stringify(payload),
                dataType: "json",
                async: false,
                success: function (response) {
                    console.log(response);
                },
                error: function (xhr) {
                    console.log(xhr);
                }
            });    
                })
    }

})

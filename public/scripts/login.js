// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    $props.load();
    let useremail = $props.getuseremail();
    if(useremail !== null) {
        $('#inputEmail').val(useremail);
    }
    $('#inputEmail').change(function (e) { 
        e.preventDefault();
        useremail = $('#inputEmail').val();
        $props.saveuseremail(useremail);
    });
})

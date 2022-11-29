// ------------------ Init loop to trap all mouse clicks -------------------------
$(document).ready(function () {
    console.log('Initializing buttons handlers');
    $(".add-button").click( function (event) { 
        event.preventDefault();
        actionRequest(this) ;
    });
    $("#newsletter_email").on('keyup', function (event){
        // var validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        // var validRegex = /^[a-zA-Z0-9][\-_\.\+\!\#\$\%\&\'\*\/\=\?\^\`\{\|]{0,1}([a-zA-Z0-9][\-_\.\+\!\#\$\%\&\'\*\/\=\?\^\`\{\|]{0,1})*[a-zA-Z0-9]@[a-zA-Z0-9][-\.]{0,1}([a-zA-Z][-\.]{0,1})*[a-zA-Z0-9]\.[a-zA-Z0-9]{0,1}([\.\-]{0,1}[a-zA-Z]){0,1}[a-zA-Z0-9]{0,1}$/
        // var validRegex =  /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
        const validRegex = new RegExp(/^[A-Za-z0-9_!#$%&'*+\/=?`{|}~^.-]+@[A-Za-z0-9.-]+$/, "gm");
        // const validEmail = ($("#newsletter_email").val()).match(validRegex);
        const validEmail = validRegex.test($("#newsletter_email").val());
        console.log(validEmail);
        if(validEmail){
            $(".add-button").addClass('active');
            $(".add-button").removeClass('disabled');
        }else{
            $(".add-button").addClass('disabled');
            $(".add-button").removeClass('active');
        }        
    })
})

// ------------------ Handler -------------------------
function actionRequest(element) {
    
    let email = $("#newsletter_email").val();
    let knife = $("#newsletter_forknife").prop('checked');
    let events = $("#newsletter_forevents").prop('checked');

    if((email.length !== 0) && ((knife) || (events))){
        let url = $(element).attr('href');
        console.log('Subcribe ' +  url);
        
        
        let data = {
            "email" : email,
            "knifes" :knife,
            "events" : events
        };

        $.ajax(
            {
                type: "POST",
                url: url,
                datatype: "json",
                data: data,
                async: false,
                success: function (response) {
                    console.log(response);
                },
                error: function (xhr, status, error) {
                    console.log(status + ' Something went wrong during ' + email + ' registration');
                }
            }
        )        
    }
    
}

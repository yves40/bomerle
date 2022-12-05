// ------------------ Init loop to trap all mouse clicks -------------------------
let validEmail = false;
let knifeChecked = false;
let eventsChecked = false;
$(document).ready(function () {
    console.log('Initializing buttons handlers');
    $(".subscribe").click( function (event) { 
        event.preventDefault();
        actionRequest(this) ;
    });
    $("#newsletter_email").on('keyup', function (event){
        const maregex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        
        validEmail = maregex.test($("#newsletter_email").val());

        console.log(validEmail);
        buttonActivation();
    })
    $("#newsletter_forknife").change( function () {
        if(this.checked){
            knifeChecked = true;
        }else{
            knifeChecked = false;
        }
        buttonActivation();
    })
    $("#newsletter_forevents").change( function () {
        if(this.checked){
            eventsChecked = true;
        }else{
            eventsChecked = false;
        }
        buttonActivation();
    })
})

function buttonActivation() {
    if(validEmail && (knifeChecked || eventsChecked)){
        $(".subscribe").addClass('active');
        $(".subscribe").removeClass('disabled');
    }else{
        $(".subscribe").addClass('disabled');
        $(".subscribe").removeClass('active');
    }  
}

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
                    $("#newsletter_email").val("");
                    $("#newsletter_forknife").prop('checked', false);
                    $("#newsletter_forevents").prop('checked', false);
                    $(".subscribe").addClass('disabled');
                    $(".subscribe").removeClass('active');
                    $(".subscribe-success").text(response.message)
                    validEmail = false;
                    knifeChecked = false;
                    eventsChecked = false;
                },
                error: function (xhr, status, error) {
                    console.log(status + ' Something went wrong during ' + email + ' registration');
                }
            }
        )        
    }
    
}

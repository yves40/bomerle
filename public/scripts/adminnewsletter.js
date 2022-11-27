$(document).ready(function () {
    $(".add-button").click( function (event) { 
        event.preventDefault();
        actionRequest(this);
    })
    $('#knife-choice').addClass('hidden');
    $('#event-choice').addClass('hidden');
    $('#newsletter-choice').change( function () {
        console.log("choix : "+$('#newsletter-choice').val())
        switch($('#newsletter-choice').val()){
            case 'knife':
                $('#knife-choice').addClass('visible').removeClass('hidden');
                $('#event-choice').addClass('hidden').removeClass('visible');
                break;
            case 'event':
                $('#event-choice').addClass('visible').removeClass('hidden');
                $('#knife-choice').addClass('hidden').removeClass('visible');
                break;
            default :
                $('#knife-choice').addClass('hidden').removeClass('visible');
                $('#event-choice').addClass('hidden').removeClass('visible');
        }
    })
})

function actionRequest(element) {
    const eventType = $("#newsletter-choice").val();
    let url = $(element).attr('href')+'/'+eventType+'/';
    if(eventType === 'knife'){
        const knife = $("#knife-choice").val();
        url = url+knife;
    }else{
        const event = $("#event-choice").val();
        url = url+event;
    }
    console.log(url);
    $.ajax(
        {
            type: "POST",
            url: url,
            datatype: "json",
            async: false,
            success: function (response) {
                console.log(response);
            },
            error: function (xhr, status, error) {
                // var jsonResponse = JSON.parse(xhr.responseText);
                console.log(status);
            }
        }
    ) 
}


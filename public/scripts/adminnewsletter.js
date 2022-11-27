$(document).ready(function () {
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
    $(".add-button").click( function(event) {
        event.preventDefault();
        console.log('clic');
    })
})


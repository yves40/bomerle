$(document).ready(function () {
    $('.knife').addClass('hidden');
    $('.knife').removeClass('visible');
    $('.object').change( function() {
        $('select option:selected').each( function(index, element) {
            if(index == 0){
                if($(this).val() == 'infoknife' ){
                    $('.knife').removeClass('hidden');
                    $('.knife').addClass('visible');
                }else{
                    $('.knife').addClass('hidden');
                    $('.knife').removeClass('visible');
                }
            }
        })
    })
})
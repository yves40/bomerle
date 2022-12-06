// ------------------ Init loop to trap all mouse clicks -------------------------
let validEmail = false;
let validFirstname = false;
let validLastname = false;
let validAddress = false;
let validPassword = false;

$(document).ready(function () {
    console.log('Initializing register form');
    $(".login-button").addClass('disabled').removeClass('active');
    // $(".login-button").click( function (event) { 
            
    // });
    $("#users_email").on('keyup', function (event){
        const maregex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        
        validEmail = maregex.test($("#users_email").val());
        if(validEmail){
            $('.email').children('.message').text('Email valide').show();
        }else{
            $('.email').children('.message').text('Email invalide').show();
        }
        buttonActivation();
    })
    $("#users_firstname").change( function () {
        let firstName = ($("#users_firstname").val());
        if(firstName.length === 0){
            validFirstname = false;
            $('.firstname').children('.message').text('Prénom invalide').show();
        }else{
            validFirstname = true;
            $('.firstname').children('.message').text('Prénom valide').show();
        }
        buttonActivation();
    })
    $("#users_lastname").change( function () {
        let lastName = ($("#users_lastname").val());
        if(lastName.length === 0){
            validLastname = false;
            $('.lastname').children('.message').text('Nom invalide').show();
        }else{
            validLastname = true;
            $('.lastname').children('.message').text('Nom valide').show();
        }
        buttonActivation();
    })
    $("#users_address").change( function () {
        let address = ($("#users_address").val());
        if(address.length === 0){
            validAddress = false;
            $('.address').children('.message').text('Adresse invalide').show();
        }else{
            validAddress = true;
            $('.address').children('.message').text('Adresse valide').show();
        }
        buttonActivation();
    })
    $("#users_password").change( function () {
        checkPasswords();
        buttonActivation();
    })
    $("#users_confirmpassword").on('keyup', function () {
        checkPasswords();
        buttonActivation();
    })
})

function checkPasswords() {
    let password = ($("#users_password").val());
    let passwordChecked = $("#users_confirmpassword").val();
    if(password.length > 3 && password.length < 21 && (password === passwordChecked) ){
        validPassword = true;
        $('.password').children('.message').text('Mot de passe valide').show();
    }else{
        validPassword = false;
        $('.password').children('.message').text('Mot de passe invalide').show();
    }
}

function buttonActivation() {
    if(validEmail && validFirstname && validAddress && validLastname){
        $(".login-button").addClass('active').removeClass('disabled');
    }else{
        $(".login-button").addClass('disabled').removeClass('active');
    }  
}

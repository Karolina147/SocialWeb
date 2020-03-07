$(document).ready(function () {

    // schawanie menu logowania i rozwiniecię menu rejestracji po kliknięciu 
    $("#signup").click(function () {
        $("#first").slideUp("slow", function () {
            $("#second").slideDown("slow");
        })
    })

    // pokazanie menu logowania i schowanie menu rejestracji
    $("#signin").click(function () {
        $("#second").slideUp("slow", function () {
            $("#first").slideDown("slow");
        })
    })

});

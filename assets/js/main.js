/*
	Main js for the template system




*/
$( document ).ready(function() {
     //start with menu open
    $("#wrapper").toggleClass("toggled");

    //toggle class open and close the menu
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });



});
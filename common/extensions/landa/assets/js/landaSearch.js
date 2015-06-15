// Safely inject CSS3 and give the search results a shadow
//var cssObj = {'box-shadow': '#888 5px 10px 10px', // Added when CSS3 is standard
//    '-webkit-box-shadow': '#888 5px 10px 10px', // Safari
//    '-moz-box-shadow': '#888 5px 10px 10px'}; // Firefox 3.5+
//$("#suggestions").css(cssObj);

// Fade out the suggestions box when not active
$("#inputString").blur(function() {
    $('#suggestions').fadeOut();
});


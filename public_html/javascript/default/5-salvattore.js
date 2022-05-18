function add_salvattore() {
    console.log('Salvattore is on.');
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "/javascript/salvattore.min.js";
    document.querySelector("body").appendChild(script);
}

var salvattore = 0;
if ($(window).width() > 972) {
    salvattore = 1;
    add_salvattore();
} else {
    //$('head').append('<style>#grid[data-columns]::before{content:none}</style>');
}

$(window).resize(function() {
    if (!salvattore && $(window).width() > 972) {
        salvattore = 1;
        $('head style').remove();
        add_salvattore();
    }
});
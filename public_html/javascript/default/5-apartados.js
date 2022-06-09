$(function() {
    var hash = location.hash;
    console.log(hash);
    $('[href="' + hash + '"').click();
});
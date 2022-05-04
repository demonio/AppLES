$('body').on('change', '.dropimage [type="file"]', function(eve) {

    var img = $(this).parent().children('img');
    var parent = $(this).parent();
    var reader = new FileReader();

    reader.onloadend = function() {
        if (img.length) {
            $(img).remove();
        }
        $(parent).css('background-image', 'url(' + reader.result + ')');
        $(parent).css('background-repeat', 'no-repeat');
        $(parent).css('background-size', 'cover');

    }

    reader.readAsDataURL(eve.target.files[0]);

    if ($(parent).hasClass('multiple')) {
        console.log($('.dropimagehover').lenght);
        var other = $(parent).removeClass('dropimagehover').clone().appendTo($(parent).parent());
        $(other).find('[type="file"]').val('');
    }

    $(parent).addClass('dropnocontent');
});

$('body').on('click', '.dropimage button', function() {
    var parent = $(this).parents('.dropimage');
    $(parent).removeAttr('style');
    $(parent).removeClass('dropimagehover');
    $(parent).removeClass('dropnocontent');
    $(parent).find('input').val('');
});

$(document).on('dragenter, dragover', '.dropimage', function(eve) {
    eve.preventDefault();
    $(this).addClass('dropimagehover');
});

$(document).on('dragleave', '.dropimage', function() {
    $(this).removeClass('dropimagehover');
});
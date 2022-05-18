$('body').on('click', '[data-eliminar]', function() {
    var to = $(this).data('eliminar');
    if (to == 'padre') {
        $(this).parent().remove();
    } else {
        $(to).remove();
    }
});

$('body').on('change', '[type="range"]', function() {
    var name = $(this).attr('name');
    var val = $(this).val();
    $('.' + name).text(val);
});

$('body').on('change', '.modal.evento [name="tipo"]', function() {
    var val = $(this).val();

    if (val === 'charla') {
        $('.sistema').hide();
    } else {
        $('.sistema').show();
    }
});

$('body').on('click', '[data-show_pass]', function(eve) {
    eve.preventDefault();

    var input_password = $(this).data('show_pass');

    var img = $(this).find('[src*="eye"]');

    if ($(input_password).attr('type') == 'text') {
        $(img).attr('src', '/img/icons/eye-s.svg');

        $(input_password).attr('type', 'password');
    } else {
        $(img).attr('src', '/img/icons/eye-off-s.svg');

        $(input_password).attr('type', 'text');
    }
});
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
$('body').on('click', '[data-eliminar]', function() {
    var to = $(this).data('eliminar');
    if (to == 'padre') {
        $(this).parent().remove();
    } else {
        $(to).remove();
    }
});
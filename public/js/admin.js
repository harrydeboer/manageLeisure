$(function() {
    $('[name="media_filter"]').on('submit', function (event) {

        window.location.href = '/admin/media/filter/' +
            $('#media_filter_year').val() + '/' + $('#media_filter_month').val();
        event.preventDefault();
    });
});

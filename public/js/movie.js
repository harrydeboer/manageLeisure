$(function() {
    /** When there is no swearing then a request is sent to the server, which sends the email. */
    $('[name=movie]').on('submit', function (event) {
        $('#hide-all').show();

        $.ajax({
            url: '/movie/get-rating',
            type: 'GET',
            data: $(this).serialize(),
            success: function (data) {
                $('#hide-all').hide();
                $('#get-rating').html(data);
            },
            error: function (data) {
                $('#hide-all').hide();
                $('#get-rating').html(data.responseText);
            }
        });

        event.preventDefault();
    });

    $('#get-rating').on('click', 'a', function (event) {
        $('#hide-all').show();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.get($(this).attr('href'), '', function (data) {
            $('#hide-all').hide();
            $('#get-rating').html(data);
        });

        event.preventDefault();
    });
});

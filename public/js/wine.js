$('.dropdown-menu').on('click', function (event) {
    event.stopPropagation();
});

$('.country-select').on('change', function() {
    let id = $(this).val();
    $.get('/country/get-regions/' + id, '', function (data) {
        $('.region-select').html(data);
    });
});

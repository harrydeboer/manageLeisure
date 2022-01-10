$('.dropdown-menu').on('click', function (event) {
    event.stopPropagation();
});

$('.region-select').on('change', function() {
    let id = $(this).val();
    $.get('/region/get-subregions/' + id, '', function (data) {
        $('.subregion-select').html(data);
    });
});

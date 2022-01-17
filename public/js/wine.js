$(function() {
    $('.dropdown-menu').on('click', function (event) {
        event.stopPropagation();
    });

    $('.country-select').on('change', function() {
        let id = $(this).val();
        $.get('/wine/get-regions/' + id, '', function (data) {
            $('.region-select').html(data);
        });
    });

    $('.region-select').on('change', function() {
        let id = $(this).val();
        $.get('/wine/get-subregions/' + id, '', function (data) {
            $('.subregion-select').html(data);
        });
    });

    $('#wineFilterIcon').on('click', function() {
        $('#filter-sort-form').toggle();
    });
});

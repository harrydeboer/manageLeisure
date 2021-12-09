$('#delete-wine').on('click', function() {
    $('#delete-wine-modal').modal('show');
});

$('#delete-taste-profile').on('click', function() {
    $('#delete-taste-profile-modal').modal('show');
});

$('#delete-grape').on('click', function() {
    $('#delete-grape-modal').modal('show');
});

$('#delete-region').on('click', function() {
    $('#delete-region-modal').modal('show');
});

$('.dropdown-menu').on('click', function (event) {
    event.stopPropagation();
});

$('#country, #update_wine_country, #create_wine_country').on('change', function(event) {
    id = $(this).val();
    $.get('/country/get-regions/' + id, '', function (data) {
        $('#region, #update_wine_region, #create_wine_region').html(data);
    });
});

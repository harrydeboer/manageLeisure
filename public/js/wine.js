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

$('.delete-wine').on('click', function() {
    $('#delete-wine-modal').modal('show');
});

$('.delete-category').on('click', function() {
    $('#delete-category-modal').modal('show');
});

$('.delete-grape').on('click', function() {
    $('#delete-grape-modal').modal('show');
});

$('form[name=wine_filter_and_sort_form]').on('change', function() {
    $(this).submit();
});

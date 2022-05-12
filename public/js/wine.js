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

    if ($('#pieChart').length) {
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChart);
    }

    function drawChart() {

        let jsonData = $('#pieChartData').data('pie-chart');

        let data = new google.visualization.DataTable();
        // assumes "word" is a string and "count" is a number
        data.addColumn('string', 'word');
        data.addColumn('number', 'count');

        $.each(jsonData, function (i, val) {
            data.addRow([i, val]);
        });

        let options = {
            title: 'Wines per country'
        };

        let chart = new google.visualization.PieChart($('#pieChart')[0]);

        chart.draw(data, options);
    }
});

let measurements = $('.measurement-graph');
for (let measurement of measurements) {
    let data = JSON.parse(measurement.dataset.measurements);
    data = MG.convert.date(data, 'created_at', '%Y-%m-%dT%H:%M:%S%Z');

    MG.data_graphic({
        title: measurement.dataset.name,
        data: data,
        height: 150,
        full_width: true,
        target: measurement,
        y_label: measurement.dataset.unit,
        x_accessor: 'created_at',
        y_accessor: 'value'
    });
}

d3.selectAll('.mg-rollover-rect rect').on('click', function(point) {
    $('#measurement-date').text(point.created_at.toJSON());
    $.ajax({
        url: '/recordings/closest:' + encodeURIComponent(point.created_at.toJSON()) + '/group:' + encodeURIComponent($('#recording').data('group')),
        success: function(data) {
            let source = '';
            if ('id' in data) {
                source = '/recordings/show:' + encodeURIComponent(data['id']);
            }
            $('#recording-image').attr('src', source);

            let createdAt = '';
            if ('created_at' in data) {
                createdAt = new Date(data['created_at']).toISOString();
            }
            $('#recording-date').text(createdAt);
        }
    });
});
let measurements = $('.measurement-graph');
for (let measurement of measurements) {
    let data = JSON.parse(measurement.dataset.measurements);
    data = MG.convert.date(data, 'created_at', '%Y-%m-%dT%H:%M:%S%Z');

    MG.data_graphic({
        title: measurement.dataset.name,
        data: data,
        height: 250,
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
        url: '/api/recordings/closest:' + encodeURIComponent(point.created_at.toJSON()) + '/group:' + encodeURIComponent($('#recording').data('group')),
        headers: { 'X-Auth-Token': $('#api').data('token') },
        success: function(data) {
            for (const [group, recording] of Object.entries(data)) {
                let image = '/recordings/show:' + encodeURIComponent(recording['id']);
                $('#recording-image-' + group).attr('src', image);
                let createdAt = new Date(recording['created_at']).toISOString();
                $('#recording-date-' + group).text(createdAt);
            }
        }
    });
});
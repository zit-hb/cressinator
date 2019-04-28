let measurements = $('.measurement-graph');
for (let measurement of measurements) {
    let data = JSON.parse(measurement.dataset.measurements);
    data = MG.convert.date(data, 'created_at', '%Y-%m-%d %H:%M:%S');

    MG.data_graphic({
        title: measurement.dataset.name,
        data: data,
        height: 150,
        full_width: true,
        //width: 1000,
        target: measurement,
        y_label: measurement.dataset.unit,
        x_accessor: 'created_at',
        y_accessor: 'value'
    });
}

d3.selectAll('.mg-rollover-rect rect').on('click', function(point) {
    let recording = $('#recording');

    $.ajax({
        url: '/recordings/closest:' + point.created_at.toJSON() + '/group:' + recording.data('group'),
        success: function(data) {
            let source = '';
            if ('id' in data) {
                source = '/recordings/show:' + data['id'];
            }
            $('#recording-image').attr('src', source);

            let createdAt = '';
            if ('created_at' in data) {
                createdAt = data['created_at'];
            }
            $('#recording-date').text(createdAt);
        }
    });
});
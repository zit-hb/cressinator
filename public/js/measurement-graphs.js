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
            for (const [source, recording] of Object.entries(data)) {
                showRecording(recording['id'], recording['created_at'], source);
            }
        }
    });
});

$('.previous-recording').click(function() {
    let source = $(this).data('source');
    let current_date = $('#recording-date-' + source).text();

    if (!current_date) {
        return;
    }

    $.ajax({
        url: '/api/recordings/previous:' + encodeURIComponent(current_date) + '/source:' + encodeURIComponent(source),
        headers: { 'X-Auth-Token': $('#api').data('token') },
        success: function(recording) {
            if (jQuery.isEmptyObject(recording)) {
                return;
            }
            showRecording(recording['id'], recording['created_at'], source);
        }
    });
});

$('.next-recording').click(function() {
    let source = $(this).data('source');
    let current_date = $('#recording-date-' + source).text();

    if (!current_date) {
        return;
    }

    $.ajax({
        url: '/api/recordings/next:' + encodeURIComponent(current_date) + '/source:' + encodeURIComponent(source),
        headers: { 'X-Auth-Token': $('#api').data('token') },
        success: function(recording) {
            if (jQuery.isEmptyObject(recording)) {
                return;
            }
            showRecording(recording['id'], recording['created_at'], source);
        }
    });
});

function showRecording(id, created_at, source) {
    let image = '/recordings/show:' + encodeURIComponent(id);
    $('#recording-image-' + source).attr('src', image);
    let createdAt = new Date(created_at).toISOString();
    $('#recording-date-' + source).text(createdAt);
}

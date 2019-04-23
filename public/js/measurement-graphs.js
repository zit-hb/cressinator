let measurements = document.getElementsByClassName('measurement-graph');
for (let measurement of measurements) {
    let data = JSON.parse(measurement.dataset.measurements);
    data = MG.convert.date(data, 'created_at', '%Y-%m-%d %H:%M:%S');

    MG.data_graphic({
        title: measurement.dataset.name,
        data: data,
        width: 650,
        height: 150,
        target: measurement,
        x_accessor: 'created_at',
        y_accessor: 'value'
    });
}
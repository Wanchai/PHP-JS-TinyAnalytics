
google.charts.load('current', {
  callback: function () {
    drawChart();
    window.addEventListener('resize', drawChart, false);
  },
  packages: ['corechart']
});

function drawChart() {
  var formatDate = new google.visualization.DateFormat({
    pattern: 'MMM d'
  });
  var ticksAxisH;

  function createDataTable(values) {
    var dataTable = new google.visualization.DataTable();
    dataTable.addColumn('date', 'Day');
    dataTable.addColumn('number', 'Unique visitors');
    var today = new Date();
    ticksAxisH = [];
    for (var i = 0; i < values.length; i++) {
      var rowDate = new Date(today - i * 24 * 3600 * 1000);
      var xValue = {
        v: rowDate,
        f: formatDate.formatValue(rowDate)
      };
      var yValue = parseInt(values[i]);
      dataTable.addRow([xValue, yValue]);
      if ((i % 7) === 0) {
        ticksAxisH.push(xValue);
      } // add tick every 7 days
    }
    return dataTable;
  }

  var charts = document.getElementsByClassName("chart");
  for (var i = 0; i < charts.length; i++) {
    var chart = new google.visualization.AreaChart(charts[i]);
    var data = charts[i].getAttribute('data').split(',');
    var dataTable = createDataTable(data);
    chart.draw(dataTable, {
      hAxis: {
        gridlines: {
          color: '#f5f5f5'
        },
        ticks: ticksAxisH
      },
      legend: 'none',
      pointSize: 6,
      lineWidth: 3,
      colors: ['#058dc7'],
      areaOpacity: 0.1,
      vAxis: {
        gridlines: {
          color: '#f5f5f5'
        }
      },
    });
  }
}
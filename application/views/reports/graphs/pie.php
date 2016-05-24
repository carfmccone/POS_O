<script>
	// Labels and data series
	var data = {
		labels: <?php echo json_encode($labels_1); ?>,
		series: <?php echo json_encode($series_data_1); ?>
	};

	var sum = function(a, b) { return a + b };
	
	// We are setting a few options for our chart and override the defaults
	var options = {

		// Specify a fixed width for the chart as a string (i.e. '100px' or '50%')
		width: '100%',

		// Specify a fixed height for the chart as a string (i.e. '100px' or '50%')
		height: '80%',

		// Padding of the chart drawing area to the container element and labels as a number or padding object {top: 5, right: 5, bottom: 5, left: 5}
		chartPadding: {
			top: 10,
//			right: 15,
//			bottom: 10,
//			left: 10
		},

		// show the labels on the border with the pie chart
		labelPosition: 'outside',

		// interpolate labels to show lable, value and %
		labelInterpolationFnc: function(label, index) {
			debugger;;
			return label + ": " + data.series[index] + " / " + Math.round(data.series[index] / data.series.reduce(sum) * 100) + '%';
		}
	};
	
/*	var responsiveOptions = [
		['screen and (min-width: 640px)', {
			chartPadding: 30,
			labelOffset: 100,
			labelDirection: 'explode',
			labelInterpolationFnc: function(value) {
				return value;
			}
		}],
		['screen and (min-width: 1024px)', {
			labelOffset: 80,
			chartPadding: 20
		}]
	];*/

	new Chartist.Pie('#chart1', data, options/*, responsiveOptions*/);
</script>
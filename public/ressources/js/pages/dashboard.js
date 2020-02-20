//[Dashboard Javascript]

//Project:	mínimo admin - Responsive Admin Template
//Primary use:   Used only for the main dashboard (index.html)


$(function () {

  'use strict';

  // Make the dashboard widgets sortable Using jquery UI
  $('.connectedSortable').sortable({
    placeholder         : 'sort-highlight',
    connectWith         : '.connectedSortable',
    handle              : '.box-header, .nav-tabs',
    forcePlaceholderSize: true,
    zIndex              : 999999
  });
  $('.connectedSortable .box-header, .connectedSortable .nav-tabs-custom').css('cursor', 'move');

  // jQuery UI sortable for the todo list
  $('.todo-list').sortable({
    placeholder         : 'sort-highlight',
    handle              : '.handle',
    forcePlaceholderSize: true,
    zIndex              : 999999
  });

  // bootstrap WYSIHTML5 - text editor
  $('.textarea').wysihtml5();

  $('.daterange').daterangepicker({
    ranges   : {
      'Today'       : [moment(), moment()],
      'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
      'Last 30 Days': [moment().subtract(29, 'days'), moment()],
      'This Month'  : [moment().startOf('month'), moment().endOf('month')],
      'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    startDate: moment().subtract(29, 'days'),
    endDate  : moment()
  }, function (start, end) {
    window.alert('You chose: ' + start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
  });

  /* jQueryKnob */
  $('.knob').knob();

  // jvectormap data
  var visitorsData = {
    US: 398, // USA
    SA: 400, // Saudi Arabia
    CA: 1000, // Canada
    DE: 500, // Germany
    FR: 760, // France
    CN: 300, // China
    AU: 700, // Australia
    BR: 600, // Brazil
    IN: 800, // India
    GB: 320, // Great Britain
    RU: 2000 // Russia
  };

	
// world-map
jQuery('#world-map').vectorMap(
{
    map: 'world_mill_en',
    backgroundColor: '#fff',
    borderColor: '#818181',
    borderOpacity: 0.25,
    borderWidth: 1,
    color: '#f4f3f0',
    regionStyle : {
        initial : {
          fill : '#c9d6de'
        }
      },
    markerStyle: {
      initial: {
                    r: 9,
                    'fill': '#fff',
                    'fill-opacity':1,
                    'stroke': '#000',
                    'stroke-width' : 5,
                    'stroke-opacity': 0.4
                },
                },
    enableZoom: true,
    hoverColor: '#0a89c1',
    markers : [{
        latLng : [21.00, 78.00],
        name : 'I Love My India'
      
      }],
    hoverOpacity: null,
    normalizeFunction: 'linear',
    scaleColors: ['#b6d6ff', '#005ace'],
    selectedColor: '#c9dfaf',
    selectedRegions: [],
    showTooltip: true,
    onRegionClick: function(element, code, region)
    {
        var message = 'You clicked "'
            + region
            + '" which has the code: '
            + code.toUpperCase();

        alert(message);
    }
});	
	
  // Sparkline charts
	
  $("#linechart").sparkline([1,4,3,7,6,4,8,9,6,8,12], {
			type: 'line',
			width: '100',
			height: '38',
			lineColor: '#ffffff',
			fillColor: '#7460ee ',
			lineWidth: 2,
			minSpotColor: '#0fc491',
			maxSpotColor: '#0fc491',
		});
		
		$("#barchart").sparkline([1,4,3,7,6,4,8,9,6,8,12], {
			type: 'bar',
			height: '38',
			barWidth: 6,
			barSpacing: 4,
			barColor: '#ffffff',
		});
		$("#discretechart").sparkline([1,4,3,7,6,4,8,9,6,8,12], {
			type: 'discrete',
			width: '50',
			height: '38',
			lineColor: '#ffffff',
		});	
	
  var myvalues = [1300, 500, 1920, 927, 831, 1127, 719, 1930, 1221];
  $('#sparkline-1').sparkline(myvalues, {
    type     : 'line',
    lineColor: '#67757c',
    fillColor: '#c9d6de',
    height   : '50',
    width    : '70'
  });
  myvalues = [715, 319, 620, 342, 662, 990, 730, 467, 559, 340, 881];
  $('#sparkline-2').sparkline(myvalues, {
    type     : 'line',
    lineColor: '#67757c',
    fillColor: '#c9d6de',
    height   : '50',
    width    : '70'
  });
  myvalues = [88, 49, 22,35, 45, 72, 11, 55, 25, 19, 27];
  $('#sparkline-3').sparkline(myvalues, {
    type     : 'line',
    lineColor: '#67757c',
    fillColor: '#c9d6de',
    height   : '50',
    width    : '70'
  });

  // The Calender
  $('#calendar').datepicker();

  // SLIMSCROLL FOR CHAT WIDGET
  $('#chat-box').slimScroll({
    height: '315px'
  }); 
	
	/* Morris.js Charts */
  // Sales chart
  var line = new Morris.Line({
    element          : 'line-chart',
    resize           : true,
    data             : [
      { y: '2015 Q1', item1: 2800 },
      { y: '2015 Q2', item1: 2500 },
      { y: '2015 Q3', item1: 4200 },
      { y: '2015 Q4', item1: 3900 },
      { y: '2016 Q1', item1: 4589 },
      { y: '2016 Q2', item1: 6489 },
      { y: '2016 Q3', item1: 3548 },
      { y: '2016 Q4', item1: 12589 },
      { y: '2017 Q1', item1: 11025 },
      { y: '2017 Q2', item1: 19540 }
    ],
    xkey             : 'y',
    ykeys            : ['item1'],
    labels           : ['Item 1'],
    lineColors       : ['#7460ee'],
    lineWidth        : 2,
    hideHover        : 'auto',
    gridTextColor    : '#999',
    gridStrokeWidth  : 0.2,
    pointSize        : 4,
    pointStrokeColors: ['#7460ee'],
    gridLineColor    : '#999',
    gridTextFamily   : 'Open Sans',
    gridTextSize     : 10
  });
//--------------
		//- AREA CHART -
		//--------------

 // Get context with jQuery - using jQuery's .get() method.
		var areaChartCanvas = $('#areaChart').get(0).getContext('2d')
		// This will get the first returned node in the jQuery collection.
		var areaChart       = new Chart(areaChartCanvas)

		var areaChartData = {
		  labels  : ['1', '2', '3', '4', '5', '6', '7', '8'],
		  datasets: [
			{
			  label               : 'Electronics',
			  fillColor           : 'rgba(252,75,108,0.3)',
			  strokeColor         : 'rgba(252,75,108,1)',
			  pointColor          : 'rgba(252,75,108,0.5)',
			  pointStrokeColor    : '#1eacbe',
			  pointHighlightFill  : '#fff',
			  pointHighlightStroke: 'rgba(252,75,108,1)',
			  data                : [0, 5, 6, 8, 25, 9, 8, 24]
			},
			{
			  label               : 'Digital Goods',
			  fillColor           : 'rgba(56,154,240,0.3)',
			  strokeColor         : 'rgba(56,154,240,1)',
			  pointColor          : '#06d79c',
			  pointStrokeColor    : 'rgba(56,154,240,0.5)',
			  pointHighlightFill  : '#fff',
			  pointHighlightStroke: 'rgba(56,154,240,1)',
			  data                : [0, 3, 1, 2, 8, 1, 5, 1]
			}
		  ]
		}

		var areaChartOptions = {
		  //Boolean - If we should show the scale at all
		  showScale               : true,
		  //Boolean - Whether grid lines are shown across the chart
		  scaleShowGridLines      : true,
		  //String - Colour of the grid lines
		  scaleGridLineColor      : 'rgba(0,0,0,.05)',
		  //Number - Width of the grid lines
		  scaleGridLineWidth      : 1,
		  //Boolean - Whether to show horizontal lines (except X axis)
		  scaleShowHorizontalLines: true,
		  //Boolean - Whether to show vertical lines (except Y axis)
		  scaleShowVerticalLines  : true,
		  //Boolean - Whether the line is curved between points
		  bezierCurve             : true,
		  //Number - Tension of the bezier curve between points
		  bezierCurveTension      : 0.5,
		  //Boolean - Whether to show a dot for each point
		  pointDot                : true,
		  //Number - Radius of each point dot in pixels
		  pointDotRadius          : 1,
		  //Number - Pixel width of point dot stroke
		  pointDotStrokeWidth     : 1,
		  //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
		  pointHitDetectionRadius : 20,
		  //Boolean - Whether to show a stroke for datasets
		  datasetStroke           : true,
		  //Number - Pixel width of dataset stroke
		  datasetStrokeWidth      : 0,
		  //Boolean - Whether to fill the dataset with a color
		  datasetFill             : true,
		  //String - A legend template
		  legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
		  //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
		  maintainAspectRatio     : true,
		  //Boolean - whether to make the chart responsive to window resizing
		  responsive              : true
		};
		
		//Create the line chart
		areaChart.Line(areaChartData, areaChartOptions);
	



  // Fix for charts under tabs
  $('.box ul.nav a').on('shown.bs.tab', function () {
    area.redraw();
    donut.redraw();
    line.redraw();
  });

  /* The todo list plugin */
  $('.todo-list').todoList({
    onCheck  : function () {
      window.console.log($(this), 'The element has been checked');
    },
    onUnCheck: function () {
      window.console.log($(this), 'The element has been unchecked');
    }
  });

}); // End of use strict

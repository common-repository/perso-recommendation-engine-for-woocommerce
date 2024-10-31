var randomScalingFactor = function(){ return Math.round(Math.random()*100)};

	var barChartData = {
		labels : ["January","February","March","April","May","June","July"],
		datasets : [
			{
				label: "Sales",
				fillColor : "rgba(70,191,189,0.5)",
				strokeColor : "rgba(70,191,189,0.8)",
				highlightFill: "rgba(70,191,189,0.75)",
				highlightStroke: "rgba(70,191,189,1)",
				data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
			},
			{
				label: "Abandonned Cart Values",
				fillColor : "rgba(247,70,74,0.5)",
				strokeColor : "rgba(247,70,74,0.8)",
				highlightFill : "rgba(247,70,74,0.75)",
				highlightStroke : "rgba(247,70,74,1)",
				data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
			}
		]

	}

var randomColorFactor = function(){ return Math.round(Math.random()*255)};


var lineChartData = {
		labels : ["January","February","March","April","May","June","July"],
		datasets : [
			{
				label: "My First dataset",
				fillColor : "rgba(220,220,220,0.5)",
				strokeColor : "rgba(220,220,220,1)",
				pointColor : "rgba(220,220,220,1)",
				pointStrokeColor : "#fff",
				pointHighlightFill : "#fff",
				pointHighlightStroke : "rgba(220,220,220,1)",
				data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
			},
			{
				label: "My Second dataset",
				fillColor : "rgba(151,187,205,0.5)",
				strokeColor : "rgba(151,187,205,1)",
				pointColor : "rgba(151,187,205,1)",
				pointStrokeColor : "#fff",
				pointHighlightFill : "#fff",
				pointHighlightStroke : "rgba(151,187,205,1)",
				data : [randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor(),randomScalingFactor()]
			}
		]
}

var options = {
    responsive : true,
    multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
}

window.onload = function(){
		
		var ctx = document.getElementById("canvas").getContext("2d");
		window.myBar = new Chart(ctx).Bar(barChartData, options);
		
		var ctx = document.getElementById("canvas-line").getContext("2d");
		window.myLine = new Chart(ctx).Line(lineChartData, options);

	};
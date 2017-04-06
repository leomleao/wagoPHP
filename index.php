<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
<title>WAGO</title>

<?php

include ("_meta.php");
?>
 <!--<script type="text/javascript" src="themes/gray.js"></script>-->
 <script type="text/javascript" src="themes/grid.js"></script>
 <script type="text/javascript" src="themes/gray.js"></script>
 <script type="text/javascript" src="themes/dark-blue.js"></script>
 <script type="text/javascript" src="themes/dark-green.js"></script>
 <script type="text/javascript" src="themes/default.js"></script>
</head>
<body>
<div id="wrapper">
<?php include ("_header.php");?>
<div id="content">

<h1 id ="content-top">File Exchange with CoDeSys</h1>
 <div id="top" >

<div id="showDetail" class="mybutton inline"><h4><img src="images/refresh.gif" alt="" /></h4></div>
	<div class="inline">
	<input type="checkbox" class="css-checkbox " id="auto"  checked="checked"/>
	<label for="auto" name="checkbox1_lbl"  class="css-label lite-green-check">Refresh Auto  </label>
	</div>
	<input id="rangeInput" class="slider" style="width:60px; "type="range" value="5" name="rangeInput" min="2" max="10" step='.5' onchange="updateTextInput(this.value);">                                                       
   <label for="rangeInput" class="slider" name="rangeInput_lbl"  id="rangeInput_lbl">5 s</label>

				<select name="theme"id="theme" style="float:right">
		<option value="grid">Grid</option>
		<option value="dark-blue">Dark Blue</option>
		<option value="dark-green">Dark Green</option>
		<option value="gray">Gray</option>
		<option value="skies">Skies</option>
		<option value="default">Default</option>
		</select>
</div>
 <div id="bottom" >
</div>
 
</div>
</div>
 <script>
 
 
 var theme=grid;
  var refreshtime=5000;
  function updateTextInput(val) {
	//alert(val);
	refreshtime=val*1000;
	clearInterval(refreshId);
			refreshId=0;
refreshId = setInterval( function() 
		{
			// var r = (-0.5)+(Math.random()*(1000.99));
			 $('#showDetail h4').html('<img src="images/486.gif" style="max-width:100%; max-height:100%;" >');
	refresh();}, refreshtime);
     $("#rangeInput_lbl").html(val+" s")
    }

function getSerie(chart,attr, value) {
    jQuery.each(chart.series, function(seriePosition, serie) {
        if(serie.options[attr] == value) return serie;
    });
}
 var refreshId ;
 function refresh(force)
 {   
 $.post('listfile.php',function(data) { var json = data;
	if (json =='no files') { 
	
	 if (!document.getElementById('nodata'))
	{$("#bottom").append("<div id='nodata' class='nodata'>No Data to display</div>");
	 Cufon.replace('.nodata',{fontFamily: 'Futura', hover: {color: '#58AD55'}}); 
	 //alert("blabla");
	}
	}
	else   {
	 if (document.getElementById('nodata')) { $('#nodata').remove();};

	var obj = $.parseJSON(json);
  
 for (var i = 0; i < obj.length; i++)

	{	
	
	var temp = obj[i].substr(0,obj[i].lastIndexOf("."));
	temp = temp.substr(temp.lastIndexOf("/")+1);
	
		var parameter = temp.split("&");
		var datapointname=parameter[0];
		var seriename=parameter[1];
		var color=parameter[2];
		var type=parameter[3];
		var typexaxis=parameter[4];
		//var theme=parameter[4];
		//alert(seriename +' ' +color);
		if (!document.getElementById(datapointname))
	{
		$("#bottom").append("<div id="+datapointname+" class='graph'></div>")
		;
	}	
	
		getGraph(obj[i],datapointname,seriename,type,color,typexaxis,force);
		
	}
	if (i = obj.length)
		{ $('#showDetail h4').html('<img src="images/refresh.gif" alt="" />');}
		 $( ".graph" ).each(function( index ) {  if ($(this).attr('id') >= obj.length) { $(this).remove();}

	});
 
   }
   }); 

 }
	function getGraph(filename,datapointname,seriename,type,color,typexaxis,force) {
	//alert(typexaxis);
 var highchartsOptions = Highcharts.setOptions(theme);
	var series;
	
	  $.post('data.php', {file: filename},function(data) {/*$('#data').html(data);*/ var json = data;
	
	  var jsonObj = $.parseJSON(json);

	var seriesData = [],
    categories = [];
	for (var i = 0; i < jsonObj.length; i++) {
		//you may want to do a parseInt/parseFloat it this value is a string
		
			//alert(Date.parse(jsonObj[i].label));
		seriesData.push(parseFloat(jsonObj[i].data));
		if (typexaxis=='datetime') { //alert('date ok');   
	
			var date = new Date(jsonObj[i].label.split(' ').join('T'));
			categories.push(date.getTime());
			
		} 
		else {//typexaxis='linear';
			//alert('linear');
		categories.push( jsonObj[i].label);
		}
		;
	}
	
	
	if (typexaxis =='datetime') {
	for(j=0;j<seriesData.length;j++) { 
		var temp = new Array(categories[j],seriesData[j]); 
		seriesData[j] = temp;     
		}//alert('ici');
		var date_sort_asc = function (date1, date2) { if (date1 > date2) return 1;  if (date1 < date2) return -1;  return 0;};
		seriesData.sort(date_sort_asc);
			//alert(JSON.stringify(seriesData));
			
			xaxis = { type : typexaxis
			};
		}
		else
		{
		xaxis ={ type : typexaxis,
		minorTickInterval : auto,
       categories: categories
    };
		
		}
		
	
		
		if (type =='pie')
		{ //alert('blabla');
		for(j=0;j<categories.length;j++) { 
		var temp = new Array(categories[j],seriesData[j]); 
		seriesData[j] = temp;     
		}
		}
		//alert(JSON.stringify(seriesData));
		//alert(darkgreen);
	var chart = $('#'+datapointname).highcharts();
	

	if (chart && (!force))
  { 
  var serie = chart.get(seriename);
  
		if (!serie) 
			{ if (color!='default')
			{//alert('set color');
			 series = {
						id: seriename,
						name: seriename,
						type :type,
						color: color,
						data: seriesData
						};
			}
			else
			{ series = {
						id: seriename,
						name: seriename,
						type :type,
						data: seriesData
						};
			}
			chart.addSeries(series);
			//alert('ajout');
			}
			
			else
			{/* if (typexaxis =='datetime')
			{serie.setData(seriesData);
			}
			else
			{*/
		  $.each(serie.data, function (i, point) {
         point.update(seriesData[i], false);
    })};
		chart.redraw();
		
		//	}
}
else
{	

			if (color!='default')
			{//alert('set color');
			 series = {
						id: seriename,
						name: seriename,
						type :type,
						color: color,
						data: seriesData
						};
			}
			else
			{ series = {
						id: seriename,
						name: seriename,
						type :type,
						data: seriesData
						};
			}
	if (type!='gauge' ) {	
var myoptions = {
	tooltip: {
        valueDecimals: 2
    },

	chart: {
	  zoomType: 'x'
		},
	  legend: {enabled: true},     
	credits: {
      enabled: false
  },

	 title: {
                text: datapointname,
                x: -20 //center
            },

 xAxis: xaxis,
 
    series: [series],
	 exporting: {
			
			buttons: {
            'myButton': {
                _id: 'myButton',
                symbol: 'url(images/minmax.png)',
                //symbolX:6,
                //symbolY:6,
                x: -65,
                symbolFill: '#B5C9DF',
                hoverSymbolFill: '#779ABF',
                onclick: function () {
						if ($("#"+datapointname).attr("class")=='graph')
					{$("#"+datapointname).attr("class", "graphmax");
					this.setSize($(".graphmax").width(), $(".graphmax").height(), doAnimation = true);
                    }
					else
					{
					this.setSize($(".graph").width(), $(".graph").height(), doAnimation = false);
					$("#"+datapointname).attr("class", "graph");
					}
					}
            }
        }

        }
   };
		if (typexaxis =='datetime' )
		{$('#'+datapointname).highcharts('StockChart', myoptions);}
		else {$('#'+datapointname).highcharts( myoptions);}
   }
   
   else
   
   { $('#'+datapointname).highcharts({
	
	    chart: {
	        type: 'gauge',
	        plotBackgroundColor: null,
	        plotBackgroundImage: null,
	        plotBorderWidth: 0,
	        plotShadow: false
	    },
	    
	    title: {
	        text: datapointname
	    },
		
	    credits: {
      enabled: false
  },
	    pane: {
	        startAngle: -150,
	        endAngle: 150,
	        background: [{
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#FFF'],
	                    [1, '#333']
	                ]
	            },
	            borderWidth: 0,
	            outerRadius: '109%'
	        }, {
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#333'],
	                    [1, '#FFF']
	                ]
	            },
	            borderWidth: 1,
	            outerRadius: '107%'
	        }, {
	            // default background
	        }, {
	            backgroundColor: '#DDD',
	            borderWidth: 0,
	            outerRadius: '105%',
	            innerRadius: '103%'
	        }]
	    },
	       
	    // the value axis
	    yAxis: {
	        min: 0,
	        max: 200,
	        
	        minorTickInterval: 'auto',
	        minorTickWidth: 1,
	        minorTickLength: 10,
	        minorTickPosition: 'inside',
	        minorTickColor: '#666',
	
	        tickPixelInterval: 30,
	        tickWidth: 2,
	        tickPosition: 'inside',
	        tickLength: 10,
	        tickColor: '#666',
	        labels: {
	            step: 2,
	            rotation: 'auto'
	        },
			exporting: {
			
			buttons: {
            'myButton': {
                _id: 'myButton',
                symbol: 'url(images/minmax.png)',
                //symbolX:6,
                //symbolY:6,
                x: -65,
                symbolFill: '#B5C9DF',
                hoverSymbolFill: '#779ABF',
                onclick: function () {
						if ($("#"+datapointname).attr("class")=='graph')
					{$("#"+datapointname).attr("class", "graphmax");
					this.setSize($(".graphmax").width(), $(".graphmax").height(), doAnimation = true);
                    }
					else
					{
					this.setSize($(".graph").width(), $(".graph").height(), doAnimation = false);
					$("#"+datapointname).attr("class", "graph");
					}
					}
            }
        }

        },
	        title: {
	            text: seriename
	        },
	        plotBands: [{
	            from: 0,
	            to: 120,
	            color: '#55BF3B' // green
	        }, {
	            from: 120,
	            to: 160,
	            color: '#DDDF0D' // yellow
	        }, {
	            from: 160,
	            to: 200,
	            color: '#DF5353' // red
	        }]        
	    },
	
	    series: [series]
	
	});}
}
});
}
	  $(document).ready(function() {
	   $(".graph").click(function() {
		   $.fancybox(this);
		  });
	  //$("#rangeInput").val=(refreshtime/1000);
	  refresh();
	   if ($('#auto').is(":checked"))
		{	$(".slider").show(500);
		 	  refreshId = setInterval( function() 
		{
			// var r = (-0.5)+(Math.random()*(1000.99));
			 $('#showDetail h4').html('<img src="images/486.gif" style="max-width:100%; max-height:100%;" >');
	refresh();}, refreshtime);
		}
		else 

		{	$(".slider").hide();
			clearInterval(refreshId);
			refreshId=0;
		  }
		  $( "#theme" ).change(function() { 
				switch ($( "#theme" ).val()) 
				{ 
				case 'grid': theme=grid; 
				break; 
				case 'dark-blue': theme=darkblue; 
				break; 
				case 'dark-green': theme=darkgreen; 
				break; 
				case 'skies': theme=skies; 
				break; 
				case 'default': theme=defaulttheme; 
				break; 
				case 'gray': theme=gray; 
				break; 
				}


		 refresh(true);});
		 
		$("#auto").click(function() {
	
		 if ($('#auto').is(":checked"))
		{	$(".slider").show(500);
		 	  refreshId = setInterval( function() 
		{
			// var r = (-0.5)+(Math.random()*(1000.99));
			 $('#showDetail h4').html('<img src="images/486.gif" style="max-width:100%; max-height:100%;" >');
	refresh();}, refreshtime);
		}
		else 

		{	$(".slider").hide();
			clearInterval(refreshId);
			refreshId=0;
		  }
		 });
	  $("#showDetail").click(function() { $('#showDetail h4').html('<img src="images/486.gif" style="max-width:100%; max-height:100%;" >');refresh();});
	  
	});   
 
  </script>
</body>
</html>
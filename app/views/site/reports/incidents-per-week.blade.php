{{--*/
	$incident_types = array(
		[
			'name'	=> 'Accidente de Transito',
			'value'	=> 1
		],
		[
			'name'	=> 'Accidente Laboral',
			'value'	=> 2
		]
	);

	$hasIncidents= (count($incidents) > 0) ? true : false;
	if($hasIncidents)
	{
		$categories = array();

		$series = array();
		
		//rellenar los valores de las series
		foreach ($incident_types as $incident_type)
		{
			$serie_values = array();
			foreach ($dates as $date)
			{
				$date_string_from = Timesor::createFromSystem($date['system'])->startOfDay()->toDateTimeString();
				$date_string_until = Timesor::createFromSystem($date['system'])->endOfDay()->toDateTimeString();

				$incidentes = LocationHistory::where('incident_type_id', $incident_type['value'])
				->where('created_at', '>=', $date_string_from)
				->where('created_at', '<=', $date_string_until)
				->get()->toArray();

				array_push($serie_values, count($incidentes));
			}
			array_push($series, ['name' => $incident_type['name'], 'data' => $serie_values]);
		}		

		$serie_total_values = array();

		foreach ($dates as $date)
		{
			$date_string_from = Timesor::createFromSystem($date['system'])->startOfDay()->toDateTimeString();
			$date_string_until = Timesor::createFromSystem($date['system'])->endOfDay()->toDateTimeString();

			$incidentes_totales = LocationHistory::whereNotNull('incident_type_id')
				->where('created_at', '>=', $date_string_from)
				->where('created_at', '<=', $date_string_until)
				->get()->toArray();

			array_push($serie_total_values, count($incidentes_totales));
		}

		//sacar las fechas en formato d/m/Y
		$counter = 0;
		foreach ($dates as $date)
		{
			array_push($categories, Timesor::getDayName($date['system']).'<br>'.$date['readable'].'<br>Total de incidentes: '.$serie_total_values[$counter]);
			$counter++;
		}

		$categories = json_encode($categories);
		$series = json_encode($series);
	}
	
/*--}}

@extends('layouts.dashboard-base')

@section('content')
	<div class="row">
		<div class="col-md-12">
			{{$templator->getForm()}}
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-sm-12">
		@if($hasIncidents)
			<div id="chart"></div>
		@else
			<h4 class="text-center"><strong>No hay Datos que mostrar para las fechas {{$date_from}} al {{$date_until}}</strong></h4>
		@endif
		</div>
	</div>
@stop		

@section('js')

	<script>
    	$(function () {
    		
            $('#date_from').datetimepicker({
                locale: 'es',
                format: 'DD/MM/YYYY',
                sideBySide: true
            });

            $('#date_until').datetimepicker({
                locale: 'es',
                format: 'DD/MM/YYYY',
                sideBySide: true
            });
        });
    </script>

	@if($hasIncidents)
    <script src="{{asset('assets/js/highcharts/plugins/highcharts-legend-yaxis.min.js')}}"></script>

	<script>
		$(function () {
		    $('#chart').highcharts({
		        chart: {
		            type: 'column',
		            inverted: false
		        },
		        title: {
		            text: 'Incidencias por día <small>del {{$date_from}} al {{$date_until}}</small>'
		        },
		        subtitle: {
		            style: {
		                position: 'absolute',
		                right: '0px',
		                bottom: '10px'
		            }
		        },
		        legend: { enabled: true },
		        xAxis: {
		            categories: {{$categories}}
		        },
		        yAxis: {
		            title: {
		                text: 'Número de incidencias'
		            },
		            labels: {
		                formatter: function () {
		                    return this.value;
		                }
		            },
		            min: 0
		        },
		        plotOptions: {
		            area: {
		                fillOpacity: 0.5
		            }
		        },
		        series: {{$series}}
		    });
		});
	</script>
	@endif
@stop
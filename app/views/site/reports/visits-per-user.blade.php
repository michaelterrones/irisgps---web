{{--*/
	$task_states = array(
		[
			'name'	=> 'Programado',
			'value'	=> 1,
			'color' => '#4527a0'
		],
		[
			'name'	=> 'Realizado',
			'value'	=> 2,
			'color' => '#2e7d32'
		],
		[
			'name'	=> 'Cancelado',
			'value'	=> 3,
			'color' => '#424242'
		],
		[
			'name'	=> 'Pospuesto',
			'value'	=> 4,
			'color' => '#ff8f00'
		],
		[
			'name'	=> 'Reprogramado',
			'value'	=> 5,
			'color' => '#9e9d2f'
		],
	);

	$hasVisits= (count($visits) > 0) ? true : false;
	if($hasVisits)
	{
		$categories = array();

		$series = array();
		
		//rellenar los valores de las series
		foreach ($task_states as $task_state)
		{
			$serie_values = array();
			foreach ($dates as $date)
			{				
				$visitas = RouteTask::leftJoin('tracking_routes', 'tracking_routes.id', '=', 'route_tasks.tracking_route_id')
				->where('tracking_routes.date', '=', $date['system'])
				->where('route_tasks.task_state_id', $task_state['value'])
				->select('route_tasks.id')
				->get()->toArray();
				array_push($serie_values, count($visitas));
			}
			array_push($series, ['name' => $task_state['name'], 'data' => $serie_values, 'color' => $task_state['color']]);
		}		

		$serie_total_values = array();

		foreach ($dates as $date)
		{
			$visitas_totales = RouteTask::leftJoin('tracking_routes', 'tracking_routes.id', '=', 'route_tasks.tracking_route_id')
			->where('tracking_routes.date', '=', $date['system'])
			->select('route_tasks.id')
			->get()->toArray();

			array_push($serie_total_values, count($visitas_totales));
		}

		//sacar las fechas en formato d/m/Y
		$counter = 0;
		foreach ($dates as $date)
		{
			array_push($categories, Timesor::getDayName($date['system']).'<br>'.$date['readable'].'<br>Total de visitas: '.$serie_total_values[$counter]);
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
		@if($hasVisits)
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

	@if($hasVisits)
    <script src="{{asset('assets/js/highcharts/plugins/highcharts-legend-yaxis.min.js')}}"></script>

	<script>
		$(function () {
		    $('#chart').highcharts({
		        chart: {
		            type: 'column',
		            inverted: false
		        },
		        title: {
		            text: 'Estado de visitas por día <small>del {{$date_from}} al {{$date_until}}</small>'
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
		                text: 'Número de visitas'
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
<?php

class ReportsController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function getVisitsPerWeek()
	{
		$week_number = Input::get('week_number', Timesor::getCurrentWeekNumber());
		$current_year = Timesor::getCurrentYear();

		$date_from = Timesor::getFirstDayOfWeekNumber($current_year, $week_number, $format = 'd/m/Y');
		$date_until = Timesor::getLastDayOfWeekNumber($current_year, $week_number, $format = 'd/m/Y');		

		$from = Timesor::createFromDMY($date_from)->startOfDay()->toDateTimeString();
		$until = Timesor::createFromDMY($date_until)->endOfDay()->toDateTimeString();

		$visits = RouteTask::leftJoin('tracking_routes', 'tracking_routes.id', '=', 'route_tasks.tracking_route_id')
			->where('tracking_routes.date', '>=', $from)
			->where('tracking_routes.date', '<=', $until)
			->distinct('tracking_routes.date')
			->select('tracking_routes.date')
			->get()->toArray();

		$dates = array();

		$current_date = Timesor::createFromDMY($date_from)->subDay()->startOfDay();

		for ($i=0; $i < 7; $i++) { 
			$current_date = $current_date->addDay();
			$date = ['system' => $current_date->format('Y-m-d H:i:s'), 'readable' => $current_date->format('d/m/Y')];
			array_push($dates, $date);
		}

		$weeks = Timesor::getWeeksArray();

		$templator = new Templator;
		$templator->isFormInline();
		$templator->setSize(5);
		$templator->createForm($actionRoute = 'getVisitsPerWeek', $routeParams = null, $method = 'GET', $legend = 'Visitas por Semana', $submitName = 'Ver', $resetName = null);
		$templator->addSelectBasic($id = 'week_number', $label = 'Semana', $name = 'week_number', $elements = $weeks, $haveEmptyOption = false, $selected = $week_number);

		return View::make('site.reports.visits-per-user')
			->with(
				array(
					'pageTitle'=>'Reportes',
					'visits' => $visits,
					'templator' => $templator,
					'dates' => $dates,
					'date_from' => Timesor::formatFromSystem('d/m/Y', $dates[0]['system']),
					'date_until' => Timesor::formatFromSystem('d/m/Y', $dates[6]['system'])
					)
				);
	}

	public function getIncidentsPerWeek()
	{
		$week_number = Input::get('week_number', Timesor::getCurrentWeekNumber());
		$current_year = Timesor::getCurrentYear();

		$date_from = Timesor::getFirstDayOfWeekNumber($current_year, $week_number, $format = 'd/m/Y');
		$date_until = Timesor::getLastDayOfWeekNumber($current_year, $week_number, $format = 'd/m/Y');

		$from = Timesor::createFromDMY($date_from)->startOfDay()->toDateTimeString();
		$until = Timesor::createFromDMY($date_until)->endOfDay()->toDateTimeString();

		$incidents = LocationHistory::whereNotNull('incident_type_id')
			->where('created_at', '>=', $from)
			->where('created_at', '<=', $until)
			->get()->toArray();

		$dates = array();

		$current_date = Timesor::createFromDMY($date_from)->subDay()->startOfDay();

		for ($i=0; $i < 7; $i++) { 
			$current_date = $current_date->addDay();
			$date = ['system' => $current_date->format('Y-m-d H:i:s'), 'readable' => $current_date->format('d/m/Y')];
			array_push($dates, $date);
		}

		$weeks = Timesor::getWeeksArray();

		$templator = new Templator;
		$templator->isFormInline();
		$templator->setSize(5);
		$templator->createForm($actionRoute = 'getIncidentsPerWeek', $routeParams = null, $method = 'GET', $legend = 'Incidentes por Semana', $submitName = 'Ver', $resetName = null);
		$templator->addSelectBasic($id = 'week_number', $label = 'Semana', $name = 'week_number', $elements = $weeks, $haveEmptyOption = false, $selected = $week_number);

		return View::make('site.reports.incidents-per-week')
			->with(
				array(
					'pageTitle'=>'Reportes',
					'incidents' => $incidents,
					'dates' => $dates,
					'templator' => $templator,
					'date_from' => Timesor::formatFromSystem('d/m/Y', $dates[0]['system']),
					'date_until' => Timesor::formatFromSystem('d/m/Y', $dates[6]['system'])
					)
				);
	}

}

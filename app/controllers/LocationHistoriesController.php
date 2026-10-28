<?php

class LocationHistoriesController extends \BaseController {

	public function postStoreLocations()
	{
		$plate = Input::get('plate');
		$route_id = Input::get('route_id');
		$locations = Input::get('locations');

		return LocationHistoryService::storeLocations($plate, $route_id, $locations);		
	}

	/*
	public function index()
	{
		$locationhistories = Locationhistory::all();

		return View::make('locationhistories.index', compact('locationhistories'));
	}

	public function create()
	{
		return View::make('locationhistories.create');
	}

	public function store()
	{
		$validator = Validator::make($data = Input::all(), Locationhistory::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Locationhistory::create($data);

		return Redirect::route('locationhistories.index');
	}

	public function show($id)
	{
		$locationhistory = Locationhistory::findOrFail($id);

		return View::make('locationhistories.show', compact('locationhistory'));
	}

	public function edit($id)
	{
		$locationhistory = Locationhistory::find($id);

		return View::make('locationhistories.edit', compact('locationhistory'));
	}

	public function update($id)
	{
		$locationhistory = Locationhistory::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Locationhistory::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$locationhistory->update($data);

		return Redirect::route('locationhistories.index');
	}

	public function destroy($id)
	{
		Locationhistory::destroy($id);

		return Redirect::route('locationhistories.index');
	}
	*/

}

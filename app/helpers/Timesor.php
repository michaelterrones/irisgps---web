<?php

class Timesor {

	public $isFuture = true;
	public $years, $months, $days, $hours, $minutes, $seconds = 0;

	public static function diffForHumans($carbon_time)
	{

		$isFuture = true;

		$years = 0;
		$months = 0;
		$days = 0;
		$hours = 0;
		$minutes = 0;
		$seconds = 0;

		$date = $carbon_time;
		$now = Carbon::now();
		$interval = date_diff($now, $date);

		$isFuture = ($interval->format('%R') == '+') ? true : false;

		$years = intval($interval->format('%y'));
		$months = intval($interval->format('%m'));
		$days = intval($interval->format('%d'));
		$hours = intval($interval->format('%H'));
		$minutes = intval($interval->format('%i'));
		$seconds = intval($interval->format('%s'));

		$values = [
			'years' => $years,
			'months' => $months,
			'days' => $days,
			'hours' => $hours,
			'minutes' => $minutes,
			'seconds' => $seconds
		];

		$keys = array_keys($values);
		$values = array_values($values);

		$posKey = 0;

		for ($i=0; $i < count($values); $i++) { 
			if($values[$i] > 0)
			{
				$posKey = $i;
				break;
			}
		}

		$element = $keys[$posKey];
		$value = $$element;
		$delta = $keys[$posKey];

		$txt = '';

		switch ($delta) {
			case 'years':
				$delta = ($value <= 1) ? ' año' : ' años';
				break;
			case 'months':
				$delta = ($value <= 1) ? ' mes' : ' meses';
				break;
			case 'days':
				$delta = ($value <= 1) ? ' día' : ' días';
				break;
			case 'hours':
				$delta = ($value <= 1) ? ' hora' : ' horas';
				break;
			case 'minutes':
				$delta = ($value <= 1) ? ' minuto' : ' minutos';
				break;
			case 'seconds':
				$delta = ($value <= 10) ? 'unos instantes' : ' segundos';
				$value = '';
				break;
		}

		if($isFuture && $value === 0)
		{
			$txt = 'en este preciso momento';
		}else
		{
			$txt = (($isFuture) ? 'dentro de ' : 'hace ').$value.$delta;
		}

		return $txt;

	}

	public static function formatFromSystem($format, $dateString)
	{
		return Carbon::createFromFormat('Y-m-d H:i:s', $dateString)->format($format);
	}

	public static function createFromSystem($dateString)
	{
		return Carbon::createFromFormat('Y-m-d H:i:s', $dateString);
	}

	public static function createFromDMY($dateString)
	{
		return Carbon::createFromFormat('d/m/Y', $dateString);
	}

	public static function formatDMY($carbon_date)
	{
		return $carbon_date->format('d/m/Y');
	}

	public static function getDayName($dateString)
	{
		$dt = Carbon::createFromFormat('Y-m-d H:i:s', $dateString);
		switch ($dt->dayOfWeek) {
			case Carbon::MONDAY:
				$day = 'Lunes';
				break;
			case Carbon::TUESDAY:
				$day = 'Martes';
				break;
			case Carbon::WEDNESDAY:
				$day = 'Miercoles';
				break;
			case Carbon::THURSDAY:
				$day = 'Jueves';
				break;
			case Carbon::FRIDAY:
				$day = 'Viernes';
				break;
			case Carbon::SATURDAY:
				$day = 'Sábado';
				break;
			case Carbon::SUNDAY:
				$day = 'Domingo';
				break;
		}
		return $day;
	}

	public static function getCurrentYear()
	{
		$date = new DateTime();
		return $year = $date->format("Y");
	}

	public static function getCurrentWeekNumber()
	{
		$date = new DateTime();
		return $week = $date->format("W");
	}

	public static function getFirstDayOfWeekNumber($year, $week, $format = 'Y-m-d H:i:s')
	{
		$date = new DateTime();
		$date->setISODate($year, $week);
		return $date->format($format);
	}

	public static function getLastDayOfWeekNumber($year, $week, $format = 'Y-m-d H:i:s')
	{
		$date = new DateTime();
		$date->setISODate($year, $week, 7);
		return $date->format($format);
	}

	public static function getWeeksArray($upDownVal = 5)
	{
		$year = Timesor::getCurrentYear();
		$week =  Timesor::getCurrentWeekNumber();

		$array = array();

		for ($i=-$upDownVal; $i <= $upDownVal; $i++) { 
			$array[($week+$i)] = 'Semana '.($week+$i).': del '.Timesor::getFirstDayOfWeekNumber($year, ($week+$i), 'd/m/Y').' al '.Timesor::getLastDayOfWeekNumber($year, ($week+$i), 'd/m/Y');
		}

		return $array;
	}
}
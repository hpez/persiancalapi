<?php


namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Morilog\Jalali\CalendarUtils;
use \App\Classes;

class HolidayController extends Controller
{
    public function index($type, $y, $m, $d)
    {
        if ($type == 'jalali') {
            list($y,$m,$d) = CalendarUtils::toGregorian($y, $m, $d);
        }
		$date="$y-$m-$d";
		$calendar = new \App\Classes\Calendar;
		$events = $calendar->getEvents($date);
		return $events;
    }
}
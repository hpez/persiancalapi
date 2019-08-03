<?php


namespace App\Http\Controllers;


use App\Classes\Calendar;
use Morilog\Jalali\CalendarUtils;

class HolidayController extends Controller
{
    public function index($type, $y, $m, $d)
    {
        if ($type == 'jalali') {
            list($y, $m, $d) = CalendarUtils::toGregorian($y, $m, $d);
        }
        $date = "$y-$m-$d";
        $calendar = new Calendar;
        $events = $calendar->getEvents($date);
        return $events;
    }
}
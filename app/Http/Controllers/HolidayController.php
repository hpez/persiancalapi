<?php


namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Morilog\Jalali\CalendarUtils;

class HolidayController extends Controller
{
    public function index($type, $y, $m, $d)
    {
        Log::info("Yay");
        $ymd = [$y, $m, $d];
        if ($type == 'gregorian') {
            $ymd = CalendarUtils::toJalali($y, $m, $d);
        }

        $handle = curl_init();

        $url = "https://www.time.ir/fa/event/list/0/$ymd[0]/$ymd[1]/$ymd[2]";

        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($handle);

        curl_close($handle);

        if (strpos($output, 'eventHoliday') !== false) {
            $start = strpos($output, '</span>', strpos($output, 'eventHoliday'));
            $start += 7;
            $end = strpos($output, '<span', $start);
            $cause = substr($output, $start, $end - $start);
            $cause = str_replace("\n", '', $cause);
            $cause = str_replace("\r", '', $cause);
            $cause = str_replace('"', '', $cause);
            $cause = trim($cause);
            return response()->json(['is_holiday' => true, 'cause' => $cause]);
        }

        $greg = CalendarUtils::toGregorian($ymd[0], $ymd[1], $ymd[2]);
        if (Carbon::create($greg[0], $greg[1], $greg[2])->dayOfWeek == 5)
            return response()->json(['is_holiday' => true, 'cause' => 'جمعه']);

        return response()->json(['is_holiday' => false]);
    }
}
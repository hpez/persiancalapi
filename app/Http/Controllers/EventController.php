<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Morilog\Jalali\CalendarUtils;

class EventController extends Controller
{
    public function index($type, $y, $m, $d)
    {
        if ($type == 'gregorian') {
            list($y, $m, $d) = CalendarUtils::toJalali($y, $m, $d);
        }
        $client = new \GuzzleHttp\Client();
        // dd('https://www.time.ir/event/'.$y.'/'.$m.'/'.$d);
        $response = $client->request('GET', 'https://www.time.ir/event/'.$y.'/'.$m.'/'.$d);
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML((string) $response->getBody());
        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query('//div[contains(@class, "SpecialDayEventList_root")]/div[contains(@class, "SpecialDayEventListItem_root")]');
        $events = [];
        $isHoliday = false;
        foreach ($nodes as $node) {
            $spans = $xpath->query('.//span', $node);
            $title = $spans->item(0)?->nodeValue ?? '';
            $date = $spans->item(1)?->nodeValue ?? '';
            $date = trim($date, '[] ');
            $isHoliday |= str_contains($node->attributes->getNamedItem('class')->nodeValue, 'SpecialDayEventListItem_root__holiday__ZdEkM');
            $events[] = [
                'date' => $date,
                'title' => $title,
            ];
        }
        return [
            'isHoliday' => $isHoliday,
            'events' => $events,
        ];
    }
}

<?php

namespace App\Classes;

use DomDocument;
use DomXPath;
use GuzzleHttp\Client;
use Morilog\Jalali\CalendarUtils;

class Calendar
{
    public function getEvents($date)
    {
        $date = date("Y-m-d", strtotime($date));
        $timestamp = strtotime($date);
        list($year, $month, $day) = CalendarUtils::toJalali(date("Y", $timestamp), date("m", $timestamp), date("d", $timestamp));

        if (env('APP_ENV') == 'testing')
            $url = "https://www.persiancalapi.ir/proxy/$year/$month/$day";
        else
            $url = "http://www.time.ir/fa/event/list/0/$year/$month/$day";

        $client = new Client(
            array(
                'curl' => array(CURLOPT_SSL_VERIFYPEER => false),
                'verify' => false
            )
        );
        $response = (string)$client->get($url)->getBody();
        $doc = new DomDocument();
        @$doc->loadHTML('<?xml encoding="UTF-8">' . $response);
        $doc->preserveWhiteSpace = false;
        $xpath = new \DomXPath($doc);
        $elements = $xpath->query("//*[@class='list-unstyled']//li");
        $isHoliday = false;
        $events = [];
        if (date("w", $timestamp) == 5) {
            $isHoliday = true;
            $events[] = [
                'description' => 'جمعه',
                'additional_description' => '',
                'is_religious' => false
            ];
        }
        foreach ($elements as $element) {
            $childs = $element->getElementsByTagName("span");
            $date = $childs->item(0)->nodeValue;
            $additionalDescription = $childs->item(1)->nodeValue;
            $description = str_replace($additionalDescription, "", $element->nodeValue);
            $description = str_replace($date, "", $description);
            $isHoliday |= ($element->hasAttribute("class") and strstr($element->getAttribute('class'), 'eventHoliday'));


            $events[] = [
                'description' => trim($description),
                'additional_description' => trim(preg_replace("/\[|\]/", "", $additionalDescription)),
                'is_religious' => (trim($childs->item(1)->nodeValue) != "" and $childs->item(1)->getElementsByTagName("span")->length == 0)
            ];
        }
        return [
            'is_holiday' => (bool)$isHoliday,
            'events' => $events
        ];
    }
}
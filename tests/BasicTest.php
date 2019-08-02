<?php


namespace Tests;


class BasicTest extends TestCase
{
    public function testJalaliHoliday()
    {
        $handle = curl_init();

        $url = "https://persiancalapi.ir/jalali/1398/01/01";

        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

        $output = curl_exec($handle);

        curl_close($handle);

        $output = json_decode($output);

        $this->assertEquals($output->is_holiday, true);
    }

    public function testJalaliNonHoliday()
    {
        $handle = curl_init();

        $url = "https://persiancalapi.ir/jalali/1398/05/07";

        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

        $output = curl_exec($handle);

        curl_close($handle);

        $output = json_decode($output);

        $this->assertEquals($output->is_holiday, false);
    }

    public function testGregorianHoliday()
    {
        $handle = curl_init();

        $url = "https://persiancalapi.ir/gregorian/2019/08/12";

        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

        $output = curl_exec($handle);

        curl_close($handle);

        $output = json_decode($output);

        $this->assertEquals($output->is_holiday, true);
    }

    public function testGregorianNonHoliday()
    {
        $handle = curl_init();

        $url = "https://persiancalapi.ir/gregorian/2019/08/11";

        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

        $output = curl_exec($handle);

        curl_close($handle);

        $output = json_decode($output);

        $this->assertEquals($output->is_holiday, false);
    }
}
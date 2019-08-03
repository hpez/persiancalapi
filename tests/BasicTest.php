<?php


namespace Tests;


class BasicTest extends TestCase
{
    public function testJalaliHoliday()
    {
        $output = $this->get('/jalali/1398/01/01');

        $this->assertEquals($output->json('is_holiday'), true);
    }

    public function testJalaliNonHoliday()
    {
        $output = $this->get('/jalali/1398/05/07');

        $this->assertEquals($output->json('is_holiday'), false);
    }

    public function testGregorianHoliday()
    {
        $output = $this->get('/gregorian/2019/08/12');

        $this->assertEquals($output->json('is_holiday'), true);
    }

    public function testGregorianNonHoliday()
    {
        $output = $this->get('/gregorian/2019/08/11');

        $this->assertEquals($output->json('is_holiday'), false);
    }
}
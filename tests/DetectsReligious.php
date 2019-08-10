<?php


namespace Tests;


class DetectsReligious extends TestCase
{
    public function testSimpleIsReligious()
    {
        $output = $this->get('jalali/1398/05/21');

        $this->assertEquals($output->json('events')[0]['is_religious'], true);
    }

    public function testForeignIsReligious()
    {
        $output = $this->get('jalali/1398/11/22');

        $this->assertEquals($output->json('events')[1]['is_religious'], false);
    }
}
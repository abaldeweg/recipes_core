<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PlanTest extends WebTestCase
{
    use \Baldeweg\Bundle\ExtraBundle\ApiTestTrait;

    public function testScenario()
    {
        $date = new \DateTime();
        $timestamp = $date->getTimestamp();

        // list
        $request = $this->request('/api/plan/', 'GET');

        $this->assertTrue(isset($request));

        // new
        $request = $this->request('/api/plan/new', 'POST', [], [
            'year' => 2020,
            'week' => 1,
            'name' => 'name-'.$timestamp,
        ]);

        $this->assertEquals('4', count((array) $request));
        $this->assertTrue(isset($request->id));
        $this->assertEquals('2020', $request->year);
        $this->assertEquals('1', $request->week);
        $this->assertEquals('name-'.$timestamp, $request->name);

        $id = $request->id;

        // edit
        $request = $this->request('/api/plan/'.$id, 'PUT', [], [
            'year' => 2020,
            'week' => 1,
            'name' => '1-'.$timestamp,
        ]);

        $this->assertEquals('4', count((array) $request));
        $this->assertTrue(isset($request->id));
        $this->assertEquals('2020', $request->year);
        $this->assertEquals('1', $request->week);
        $this->assertEquals('1-'.$timestamp, $request->name);

        // show
        $request = $this->request('/api/plan/'.$id, 'GET');

        $this->assertEquals('4', count((array) $request));
        $this->assertTrue(isset($request->id));
        $this->assertEquals('2020', $request->year);
        $this->assertEquals('1', $request->week);
        $this->assertEquals('1-'.$timestamp, $request->name);

        // delete
        $request = $this->request('/api/plan/'.$id, 'DELETE');

        $this->assertEquals('DELETED', $request->msg);
    }
}

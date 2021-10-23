<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MenuTest extends WebTestCase
{
    use \Baldeweg\Bundle\ExtraBundle\ApiTestTrait;

    private int $meal;
    private int $plan;

    public function setUp(): void
    {
        $this->buildClient();

        $date = new \DateTime();
        $timestamp = $date->getTimestamp();

        // new meal
        $request = $this->request('/api/meal/new', 'POST', [], [
            'name' => 'name-' . $timestamp,
            'description' => 'description',
            'price' => 1.00,
            'deleted' => false
        ]);

        $this->assertEquals('5', count((array) $request));
        $this->assertTrue(isset($request->id));
        $this->assertEquals('name-' . $timestamp, $request->name);
        $this->assertEquals('description', $request->description);
        $this->assertEquals('1', $request->price);
        $this->assertFalse($request->deleted);

        $this->meal = $request->id;

        // new plan
        $request = $this->request('/api/plan/new', 'POST', [], [
            'year' => 2020,
            'week' => 1,
            'name' => 'name-' . $timestamp,
        ]);

        $this->assertEquals('4', count((array) $request));
        $this->assertTrue(isset($request->id));
        $this->assertEquals('2020', $request->year);
        $this->assertEquals('1', $request->week);
        $this->assertEquals('name-' . $timestamp, $request->name);

        $this->plan = $request->id;
    }

    public function tearDown(): void
    {
        // delete meal
        $request = $this->request('/api/meal/' . $this->meal, 'DELETE');

        $this->assertEquals('DELETED', $request->msg);

        // delete plan
        $request = $this->request('/api/plan/' . $this->plan, 'DELETE');

        $this->assertEquals('DELETED', $request->msg);

        parent::tearDown();
    }

    public function testScenario()
    {
        // list
        $request = $this->request('/api/menu/', 'GET');

        $this->assertTrue(isset($request));

        // new
        $request = $this->request('/api/menu/new', 'POST', [], [
            'meal' => $this->meal,
            'plan' => $this->plan,
            'day' => '1',
            'course' => '1',
        ]);

        $this->assertEquals('5', count((array) $request));
        $this->assertTrue(isset($request->id));
        $this->assertIsInt($request->meal_id);
        $this->assertIsInt($request->plan_id);
        $this->assertEquals('1', $request->day);
        $this->assertEquals('1', $request->course);

        $id = $request->id;

        // edit
        $request = $this->request('/api/menu/' . $id, 'PUT', [], [
            'meal' => $this->meal,
            'plan' => $this->plan,
            'day' => '1',
            'course' => '2',
        ]);

        $this->assertEquals('5', count((array) $request));
        $this->assertTrue(isset($request->id));
        $this->assertIsInt($request->meal_id);
        $this->assertIsInt($request->plan_id);
        $this->assertEquals('1', $request->day);
        $this->assertEquals('2', $request->course);

        // show
        $request = $this->request('/api/menu/' . $id, 'GET');

        $this->assertEquals('5', count((array) $request));
        $this->assertTrue(isset($request->id));
        $this->assertIsInt($request->meal_id);
        $this->assertIsInt($request->plan_id);
        $this->assertEquals('1', $request->day);
        $this->assertEquals('2', $request->course);

        // delete
        $request = $this->request('/api/menu/' . $id, 'DELETE');

        $this->assertEquals('DELETED', $request->msg);
    }
}

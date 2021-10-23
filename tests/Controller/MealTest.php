<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MealTest extends WebTestCase
{
    use \Baldeweg\Bundle\ExtraBundle\ApiTestTrait;

    public function testScenario()
    {
        $date = new \DateTime();
        $timestamp = $date->getTimestamp();

        // list
        $request = $this->request('/api/meal/', 'GET');

        $this->assertTrue(isset($request));

        // new
        $request = $this->request('/api/meal/new', 'POST', [], [
            'name' => 'name-' . $timestamp,
            'description' => 'description',
            'price' => 1.50,
            'deleted' => false
        ]);

        $this->assertEquals('5', count((array) $request));
        $this->assertTrue(isset($request->id));
        $this->assertEquals('name-' . $timestamp, $request->name);
        $this->assertEquals('description', $request->description);
        $this->assertEquals('1.5', $request->price);
        $this->assertFalse($request->deleted);

        $id = $request->id;

        // edit
        $request = $this->request('/api/meal/' . $id, 'PUT', [], [
            'name' => '1-' . $timestamp,
            'description' => 'description',
            'price' => 1.50,
            'deleted' => false
        ]);

        $this->assertEquals('5', count((array) $request));
        $this->assertTrue(isset($request->id));
        $this->assertEquals('1-' . $timestamp, $request->name);
        $this->assertEquals('description', $request->description);
        $this->assertEquals('1.5', $request->price);
        $this->assertFalse($request->deleted);

        // show
        $request = $this->request('/api/meal/' . $id, 'GET');

        $this->assertEquals('5', count((array) $request));
        $this->assertTrue(isset($request->id));
        $this->assertEquals('1-' . $timestamp, $request->name);
        $this->assertEquals('description', $request->description);
        $this->assertEquals('1.50', $request->price);
        $this->assertFalse($request->deleted);

        // delete
        $request = $this->request('/api/meal/' . $id, 'DELETE');

        $this->assertEquals('DELETED', $request->msg);
    }
}

<?php

namespace Phossa\Event;

use Phossa\Event\Interfaces\EventQueueInterface;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-10-21 at 08:50:05.
 */
class EventQueueTest
    extends \PHPUnit_Framework_TestCase
{

    /**
     * @var EventQueue
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new EventQueue;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * @covers Phossa\Event\EventQueue::count
     */
    public function testCount()
    {
        // 0
        $this->assertTrue(0 === $this->object->count());

        // 1
        $this->object->insert([$this->object, 'count'], 10);
        $this->assertTrue(1 === $this->object->count());
    }

    /**
     * @covers Phossa\Event\EventQueue::getIterator
     */
    public function testGetIterator1()
    {
        $it = $this->object->getIterator();
        $this->assertTrue($it instanceof \SplPriorityQueue);
    }

    /**
     * @covers Phossa\Event\EventQueue::getIterator
     */
    public function testGetIterator2()
    {
        $callable = [$this->object, 'count'];
        $this->object->insert($callable, 10);
        foreach ($this->object as $data) {
            $this->assertTrue($callable === $data['data']);
            $this->assertTrue(10 === $data['priority']);
        }
    }

    /**
     * @covers Phossa\Event\EventQueue::insert
     */
    public function testInsert1()
    {
        $this->object->insert([$this->object, 'count'], 10);
    }


    /**
     * insert non callable
     *
     * @covers Phossa\Event\EventQueue::insert
     * @expectedException PHPUnit_Framework_Error
     */
    public function testInsert2()
    {
        $this->object->insert('wow', 10);
    }

    /**
     * @covers Phossa\Event\EventQueue::remove
     */
    public function testRemove()
    {
        $callable = [$this->object, 'count'];
        $this->object->insert($callable, 10);
        $this->assertTrue(1 === $this->object->count());
        $this->object->remove($callable);
        $this->assertTrue(0 === $this->object->count());
    }

    /**
     * @covers Phossa\Event\EventQueue::flush
     */
    public function testFlush()
    {
        $callable = [$this->object, 'count'];
        $this->object->insert($callable, 10);
        $this->assertTrue(1 === $this->object->count());
        $this->object->flush();
        $this->assertTrue(0 === $this->object->count());
    }

    /**
     * @covers Phossa\Event\EventQueue::combine
     */
    public function testCombine()
    {
        // callables
        $call1 = [$this->object, 'count'];
        $call2 = [$this->object, 'count'];

        // queue 1
        $this->object->insert($call1, 10);

        // queue 2
        $que2 = new EventQueue();
        $que2->insert($call2, 20);


        $que3 = $this->object->combine($que2);

        // type right
        $this->assertTrue($que3 instanceof EventQueueInterface);

        // count right
        $this->assertTrue(2 === $que3->count());
    }
}

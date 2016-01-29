<?php

namespace Phossa\Event;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-10-21 at 08:50:06.
 */
class EventManagerTest
    extends \PHPUnit_Framework_TestCase
{

    /**
     * @var EventManager
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new EventManager;
        require_once __DIR__ . '/Listener.php';
        require_once __DIR__ . '/ListenerStatic.php';
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * @covers Phossa\Event\EventManager::processEvent
     */
    public function testProcessEvent1()
    {
        $l = new Listener();
        $this->object->attachListener($l);

        /*
         * 'evtTest4' => [
         *      [ 'testX', 70 ],
         *      [ 'testY', 40 ],
         *      [ 'testZ', 30 ]
         *  ]
         */
        $e1 = new Event('evtTest4', $this);
        $this->object->processEvent($e1);

        $this->assertArrayHasKey(
            'testX', $e1->getProperties()
        );
        // event stopped after testY() in Listener.php
        $this->assertArrayHasKey(
            'testY', $e1->getProperties()
        );
        $this->assertArrayNotHasKey(
            'testZ', $e1->getProperties()
        );

        $this->assertEquals(5, count($e1->getProperties()));
    }

    /**
     * test static listener
     *
     * @covers Phossa\Event\EventManager::processEvent
     */
    public function testProcessEvent2()
    {
        // event manager attach to a static listener
        $this->object->attachListener('Phossa\\Event\\ListenerStatic');

        $e1 = new Event('evtTest3', $this);
        $this->object->processEvent($e1);
        $this->assertArrayHasKey(
            's_testA', $e1->getProperties()
        );
        $this->assertArrayHasKey(
            's_testB', $e1->getProperties()
        );
        $this->assertEquals(2, count($e1->getProperties()));
    }

    /**
     * test callback in processEvent
     *
     * @covers Phossa\Event\EventManager::processEvent
     */
    public function testProcessEvent3()
    {
        $l = new Listener();
        $this->object->attachListener($l);

        // normal
        $e1 = new Event('evtTest3');
        $this->object->processEvent($e1, function() {
            return true;
        });
        $this->assertArrayHasKey(
            'testA', $e1->getProperties()
        );
        $this->assertArrayHasKey(
            'testB', $e1->getProperties()
        );
        $this->assertEquals(5, count($e1->getProperties()));

        // stopped by callback
        $e2 = new Event('evtTest3');
        $this->object->processEvent($e2, function() {
            return false;
        });
        $this->assertArrayHasKey(
            'testA', $e2->getProperties()
        );
        // stopped after first run
        $this->assertArrayNotHasKey(
            'testB', $e2->getProperties()
        );
        $this->assertEquals(1, count($e2->getProperties()));
    }

    /**
     * attach a listner object
     *
     * @covers Phossa\Event\EventManager::attachListener
     */
    public function testAttachListener1()
    {
        // the right object
        $l = new Listener();
        $this->object->attachListener($l);
    }

    /**
     * not the right listener object
     *
     * @covers Phossa\Event\EventManager::attachListener
     * @expectedException Phossa\Event\Exception\InvalidArgumentException
     * @expectedExceptionCode Phossa\Event\Message\Message::INVALID_EVENT_LISTENER
     */
    public function testAttachListener2()
    {
        // the wrong object
        $o = new \stdClass();
        $this->object->attachListener($o);
    }

    /**
     * attach a right static class
     *
     * @covers Phossa\Event\EventManager::attachListener
     */
    public function testAttachListener3()
    {
        // attach static class
        $this->object->attachListener('Phossa\\Event\\ListenerStatic');
    }

    /**
     * attach a callable directly
     *
     * @covers Phossa\Event\EventManager::attachListener
     */
    public function testAttachListener4()
    {
        // attach a callable
        $this->object->attachListener(function(Event $evt) {
            $evt->setProperty('xxx', 'bingo');
            return 10;
        }, 'bingoEvent');

        $evt = new Event('bingoEvent');
        $this->object->processEvent($evt);
        $this->assertArrayHasKey('xxx', $evt->getProperties());
    }

    /**
     * @covers Phossa\Event\EventManager::detachListener
     */
    public function testDetachListener1()
    {
        $l = new Listener();
        $this->object->attachListener($l);

        // detach one event
        $this->assertTrue($this->object->hasEventQueue('evtTest3'));
        $this->object->detachListener($l, 'evtTest3');
        $this->assertTrue($this->object->hasEventQueue('evtTest3') === false);

        // the '*'
        $this->assertTrue($this->object->hasEventQueue('*'));
        $this->object->detachListener($l, '*');
        $this->assertTrue($this->object->hasEventQueue('*') === false);

        // detach all events
        $this->object->detachListener($l);
        $this->assertTrue(0 === count($this->object->getEventNames()));
    }

    /**
     * detach a non listener object
     *
     * @covers Phossa\Event\EventManager::detachListener
     * @expectedException Phossa\Event\Exception\InvalidArgumentException
     * @expectedExceptionCode Phossa\Event\Message\Message::INVALID_EVENT_LISTENER
     */
    public function testDetachListener2()
    {
        $l = new \stdClass();
        $this->object->detachListener($l);
    }

    /**
     * detach static listener
     *
     * @covers Phossa\Event\EventManager::detachListener
     */
    public function testDetachListener3()
    {
        // detach static class
        $class = 'Phossa\\Event\\ListenerStatic';
        $this->object->attachListener($class);
        $this->assertTrue(3 === count($this->object->getEventNames()));
        $this->object->detachListener($class);
        $this->assertTrue(0 === count($this->object->getEventNames()));
    }

    /**
     * detach a callable directly
     *
     * @covers Phossa\Event\EventManager::detachListener
     */
    public function testDetachListener4()
    {
        $callable = function(Event $evt) {
            $evt->setProperty('xxx', 'bingo');
            return 10;
        };

        // detach a callable directly
        $this->object->attachListener($callable, 'xSpecialEvent');
        $this->assertTrue(1 === count($this->object->getEventNames()));
        $this->object->detachListener($callable);
        $this->assertTrue(0 === count($this->object->getEventNames()));
    }

    /**
     * @covers Phossa\Event\EventManager::hasEventQueue
     */
    public function testHasEventQueue()
    {
        $l = new Listener();
        $this->object->attachListener($l);

        // check attached event
        $this->assertTrue($this->object->hasEventQueue('evtTest2'));

        $this->assertTrue(false === $this->object->hasEventQueue('evtTestX'));
    }

    /**
     * @covers Phossa\Event\EventManager::getEventQueue
     */
    public function testGetEventQueue()
    {
        $l = new Listener();
        $this->object->attachListener($l);

        // check attached event
        $q1 = $this->object->getEventQueue('evtTest2');
        foreach ($q1 as $data) {
            $this->assertTrue(20 === $data['priority']);
            $this->assertTrue([$l, 'testD'] === $data['data']);
        }

        // attach static class again
        $this->object->attachListener('Phossa\\Event\\ListenerStatic');
        $q2 = $this->object->getEventQueue('evtTest2');

        // now has 2 callable in the queue
        $this->assertTrue(2 === $q2->count());
    }

    /**
     * @covers Phossa\Event\EventManager::clearEventQueue
     */
    public function testClearEventQueue()
    {
        $l = new Listener();
        $this->object->attachListener($l);

        $this->assertTrue($this->object->hasEventQueue('evtTest3'));
        $this->object->clearEventQueue('evtTest3');
        $this->assertTrue($this->object->hasEventQueue('evtTest3') === false);
    }

    /**
     * @covers Phossa\Event\EventManager::getEventNames
     */
    public function testGetEventNames()
    {
        $l = new Listener();
        $this->object->attachListener($l);

        // check attached event
        $evts = $this->object->getEventNames();
        $this->assertTrue($evts === array_keys($l->getEventsListening()));
    }
}

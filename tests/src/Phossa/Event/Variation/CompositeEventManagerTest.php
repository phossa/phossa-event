<?php

namespace Phossa\Event\Variation;

use Phossa\Event\EventManager;
use Phossa\Event\Listener;
use Phossa\Event\Event;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-10-22 at 08:09:39.
 */
class CompositeEventManagerTest
    extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CompositeEventManager
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new CompositeEventManager;
        require_once __DIR__ . '/../Listener.php';
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * @covers Phossa\Event\Variation\CompositeEventManager::setOtherManager
     */
    public function testSetOtherManager()
    {
        $global = new EventManager();
        $this->object->setOtherManager('global', $global);

        $pool = $this->object->getOtherManagers();
        $this->assertTrue($global === $pool['global']);
    }

    /**
     * @covers Phossa\Event\Variation\CompositeEventManager::unsetOtherManager
     */
    public function testUnsetOtherManager()
    {
        $global = new EventManager();
        $this->object->setOtherManager('global', $global);

        $pool = $this->object->getOtherManagers();
        $this->assertTrue($global === $pool['global']);

        $this->object->unsetOtherManager('global');
        $pool2 = $this->object->getOtherManagers();
        $this->assertTrue(0 === count($pool2));
    }

    /**
     * @covers Phossa\Event\Variation\CompositeEventManager::processEvent
     */
    public function testProcessEvent()
    {
        $l = new Listener();

        // global event manager with attached events
        $global = new EventManager();
        $this->object->setOtherManager('global', $global);
        $global->attachListener($l);

        // trigger event by $this manager
        $evt = new Event('evtTest4');
        $this->object->processEvent($evt, function() {
            return true;
        });

        $this->assertArrayHasKey('testX', $evt->getProperties());
        $this->assertArrayHasKey('testY', $evt->getProperties());
        $this->assertArrayHasKey('wow', $evt->getProperties());
        $this->assertArrayHasKey('bingo', $evt->getProperties());
        $this->assertArrayHasKey('bingo2', $evt->getProperties());
        // terminated after 'testY'
        $this->assertArrayNotHasKey('testZ', $evt->getProperties());
    }
}

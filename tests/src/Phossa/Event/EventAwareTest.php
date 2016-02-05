<?php
namespace Phossa\Event;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-10-21 at 08:50:05.
 */
class EventAwareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EventAwareInterface
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        require_once __DIR__.'/EventAware.php';
        $this->object = new EventAware;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * set event
     * @covers Phossa\Event\EventAware::setEventManager
     */
    public function testSetEventManager()
    {
        $this->object->setEventManager(
            new EventManager()
        );
    }

    /**
     * @covers Phossa\Event\EventAware::triggerEvent
     */
    public function testTriggerEvent()
    {
        $manager = new EventManager();
        require_once __DIR__.'/Listener.php';
        require_once __DIR__.'/ListenerStatic.php';
        $manager->attachListener(new Listener());
        $manager->attachListener('Phossa\\Event\\ListenerStatic');

        // set event manager
        $this->object->setEventManager(
            $manager,
            new Event('prototype')
        );

        $e1 = $this->object->triggerEvent('evtTest4');
        $this->assertArrayHasKey('testY', $e1->getProperties());
        // stopped after 'testY'
        $this->assertArrayNotHasKey('testZ', $e1->getProperties());

        $this->assertEquals(5, count($e1->getProperties()));

        // both object/static class has 'evtTest2'
        $e2 = $this->object->triggerEvent('evtTest2');
        $this->assertArrayHasKey('testD', $e2->getProperties());
        $this->assertArrayHasKey('s_testDD', $e2->getProperties());

        $this->assertEquals(5, count($e2->getProperties()));
    }
}

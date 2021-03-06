<?php
namespace Phossa\Event;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2016-01-28 at 20:26:57.
 */
class EventDispatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EventDispatcher
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new EventDispatcher;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Phossa\Event\EventDispatcher::__callStatic
     * @todo   Implement __callStatic
     */
    public function testCallStatic()
    {

    }

    /**
     * @covers Phossa\Event\EventDispatcher::on
     */
    public function testOn()
    {
        $this->object->on('start', function($evt) {
            echo "START";
        });

        // name globbing, priority tse
        $this->object->on('s*', function($evt) {
            echo "*";
        }, 60);

        $this->object->trigger('start');
        $this->object->trigger('start');
        $this->expectOutputString('*START*START');
    }

    /**
     * @covers Phossa\Event\EventDispatcher::one
     */
    public function testOne()
    {
        $this->object->one('start', function($evt) {
            echo "START";
        });
        $this->object->trigger('start');
        $this->object->trigger('start');
        $this->expectOutputString('START');
    }

    /**
     * @covers Phossa\Event\EventDispatcher::many
     */
    public function testMany()
    {
        $this->object->many('start', 3, function($evt) {
            echo "START";
        });
        $this->object->trigger('start');
        $this->object->trigger('start');
        $this->object->trigger('start');
        $this->object->trigger('start');
        $this->object->trigger('start');
        $this->expectOutputString('STARTSTARTSTART');
    }

    /**
     * @covers Phossa\Event\EventDispatcher::has
     */
    public function testHas()
    {
        $this->object->many('start', 3, function($evt) {
            echo "START";
        });
        $this->assertTrue($this->object->has('start'));
        $this->assertFalse($this->object->has('start2'));
    }

    /**
     * @covers Phossa\Event\EventDispatcher::off
     */
    public function testOff()
    {
        $callable = function($evt) {
            echo "START";
        };
        $this->object->many('start', 3, $callable);
        $this->object->on('end', $callable);
        $this->assertTrue($this->object->has('start'));
        $this->object->off('start');
        $this->assertFalse($this->object->has('start'));

        $this->assertTrue($this->object->has('end'));
        $this->object->off();
        $this->assertFalse($this->object->has('end'));
    }

    /**
     * @covers Phossa\Event\EventDispatcher::error
     */
    public function testError()
    {
        $this->object->error(function() {
           echo "ERROR";
           return true;
        });
        trigger_error("wow");
        $this->expectOutputString("ERROR");
    }

    /**
     * @covers Phossa\Event\EventDispatcher::ready
     */
    public function testReady()
    {
        // can not test this?
        $this->object->ready(function() {
            echo "WOW";
        });
    }

    /**
     * @covers Phossa\Event\EventDispatcher::trigger
     */
    public function testTrigger()
    {
        $this->object->on('start', function($evt) {
            echo "START";
            if ($evt->hasProperty('user')) {
                echo $evt->getProperty('user');
            }
            return 'X';
        });
        $this->object->trigger('start');
        $res = $this->object->trigger('start', [ 'user' => 'phossa']);
        $this->expectOutputString('STARTSTARTphossa');
        $this->assertEquals(['X', 'X'], $res);
    }

    /**
     * @covers Phossa\Event\EventDispatcher::isTriggered
     */
    public function testIsTriggered()
    {
        $this->object->on('start', function($evt) {
            echo "START";
            return 'X';
        });
        $this->assertFalse($this->object->isTriggered('start'));
        $this->object->trigger('start');
        $this->assertTrue($this->object->isTriggered('start'));
    }

    /**
     * @covers Phossa\Event\EventDispatcher::set
     * @covers Phossa\Event\EventDispatcher::get
     */
    public function testSet()
    {
        $dispatcher = $this->object;
        EventDispatcher::set($dispatcher);
        $this->assertTrue($dispatcher === EventDispatcher::get());
    }
}

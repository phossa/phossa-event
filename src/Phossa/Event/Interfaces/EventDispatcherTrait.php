<?php
/**
 * Phossa Project
 *
 * PHP version 5.4
 *
 * @category  Library
 * @package   Phossa\Event
 * @copyright 2015 phossa.com
 * @license   http://mit-license.org/ MIT License
 * @link      http://www.phossa.com/
 */
/*# declare(strict_types=1); */

namespace Phossa\Event\Interfaces;

/**
 * Implementing EventDispatcherInterface
 *
 * @trait
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.3
 * @since   1.0.3 added
 */
trait EventDispatcherTrait
{
    /**
     * slave event manager
     *
     * @var    EventManagerInterface
     * @access protected
     */
    protected $event_manager;

    /**
     * event prototype
     *
     * @var    EventInterface
     * @access protected
     */
    protected $event_proto;

    /**
     * triggered events log
     *
     * @var    array
     * @access protected
     */
    protected $triggered = [];

    /**
     * {@inheritDoc}
     */
    public function on(
        /*# string */ $eventName,
        callable $callable,
        /*# int */ $priority = 50
    ) {
        $this->event_manager
             ->attachListener($callable, $eventName, $priority);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function attach($listener)/*# : EventManagerInterface */
    {
        $this->event_manager->attachListener($listener);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function one(
        /*# string */ $eventName,
        callable $callable,
        /*# int */ $priority = 50
    ) {
        return $this->many($eventName, 1, $callable, $priority);
    }

    /**
     * {@inheritDoc}
     */
    public function many(
        /*# string */ $eventName,
        /*# int */ $times,
        callable $callable,
        /*# int */ $priority = 50
    ) {
        // generate a new callable
        $new = function (EventInterface $event) use ($callable, $times) {
            static $once = 0;

            $res = null;
            if ($once < $times) {
                $res  = call_user_func($callable, $event);
                ++$once;
            }
            return $res;
        };

        $this->event_manager
             ->attachListener($new, $eventName, $priority);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function has(
        /*# string */ $eventName
    )/*# : bool */ {
        return $this->event_manager->hasEventQueue($eventName);
    }

    /**
     * {@inheritDoc}
     */
    public function off(
        /*# string */ $eventName = '',
        callable $callable = null
    ) {
        $this->event_manager->detachListener($callable, $eventName);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function error(callable $callable, $errorTypes = E_ALL)
    {
        set_error_handler($callable, $errorTypes);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function ready(callable $callable)
    {
        // make sure the last one registered
        $new = function () use ($callable) {
            register_shutdown_function($callable);
        };

        // when script ends it will execute $new which in turn register
        // the $callable which becomes the last one registered and executed
        register_shutdown_function($new);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function trigger(
        /*# string */ $eventName,
        array $data = [],
        /*# bool */ $persistent = true
    )/*# : array */ {
        // use the persistent/old $event
        if ($persistent && $this->isTriggered($eventName)) {
            /* @var $event EventInterface */
            $event = $this->triggered[$eventName];

            // merget with new properties
            $event->setProperties($data, true);

        // create a new $event
        } else {
            // clone the prototype and config the new event
            $event = clone $this->event_proto;
            $event($eventName, null, $data);

            // mark as triggered
            $this->triggered[$event->getName()] = $event;
        }

        // trigger the event
        $this->event_manager->processEvent($event);

        return $event->getResults();
    }

    /**
     * {@inheritDoc}
     */
    public function isTriggered(
        /*# string */ $eventName
    )/*# : bool */ {
        // has to be string name
        if (!is_scalar($eventName)) {
            return false;
        }

        // test
        return isset($this->triggered[(string) $eventName]);
    }
}

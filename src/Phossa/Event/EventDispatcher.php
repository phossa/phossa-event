<?php
/*
 * Phossa Project
 *
 * @see         http://www.phossa.com/
 * @copyright   Copyright (c) 2015 phossa.com
 * @license     http://mit-license.org/ MIT License
 */
/*# declare(strict_types=1); */

namespace Phossa\Event;

use Phossa\Event\Exception;
use Phossa\Event\Message\Message;
use Phossa\Event\Interfaces\EventDispatcherInterface;

/**
 * A basic implementation of EventDispatcherInterface
 *
 * e.g.
 * <code>
 *     $dispatcher = new EventDispatcher();
 *
 *     // bind event 'user.login' to a callable
 *     $dispatcher->on('user.login', function(Event $evt) {
 *          ...
 *     });
 *
 *     // trigger the 'user.login' event
 *     $dispatcher->trigger('user.login', [ 'user' => $user ]);
 * </code>
 *
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\Interfaces\EventDispatcherInterface
 * @version 1.0.2
 * @since   1.0.2 added
 */
class EventDispatcher implements EventDispatcherInterface
{
    /**
     * inner event manager
     *
     * @var    EventManagerInterface
     * @access protected
     */
    protected $event_manager;

    /**
     * event prototype
     *
     * @var    callable
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
     * dispatcher to be used statically
     *
     * @var    EventDispatcherInterface[]
     * @access private
     */
    private static $instances = [];

    /**
     * Constructor, setup event related stuff
     *
     * Optionally inject the event manager object. also, may provides a event
     * prototype if you are not using the default `Event` class
     *
     * <code>
     *     $dispatcher = new EventDispatcher(
     *         $globalEventManager,
     *         new MyEvent('prototype')
     *     );
     * </code>
     *
     * @param  Interfaces\EventManagerInterface $eventManager
     * @param  Interfaces\EventInterface $eventPrototype
     * @access public
     * @api
     */
    public function __construct(
        Interfaces\EventManagerInterface $eventManager = null,
        Interfaces\EventInterface $eventPrototype = null
    ) {
        // set event manager
        $this->event_manager = $eventManager ?: new EventManager();

        // set event prototype
        $this->event_proto   = $eventPrototype ?: new Event('prototype');
    }

    /**
     * Provides a static interface for all methods
     *
     * @param  EventDispatcherInterface $dispatcher dispatcher to use
     * @return mixed
     * @access public
     * @static
     */
    public static function __callStatic($name, $arguments)
    {
        $dispatcher = static::get();
        if (method_exists($dispatcher, $name)) {
            return call_user_func_array([$dispatcher, $name], $arguments);
        }

        throw new Exception\BadMethodCallException(
            Message::get(
                Message::METHOD_NOT_FOUND,
                get_called_class(),
                $name
            ),
            Message::METHOD_NOT_FOUND
        );
    }

    /**
     * {@inheritDoc}
     */
    public function on(
        /*# string */ $eventName,
        callable $callable,
        /*# int */ $priority = 50
    ) {
        return $this->event_manager->attachListener(
            $callable, $eventName, $priority
        );
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
        $new = function(Event $event) use ($callable, $times) {
            static $once = 0;

            $res = null;
            if ($once < $times) {
                $res  = call_user_func($callable, $event);
                ++$once;
            }
            return $res;
        };

        return $this->event_manager->attachListener(
            $new, $eventName, $priority
        );
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
        return $this->event_manager->detachListener($callable, $eventName);
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
        $new = function() use ($callable) {
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
            /* @var $event Interfaces\EventInterface */
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
        if  (!is_scalar($eventName)) return false;

        // test
        return isset($this->triggered[(string) $eventName]);
    }

    /**
     * {@inheritDoc}
     */
    public static function set(
        EventDispatcherInterface $dispatcher
    ) {
        $class = get_called_class();
        self::$instances[$class] = $dispatcher;
    }

    /**
     * {@inheritDoc}
     */
    public static function get()/*# : EventDispatcherInterface */
    {
        $class = get_called_class();
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static();
        }
        return self::$instances[$class];
    }
}

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

use Phossa\Event\Message\Message;

/**
 * A basic implementation of EventManagerInterface
 *
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     Phossa\Event\EventManagerInterface
 * @version 1.0.0
 * @since   1.0.0 added
 */
class EventManager implements EventManagerInterface
{
    /**
     * Events pool
     *
     * @var    EventQueueInterface[]
     * @type   EventQueueInterface[]
     * @access protected
     */
    protected $events = [];

    /**
     * {@inheritDoc}
     */
    public function processEvent(
        EventInterface $event,
        callable $callback = null
    )/*# : EventInterface */ {
        $eventName = $event->getName();

        // event queue is empty or not found
        if (!$this->hasEventQueue($eventName)) return $event;

        // get queue from $this matching $eventName
        $queue = $this->getEventQueue($eventName);

        // run thru the queue
        return $this->runEventQueue($event, $queue, $callback);
    }

    /**
     * {@inheritDoc}
     */
    public function runEventQueue(
        EventInterface $event,
        EventQueueInterface $queue,
        callable $callback = null
    )/*# : EventInterface */ {
        try {
            foreach($queue as $data) {
                // execute callable from the queue
                $res = call_user_func($data['data'], $event);

                // break if event stopped
                if ($event->isPropagationStopped()) break;

                // break if $callback return false
                if ($callback &&
                    call_user_func($callback, $event, $res) === false
                ) {
                    break;
                }
            }
            return $event;

        } catch (\Exception $e) {

            // rethrow any exception caught
            throw new Exception\RuntimeException(
                Message::get(Message::CALLABLE_RUNTIME, $e->getMessage()),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function attachListener(
        $listener,
        /*# string */ $eventName = '',
        /*# int */ $priority = 50
    )/*# : EventManagerInterface */ {
        // get listener's events
        $evts = $this->getListenerEvents($listener, $eventName);

        // attach events
        foreach($evts as $name => $callable) {
            $this->attachIt($name, $listener, $callable, (int) $priority);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function detachListener(
        $listener,
        /*# string */ $eventName = ''
    )/*# : EventManagerInterface */ {
        // get listener's events
        $evts = $this->getListenerEvents($listener, $eventName);

        // detach events
        foreach($evts as $name => $callable) {
            $this->detachIt($name, $listener, $callable);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function hasEventQueue(
        /*# string */ $eventName
    )/*# : bool */ {
        if (isset($this->events[$eventName]) &&
            $this->events[$eventName]->count()
        ) {
            return true;
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getEventQueue(
        /*# string */ $eventName
    )/*# : EventQueueInterface */ {
        if (isset($this->events[$eventName])) {
            return $this->events[$eventName];
        }
        throw new Exception\NotFoundException(
            Message::get(
                Message::CALLABLE_NOT_FOUND,
                $eventName
            ),
            Message::CALLABLE_NOT_FOUND
        );
    }

    /**
     * {@inheritDoc}
     */
    public function clearEventQueue(
        /*# string */ $eventName
    )/*# : EventManagerInterface */ {
        if (isset($this->events[$eventName])) {
            unset($this->events[$eventName]);
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getEventNames()/*# : array */
    {
        return array_keys($this->events);
    }

    /**
     * Test if it is a valid listener
     *
     * @param  mixed $listener object or static class name
     * @return bool
     * @access protected
     */
    protected function isListener($listener)/*# : bool */
    {
        // object
        if (is_object($listener) &&
            $listener instanceof EventListenerInterface) {
            return true;
        }

        // static class name
        if (is_string($listener) &&
            is_a(
                $listener,
                '\\Phossa\\Event\\EventListenerStaticInterface',
                true
            )) {
            return true;
        }

        return false;
    }

    /**
     * Get listener events array
     *
     * @param  mixed $listener listener object or static classname
     * @param  string $eventName event name or '' for all events
     * @return array
     * @throws Exception\InvalidArgumentException
     *         if $listener not a valid listener
     * @access protected
     */
    protected function getListenerEvents(
        $listener,
        /*# string */ $eventName = ''
    )/*# : array */ {
        // check listener
        if (!$this->isListener($listener)) {
            throw new Exception\InvalidArgumentException(
                Message::get(
                    Message::INVALID_EVENT_LISTENER,
                    is_object($listener) ?
                        get_class($listener) :
                        (string) $listener
                ),
                Message::INVALID_EVENT_LISTENER
            );
        }

        // get listener's listening event definition array
        if (is_object($listener)) {
            /* @var $listener EventListenerInterface */
            $evts = $listener->getEventsListening();
        } else {
            /* @var $listener EventListenerStaticInterface */
            $evts = $listener::getEventsListening();
        }

        if ($eventName != '') {
            $names = array_keys($evts);
            foreach($names as $n) {
                if ($n !== $eventName) unset($evts[$n]);
            }
        }

        return $evts;
    }

    /**
     * Returns an array of callables
     *
     * @param  mixed $listener listener object or static class name
     * @param  mixed $callable user defined callables
     * @param  int $priority priority
     * @return array
     * @access protected
     */
    protected function makeCallables(
        $listener,
        $callable,
        /*# int */ $priority
    )/*# : array */ {
        $result = [];
        if (is_array($callable)) {
            // eventName2 => array('method2', 20)
            if (isset($callable[1]) && is_int($callable[1])) {
                $priority = $callable[1];
                $xc = $callable[0];

            // eventName3 => array(['method3', 70], 'method4', ...)
            } else {
                foreach($callable as $cc) {
                    $result = array_merge(
                        $result,
                        $this->makeCallables($listener, $cc, $priority)
                    );
                }
                return $result;
            }

        } elseif (is_string($callable)) {
            // eventName1 => 'method1'
            $xc = $callable;
        }

        if (isset($xc)) {
            if (!is_callable($xc)) $xc = array($listener, $xc);
            if (is_callable($xc)) {
                $result[] = [ $xc, $priority ];
            }
        }
        return $result;
    }

    /**
     * Attach callable to event queque
     *
     * @param  string $eventName event name
     * @param  mixed $listener listener object or static class name
     * @param  mixed $callable string | array
     * @param  int $priority priority integer
     * @return void
     * @throws Exception\InvalidArgumentException
     *         if callable is the wrong format
     * @access protected
     */
    protected function attachIt(
        /*# string */ $eventName,
        $listener,
        $callable,
        /*# int */ $priority
    ) {
        // get named event queue
        if (isset($this->events[$eventName])) {
            $q = $this->events[$eventName];
        } else {
            $q = new EventQueue();
            $this->events[$eventName] = $q;
        }

        $xc = $this->makeCallables($listener, $callable, (int) $priority);

        if (empty($xc)) {
            throw new Exception\InvalidArgumentException(
                Message::get(Message::INVALID_EVENT_CALLABLE, $eventName),
                Message::INVALID_EVENT_CALLABLE
            );
        } else {
            foreach($xc as $c) {
                $q->insert($c[0], $c[1]);
            }
        }
    }

    /**
     * Detach callable to event queque
     *
     * @param  string $eventName event name
     * @param  mixed $listener listener object or static class name
     * @param  mixed $callable string | array
     * @return void
     * @throws Exception\InvalidArgumentException
     *         if callable is the wrong format
     * @access protected
     */
    protected function detachIt(
        /*# string */ $eventName,
        $listener,
        $callable
    ) {
        // not set
        if (!$this->hasEventQueue($eventName)) return;

        // get event queue
        $q = $this->getEventQueue($eventName);

        // construct callable
        $xc = $this->makeCallables($listener, $callable, 50);

        if (empty($xc)) {
            throw new Exception\InvalidArgumentException(
                Message::get(Message::INVALID_EVENT_CALLABLE, $eventName),
                Message::INVALID_EVENT_CALLABLE
            );
        } else {
            foreach($xc as $c) {
                $q->remove($c[0]);
            }
        }

        // clear queue if empty
        if ($q->count() === 0) $this->clearEventQueue($eventName);
    }
}

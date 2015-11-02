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

/**
 * EventManager Interface
 *
 * No restrictions will be forced upon the event manager yet. It can be a
 * singleton, a local event manager, or a child process event manager ...
 *
 * @interface
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
interface EventManagerInterface
{
    /**
     * Fire up an event and process it by listeners
     *
     * Retrieve all the callables attached to this event from THIS MANAGER and
     * execute them in order. Return the event if ends OR event is stopped OR
     * the optional $callback returns false.
     *
     * The $callback defined as `function($event, $response) {}`, where
     * $reponse is the return value from previous event handler.
     *
     * @param  EventInterface $event the event
     * @param  callable $callback (optional) a callback returns bool
     * @return EventInterface
     * @throws Exception\RuntimeException
     *         rethrow any exception catched as RuntimeException
     * @access public
     * @api
     */
    public function processEvent(
        EventInterface $event,
        callable $callback = null
    )/*# : EventInterface */;

    /**
     * Run an event with the provided queue
     *
     * The $callback defined as `function($event, $response) {}`, where
     * $reponse is the return value from previous event handler.
     *
     * @param  EventInterface $event
     * @param  EventQueueInterface $queue
     * @param  callable $callback (optional) a callback returns bool
     * @return EventInterface
     * @throws Exception\RuntimeException
     *         rethrow any exception catched as RuntimeException
     * @access public
     * @api
     */
    public function runEventQueue(
        EventInterface $event,
        EventQueueInterface $queue,
        callable $callback = null
    )/*# : EventInterface */;

    /**
     * Attach a listener with a specific event or all its events
     *
     * @param  mixed $listener object or static class name
     * @param  string $eventName event name or '' for all its events
     * @param  int $priority priority level, bigger one has high priority
     * @return EventManagerInterface $this
     * @throws Exception\InvalidArgumentException
     *         if $listener not right, or format of callable not right
     * @access public
     * @api
     */
    public function attachListener(
        $listener,
        /*# string */ $eventName = '',
        /*# int */ $priority = 50
    )/*# : EventManagerInterface */;

    /**
     * Detach a listener from specific event or all of it attached
     *
     * @param  mixed $listener object or static class name
     * @param  string $eventName event name or '' for all its attached events
     * @return EventManagerInterface $this
     * @throws Exception\InvalidArgumentException
     *         if $listener not right
     * @access public
     * @api
     */
    public function detachListener(
        $listener,
        /*# string */ $eventName = ''
    )/*# : EventManagerInterface */;

    /**
     * Check event queue for $eventName
     *
     * returns true if exists AND not empty
     *
     * @param  string $eventName the event name
     * @return bool
     * @access public
     * @api
     */
    public function hasEventQueue(
        /*# string */ $eventName
    )/*# : bool */;

    /**
     * Get the event queue for $eventName
     *
     * @param  string $eventName the event name
     * @return EventQueueInterface
     * @throws Exception\NotFoundException if not find
     * @access public
     * @api
     */
    public function getEventQueue(
        /*# string */ $eventName
    )/*# : EventQueueInterface */;

    /**
     * Clear the event queue for $eventName
     *
     * @param  string $eventName the event name
     * @return EventManagerInterface $this
     * @access public
     * @api
     */
    public function clearEventQueue(
        /*# string */ $eventName
    )/*# : EventManagerInterface */;

    /**
     * Get all event names in an array
     *
     * @param  void
     * @return string[]
     * @access public
     * @api
     */
    public function getEventNames()/*# : array */;
}

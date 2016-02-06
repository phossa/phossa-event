<?php
/**
 * Phossa Project
 *
 * PHP version 5.4
 *
 * @category  Package
 * @package   Phossa\Event
 * @author    Hong Zhang <phossa@126.com>
 * @copyright 2015 phossa.com
 * @license   http://mit-license.org/ MIT License
 * @link      http://www.phossa.com/
 */
/*# declare(strict_types=1); */

namespace Phossa\Event\Interfaces;

/**
 * EventManager Interface
 *
 * No restrictions is forced upon the event manager yet. It can be a
 * singleton, a local event manager, or a child process event manager ...
 *
 * @interface
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.3
 * @since   1.0.0 added
 */
interface EventManagerInterface
{
    /**
     * Fire up an event and process it by listeners
     *
     * Retrieve all the callables attached to this event from THIS MANAGER and
     * execute them in order. Return the event if ends OR event is stopped OR
     * the optional callable $callback returns false.
     *
     * The $callback signature is `function($event, $response): bool {}`, where
     * $reponse is the return value from previous event handler.
     *
     * @param  EventInterface $event the event
     * @param  callable $callback (optional) a callback returns bool
     * @return EventManagerInterface
     * @throws \Phossa\Event\Exception\RuntimeException
     *         rethrow any exception catched as RuntimeException
     * @access public
     * @api
     */
    public function processEvent(
        EventInterface $event,
        callable $callback = null
    )/*# : EventManagerInterface */;

    /**
     * Attach a listener with a specific event or all its events
     *     * Attach to all its events where $eventName is ''
     *
     * @param  mixed $listener object or static class name or callable
     * @param  string $eventName event name or '' for all its events
     * @param  int $priority priority level, bigger one has high priority
     * @return EventManagerInterface this
     * @throws \Phossa\Event\Exception\InvalidArgumentException
     *         if $listener not right, or format of callable not right
     * @throws \Phossa\Event\Exception\BadMethodCallExceptionException
     *         if immutable
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
     * @param  mixed $listener object or static class name or callable
     * @param  string $eventName event name or '' for all its attached events
     * @return EventManagerInterface this
     * @throws \Phossa\Event\Exception\InvalidArgumentException
     *         if $listener not right
     * @throws \Phossa\Event\Exception\BadMethodCallExceptionException
     *         if immutable
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
     * returns true if exists AND not empty. Always return false if $eventName
     * is not a string
     *
     * @param  string $eventName the event name
     * @return bool
     * @access public
     * @api
     */
    public function hasEventQueue(/*# string */ $eventName)/*# : bool */;

    /**
     * Get the event queue for $eventName
     *
     * @param  string $eventName the event name
     * @return EventQueueInterface
     * @throws \Phossa\Event\Exception\NotFoundException if not find
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
     * @return EventManagerInterface this
     * @throws \Phossa\Event\Exception\BadMethodCallExceptionException
     *         if immutable
     * @access public
     * @api
     */
    public function clearEventQueue(
        /*# string */ $eventName
    )/*# : EventManagerInterface */;

    /**
     * Get all event names in an array
     *
     * @return string[]
     * @access public
     * @api
     */
    public function getEventNames()/*# : array */;
}

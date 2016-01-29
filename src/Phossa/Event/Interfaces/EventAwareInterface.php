<?php
/*
 * Phossa Project
 *
 * @see         http://www.phossa.com/
 * @copyright   Copyright (c) 2015 phossa.com
 * @license     http://mit-license.org/ MIT License
 */
/*# declare(strict_types=1); */

namespace Phossa\Event\Interfaces;

/**
 * Make implementing class event aware or capable
 *
 * Allow implementing classes has the ability to use events (trigger etc.)
 *
 * @interface
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.1
 * @since   1.0.0 added
 */
interface EventAwareInterface
{
    /**
     * Setup event related stuff
     *
     * Inject the event manager object. also, if using a different event class
     * other than the 'Phossa\Event\Event', user may pass a callable which
     * takes same arguments as the 'Event' class, to create event object.
     *
     * <code>
     *     $this->setEventManager(
     *         new EventManager(),
     *         function($evtName, $context, $properties) {
     *             return new MyEvent($evtName, $context, $properties);
     *         }
     *     );
     * </code>
     *
     * @param  EventManagerInterface $eventManager event manager object
     * @param  callable $eventFactory (optional) event factory callback
     * @return this
     * @see    \Phossa\Event\Interfaces\EventManagerInterface
     * @access public
     * @api
     */
    public function setEventManager(
        EventManagerInterface $eventManager,
        callable $eventFactory = null
    )/*# : EventAwareInterface */;

    /**
     * Trigger an event and processed it by event manager, return the event
     *
     * @param  string $eventName event name
     * @param  array $properties (optional) event property array
     * @return EventInterface
     * @throws \Phossa\Event\Exception\NotFoundException
     *         if event manager not set yet
     * @throws \Phossa\Event\Exception\RuntimeException
     *         exceptions from $event_manager->processEvent()
     * @see    \Phossa\Event\EventManager::processEvent()
     * @access public
     * @api
     */
    public function triggerEvent(
        /*# string */ $eventName,
        array $properties = []
    )/*# : EventInterface */;
}

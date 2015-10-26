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
 * Make implementing class event capable
 *
 * Allow implementing classes has the ability to use events (trigger etc.)
 *
 * @interface
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
interface EventCapableInterface
{
    /**
     * Setup event related stuff
     *
     * @param  EventManagerInterface $eventManager event manager object
     * @param  EventFactoryInterface $eventFactory event factory object
     * @return EventCapableInterface $this
     * @access public
     * @api
     */
    public function setEventCapable(
        EventManagerInterface $eventManager,
        EventFactoryInterface $eventFactory
    )/*# : EventCapableInterface */;

    /**
     * Trigger an event and processed by event manager, return the event
     *
     * @param  string $eventName event name
     * @param  array $properties event property array
     * @return EventInterface
     * @throws Exception\NotFoundException
     *         if event manager not set yet
     * @throws Exception\RuntimeException
     *         exceptions from $event_manager->processEvent()
     * @access public
     * @api
     */
    public function triggerEvent(
        /*# string */ $eventName,
        array $properties = []
    )/*# : EventInterface */;
}

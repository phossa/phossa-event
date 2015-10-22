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
 * Make static classes event capable
 *
 * Allow implementing static class has the ability to use events.
 *
 * @interface
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
interface EventCapableStaticInterface
{
    /**
     * Setup event related stuff
     *
     * @param  EventManagerInterface $eventManager event manager object
     * @param  EventFactoryInterface $eventFactory event factory object
     * @return void
     * @access public
     * @static
     * @api
     */
    public static function setEventCapable(
        EventManagerInterface $eventManager,
        EventFactoryInterface $eventFactory
    )/*# : void */;

    /**
     * Trigger an event to be processed by event manager
     *
     * @param  string $eventName event name
     * @param  array $properties event property array
     * @return EventInterface
     * @throws Exception\NotFoundException
     *         if event manager not set yet
     * @throws Exception\RuntimeException
     *         exceptions from $event_manager->processEvent()
     * @access public
     * @static
     * @api
     */
    public static function triggerEvent(
        /*# string */ $eventName,
        array $properties = []
    )/*# : EventInterface */;
}

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
 * Make static classes event aware or capable
 *
 * Allow implementing static class has the ability to use events.
 *
 * @interface
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.1
 * @since   1.0.0 added
 */
interface EventAwareStaticInterface
{
    /**
     * Setup event related stuff
     *
     * Inject the event manager object. also, if using a different event class
     * other than the 'Phossa\Event\Event', user may pass a callable which
     * takes same arguments as the 'Event' class, to create event object.
     *
     * <code>
     *     MyClass::setEventManager(
     *         $evtManager,
     *         function($evtName, $context, $properties) {
     *             return new MyEvent($evtName, $context, $properties);
     *         }
     *     );
     * </code>
     *
     * @param  EventManagerInterface $eventManager event manager object
     * @param  callable $eventFactory (optional) event factory callback
     * @return void
     * @throws \Phossa\Event\Exception\LogicException
     *         if some class properties not defined
     * @see    \Phossa\Event\Interfaces\EventManagerInterface
     * @access public
     * @static
     * @api
     */
    public static function setEventManager(
        EventManagerInterface $eventManager,
        callable $eventFactory = null
    );

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
     * @access public
     * @see    \Phossa\Event\EventManager::processEvent()
     * @static
     * @api
     */
    public static function triggerEvent(
        /*# string */ $eventName,
        array $properties = []
    )/*# : EventInterface */;
}

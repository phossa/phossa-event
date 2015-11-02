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
 * Simple implementation of EventCapableStaticInterface
 *
 * @trait
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     Phossa\Event\EventCapableStaticInterface
 * @version 1.0.0
 * @since   1.0.0 added
 */
trait EventCapableStaticTrait
{
    /**
     * event manager
     *
     * each descendant classes of class using this trait may define its
     * own copy of protected event manager.
     *
     * @var    EventManagerInterface
     * @type   EventManagerInterface
     * @access protected
     * @static
     */
    protected static $event_manager;

    /**
     * event factory
     *
     * each descendant classes of class using this trait may define its
     * own copy of protected event factory.
     *
     * @var    callable
     * @type   callable
     * @access protected
     * @static
     */
    protected static $event_factory;

    /**
     * {@inheritDoc}
     */
    public static function setEventManager(
        EventManagerInterface $eventManager,
        callable $eventFactory = null
    )/*# : void */ {
        static::$event_manager = $eventManager;
        static::$event_factory = $eventFactory;
    }

    /**
     * {@inheritDoc}
     */
    public static function triggerEvent(
        /*# string */ $eventName,
        array $properties = []
    )/*# : EventInterface */ {
        if (null !== static::$event_manager) {
            if (static::$event_factory) {
                $evt = call_user_func(
                    static::$event_factory,
                    $eventName,
                    get_called_class(),
                    $properties
                );
            } else {
                $evt = new Event($eventName, get_called_class(), $properties);
            }
            return static::$event_manager->processEvent($evt);
        }
        throw new Exception\NotFoundException(
            Message::get(
                Message::MANAGER_NOT_FOUND,
                get_called_class()
            ),
            Message::MANAGER_NOT_FOUND
        );
    }
}

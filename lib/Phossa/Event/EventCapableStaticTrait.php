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
 * @version 1.0.0
 * @since   1.0.0 added
 */
trait EventCapableStaticTrait
{
    /**
     * event manager
     *
     * @var    EventManagerInterface
     * @type   EventManagerInterface
     * @access protected
     * @static
     */
    protected static $event_manager = null;

    /**
     * event factory
     *
     * @var    EventFactoryInterface
     * @type   EventFactoryInterface
     * @access protected
     * @static
     */
    protected static $event_factory = null;

    /**
     * {@inheritDoc}
     */
    public static function setEventCapable(
        EventManagerInterface $eventManager,
        EventFactoryInterface $eventFactory
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
            return static::$event_manager->processEvent(
                static::$event_factory->createEvent(
                    $eventName,
                    get_called_class(),
                    $properties
                )
            );
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

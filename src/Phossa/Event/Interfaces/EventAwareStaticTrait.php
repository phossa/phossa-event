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

use Phossa\Event\Event;
use Phossa\Event\Exception;
use Phossa\Event\Message\Message;

/**
 * Simple implementation of EventAwareStaticInterface
 *
 * <code>
 *     namespace \MyCompany\MyProject;
 *
 *     use Phossa\Event\Interfaces\EventAwareStaticInterface;
 *
 *     class MyStaticClass implements EventAwareStaticInterface
 *     {
 *        use \Phossa\Event\Interfaces\EventAwareStaticTrait;
 *     }
 * </code>
 *
 * @trait
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\Interfaces\EventAwareStaticInterface
 * @version 1.0.1
 * @since   1.0.0 added
 */
trait EventAwareStaticTrait
{
    /**
     * event manager
     *
     * @var    EventManagerInterface[]
     * @access protected
     * @static
     */
    protected static $event_manager = [];

    /**
     * event factory
     *
     * @var    callable[]
     * @access protected
     * @static
     */
    protected static $event_factory = [];

    /**
     * {@inheritDoc}
     */
    public static function setEventManager(
        EventManagerInterface $eventManager,
        callable $eventFactory = null
    ) {
        $class = get_called_class();

        // set event_manager
        self::$event_manager[$class] = $eventManager;

        // set event factory
        self::$event_factory[$class] = $eventFactory;
    }

    /**
     * {@inheritDoc}
     */
    public static function triggerEvent(
        /*# string */ $eventName,
        array $properties = []
    )/*# : EventInterface */ {
        $class   = get_called_class();

        // manager not found
        if (!isset(self::$event_manager[$class])) {
            throw new Exception\NotFoundException(
                Message::get(
                    Message::MANAGER_NOT_FOUND,
                    $class
                ),
                Message::MANAGER_NOT_FOUND
            );
        }

        $manager = self::$event_manager[$class];
        $factory = self::$event_factory[$class];

        // event factory
        if ($factory) {
            $evt = call_user_func($factory, $eventName, $class, $properties);
        } else {
            $evt = new Event($eventName, $class, $properties);
        }

        // process the event
        $manager->processEvent($evt);

        return $evt;
    }
}

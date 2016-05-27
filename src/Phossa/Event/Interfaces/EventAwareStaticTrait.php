<?php
/**
 * Phossa Project
 *
 * PHP version 5.4
 *
 * @category  Library
 * @package   Phossa\Event
 * @copyright 2015 phossa.com
 * @license   http://mit-license.org/ MIT License
 * @link      http://www.phossa.com/
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
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\Interfaces\EventAwareStaticInterface
 * @version 1.0.7
 * @since   1.0.0 added
 * @since   1.0.3 changed to event prototype
 * @since   1.0.6 added getEventManager()
 * @since   1.0.7 rename function names
 */
trait EventAwareStaticTrait
{
    /**
     * event manager
     *
     * @var    EventManagerInterface[]
     * @access private
     * @static
     */
    private static $s_event_manager = [];

    /**
     * event prototypes for static class
     *
     * @var    EventInterface[]
     * @access private
     * @static
     */
    private static $s_event_proto   = [];

    /**
     * {@inheritDoc}
     */
    public static function setEventManagerStatically(
        EventManagerInterface $eventManager,
        EventInterface $eventPrototype = null
    ) {
        $class = get_called_class();

        // set event_manager
        self::$s_event_manager[$class] = $eventManager;

        // set event prototype
        if (!is_null($eventPrototype)) {
            self::$s_event_proto[$class] = $eventPrototype;
        }
    }

    /**
     * {@inheritDoc}
     */
    public static function getEventManagerStatically()/*# : EventManagerInterface */
    {
        // manager not found
        $class = get_called_class();
        if (!isset(self::$s_event_manager[$class])) {
            throw new Exception\NotFoundException(
                Message::get(
                    Message::MANAGER_NOT_FOUND,
                    $class
                ),
                Message::MANAGER_NOT_FOUND
            );
        }
        return self::$s_event_manager[$class];
    }

    /**
     * {@inheritDoc}
     */
    public static function triggerEventStatically(
        /*# string */ $eventName,
        array $properties = []
    )/*# : EventInterface */ {
        $class = get_called_class();
        $manager = static::getEventManagerStatically();

        // event prototype
        if (isset(self::$s_event_proto[$class])) {
            $evt = clone self::$s_event_proto[$class];
            $evt($eventName, $class, $properties);
        } else {
            $evt = new Event($eventName, $class, $properties);
        }

        // process the event
        $manager->processEvent($evt);

        return $evt;
    }
}

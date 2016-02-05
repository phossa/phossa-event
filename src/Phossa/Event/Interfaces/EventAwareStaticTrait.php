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
 * @version 1.0.3
 * @since   1.0.0 added
 * @since   1.0.3 changed to event prototype
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
     * event prototypes for static class
     *
     * @var    EventInterface[]
     * @access protected
     * @static
     */
    protected static $event_proto   = [];

    /**
     * {@inheritDoc}
     */
    public static function setEventManager(
        EventManagerInterface $eventManager,
        EventInterface $eventPrototype = null
    ) {
        $class = get_called_class();

        // set event_manager
        self::$event_manager[$class] = $eventManager;

        // set event prototype
        if (!is_null($eventPrototype)) {
            self::$event_proto[$class] = $eventPrototype;
        }
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

        // event prototype
        if (isset(self::$event_proto[$class])) {
            $evt = clone self::$event_proto[$class];
            $evt($eventName, $class, $properties);
        } else {
            $evt = new Event($eventName, $class, $properties);
        }

        // process the event
        $manager->processEvent($evt);

        return $evt;
    }
}

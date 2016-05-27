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

/**
 * Make static classes event aware or capable
 *
 * Allow implementing static class has the ability to use events.
 *
 * @todo needs different property/method names since one class may need to
 *       static and dynamic event capabilities!!
 * @interface
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.6
 * @since   1.0.0 added
 * @since   1.0.3 changed to event prototype
 * @since   1.0.6 added getEventManager()
 */
interface EventAwareStaticInterface
{
    /**
     * Setup event related stuff
     *
     * Inject the event manager object. Also, if using a different event class
     * other than the 'Phossa\Event\Event', user may pass a prototype.
     *
     * <code>
     *     MyClass::setEventManager(
     *         $evtManager,
     *         new MyEvent('prototype')
     *     );
     * </code>
     *
     * @param  EventManagerInterface $eventManager event manager object
     * @param  EventInterface $eventPrototype (optional) event prototype
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
        EventInterface $eventPrototype = null
    );

    /**
     * Get the event manager
     *
     * @return EventManagerInterface
     * @throws \Phossa\Event\Exception\NotFoundException
     *         if event manager not set yet
     * @access public
     * @static
     * @api
     */
    public static function getEventManager()/*# : EventManagerInterface */;

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

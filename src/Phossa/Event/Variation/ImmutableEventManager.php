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

namespace Phossa\Event\Variation;

use Phossa\Event\Exception;
use Phossa\Event\Message\Message;
use Phossa\Event\Interfaces\EventInterface;
use Phossa\Event\Interfaces\EventManagerInterface;

/**
 * An event manager with an immutable wrapper, prevent from being changed
 *
 * <code>
 *     // low-level manager to hide
 *     $evtManager = new EventManager();
 *     $evtManager->attachListener(...);
 *     ...
 *
 *     // expose an immutable event managet to user
 *     $iManager = new Variation\ImmutableEventManager($evtManager);
 *
 *     // cause an exception
 *     $iManager->detachListener( ... );
 * </code>
 *
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\Interfaces\EventManagerInterface
 * @version 1.0.3
 * @since   1.0.2 added
 */
class ImmutableEventManager implements EventManagerInterface
{
    /**
     * slave event manager
     *
     * @var    EventManagerInterface
     * @access protected
     */
    protected $event_manager;

    /**
     * Constructor, insert a slave EventManager
     *
     * @param  EventManagerInterface $eventManager event manager
     * @access public
     * @api
     */
    public function __construct(EventManagerInterface $eventManager)
    {
        $this->event_manager = $eventManager;
    }

    /**
     * {@inheritDoc}
     */
    public function processEvent(
        EventInterface $event,
        callable $callback = null
    )/*# : EventManagerInterface */ {
        return $this->event_manager->processEvent($event, $callback);
    }

    /**
     * {@inheritDoc}
     */
    public function attachListener(
        $listener,
        /*# string */ $eventName = '',
        /*# int */ $priority = 50
    )/*# : EventManagerInterface */ {
        $this->throwsBadMethodCallException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function detachListener(
        $listener,
        /*# string */ $eventName = ''
    )/*# : EventManagerInterface */ {
        $this->throwsBadMethodCallException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function hasEventQueue(
        /*# string */ $eventName
    )/*# : bool */ {
        return $this->event_manager->hasEventQueue($eventName);
    }

    /**
     * {@inheritDoc}
     */
    public function getEventQueue(
        /*# string */ $eventName
    )/*# : EventQueueInterface */ {
        return $this->event_manager->getEventQueue($eventName);
    }

    /**
     * {@inheritDoc}
     */
    public function clearEventQueue(
        /*# string */ $eventName
    )/*# : EventManagerInterface */ {
        $this->throwsBadMethodCallException(__METHOD__);
    }

    /**
     * {@inheritDoc}
     */
    public function getEventNames()/*# : array */
    {
        return $this->event_manager->getEventNames();
    }

    /**
     * Throw BadMethodCallException
     *
     * @param  string $method method name
     * @return void
     * @throws Exception\BadMethodCallException
     * @access protected
     */
    protected function throwsBadMethodCallException(/*# string */ $method)
    {
        throw new Exception\BadMethodCallException(
            Message::get(Message::IMMUTABLE_EVENT_METHOD, $method),
            Message::IMMUTABLE_EVENT_METHOD
        );
    }
}

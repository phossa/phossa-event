<?php
/*
 * Phossa Project
 *
 * @see         http://www.phossa.com/
 * @copyright   Copyright (c) 2015 phossa.com
 * @license     http://mit-license.org/ MIT License
 */
/*# declare(strict_types=1); */

namespace Phossa\Event\Variation;

use Phossa\Event\Exception;
use Phossa\Event\Interfaces;
use Phossa\Event\Message\Message;

/**
 * An event manager with an immutable wrapper, prevent from being changed
 *
 * <code>
 *     // low-level manager to hide
 *     $_evtManager = new EventManager();
 *     $_evtManager->attachListener(...);
 *     ...
 *
 *     // expose an immutable event managet to user
 *     $evtManager = new Variation\ImmutableEventManager($_evtManager);
 *
 *     // cause an exception
 *     $evtManager->detachListener( ... );
 * </code>
 *
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\Interfaces\EventManagerInterface
 * @version 1.0.2
 * @since   1.0.2 added
 */
class ImmutableEventManager implements Interfaces\EventManagerInterface
{
    /**
     * inner event manager
     *
     * @var    Interfaces\EventManagerInterface
     * @access protected
     */
    protected $event_manager;

    /**
     * Constructor, insert a EventManager
     *
     * @param  Interfaces\EventManagerInterface $eventManager event manager
     * @return void
     * @throws void
     * @access public
     * @api
     */
    public function __construct(
        Interfaces\EventManagerInterface $eventManager
    ) {
        $this->event_manager = $eventManager;
    }

    /**
     * {@inheritDoc}
     */
    public function processEvent(
        Interfaces\EventInterface $event,
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
    protected function throwsBadMethodCallException(
        /*# string */ $method
    ) {
        throw new Exception\BadMethodCallException(
            Message::get(Message::IMMUTABLE_EVENT_METHOD, $method),
            Message::IMMUTABLE_EVENT_METHOD
        );
    }
}

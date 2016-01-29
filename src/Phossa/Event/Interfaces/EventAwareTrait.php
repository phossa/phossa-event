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
 * EventAwareTrait
 *
 * Simple implementation of EventAwareInterface
 *
 * @trait
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\Interfaces\EventAwareInterface
 * @version 1.0.1
 * @since   1.0.0 added
 */
trait EventAwareTrait
{
    /**
     * event manager
     *
     * @var    EventManagerInterface
     * @access protected
     */
    protected $event_manager;

    /**
     * event factory
     *
     * @var    callable
     * @access protected
     */
    protected $event_factory;

    /**
     * {@inheritDoc}
     */
    public function setEventManager(
        EventManagerInterface $eventManager,
        callable $eventFactory = null
    )/*# : EventAwareInterface */ {
        $this->event_manager = $eventManager;
        $this->event_factory = $eventFactory;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function triggerEvent(
        /*# string */ $eventName,
        array $properties = []
    )/*# : EventInterface */ {
        if (null !== $this->event_manager) {
            if ($this->event_factory) {
                $evt = call_user_func(
                    $this->event_factory,
                    $eventName,
                    $this,
                    $properties
                );
            } else {
                $evt = new Event($eventName, $this, $properties);
            }
            $this->event_manager->processEvent($evt);
            return $evt;
        }
        throw new Exception\NotFoundException(
            Message::get(
                Message::MANAGER_NOT_FOUND,
                get_class()
            ),
            Message::MANAGER_NOT_FOUND
        );
    }
}

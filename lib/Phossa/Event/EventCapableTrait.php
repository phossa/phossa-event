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
 * EventCapableTrait
 *
 * Simple implementation of EventCapableInterface
 *
 * @trait
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
trait EventCapableTrait
{
    /**
     * event manager
     *
     * @var    EventManagerInterface
     * @type   EventManagerInterface
     * @access protected
     */
    protected $event_manager = null;

    /**
     * event factory
     *
     * @var    EventFactoryInterface
     * @type   EventFactoryInterface
     * @access protected
     */
    protected $event_factory = null;

    /**
     * {@inheritDoc}
     */
    public function setEventCapable(
        EventManagerInterface $eventManager,
        EventFactoryInterface $eventFactory
    )/*# : EventCapableInterface */ {
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
            return $this->event_manager->processEvent(
                $this->event_factory->createEvent(
                    $eventName,
                    $this,
                    $properties
                )
            );
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

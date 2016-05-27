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
 * EventAwareTrait
 *
 * Simple implementation of EventAwareInterface
 *
 * @trait
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\Interfaces\EventAwareInterface
 * @version 1.0.5
 * @since   1.0.0 added
 * @since   1.0.3 changed to event prototype
 * @since   1.0.5 added getEventManager()
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
     * event prototype
     *
     * @var    EventInterface
     * @access protected
     */
    protected $event_proto;

    /**
     * {@inheritDoc}
     */
    public function setEventManager(
        EventManagerInterface $eventManager,
        EventInterface $eventPrototype = null
    )/*# : EventAwareInterface */ {
        $this->event_manager = $eventManager;
        if (!is_null($eventPrototype)) {
            $this->event_proto = $eventPrototype;
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getEventManager()/*# : EventManagerInterface */
    {
        if (null === $this->event_manager) {
            throw new Exception\NotFoundException(
                Message::get(
                    Message::MANAGER_NOT_FOUND,
                    get_class()
                ),
                Message::MANAGER_NOT_FOUND
            );
        }
        return $this->event_manager;
    }

    /**
     * {@inheritDoc}
     */
    public function triggerEvent(
        /*# string */ $eventName,
        array $properties = []
    )/*# : EventInterface */ {
        $evtManager = $this->getEventManager();
        if (is_null($this->event_proto)) {
            $evt = new Event($eventName, $this, $properties);
        } else {
            $evt = clone $this->event_proto;
            $evt($eventName, $this, $properties);
        }
        $evtManager->processEvent($evt);
        return $evt;
    }
}

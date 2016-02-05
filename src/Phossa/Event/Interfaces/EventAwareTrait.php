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
 * EventAwareTrait
 *
 * Simple implementation of EventAwareInterface
 *
 * @trait
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\Interfaces\EventAwareInterface
 * @version 1.0.3
 * @since   1.0.0 added
 * @since   1.0.3 changed to event prototype
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
    public function triggerEvent(
        /*# string */ $eventName,
        array $properties = []
    )/*# : EventInterface */ {
        if (null !== $this->event_manager) {
            if (is_null($this->event_proto)) {
                $evt = new Event($eventName, $this, $properties);
            } else {
                $evt = clone $this->event_proto;
                $evt($eventName, $this, $properties);
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

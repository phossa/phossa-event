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

use Phossa\Event\Exception;
use Phossa\Event\Message\Message;

/**
 * Implementation of EventManagerCompositeInterface
 *
 * @trait
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\Interfaces\EventManagerCompositeInterface
 * @version 1.0.1
 * @since   1.0.1 added
 */
trait EventManagerCompositeTrait
{
    /**
     * Pool for other managers
     *
     * @var     EventManagerInterface[]
     * @access  protected
     */
    protected $manager_pool = [];

    /**
     * {@inheritDoc}
     */
    public function setOtherManager(
        /*# string */ $name,
        EventManagerInterface $manager
    )/*# : EventManagerCompositeInterface */ {
        // you can NOT use composite type as other manager
        if ($manager instanceof EventManagerCompositeInterface) {
            throw new Exception\InvalidArgumentException(
                Message::get(
                    Message::INVALID_EVENT_MANAGER, get_class($manager)
                ),
                Message::INVALID_EVENT_MANAGER
            );
        }

        $this->manager_pool[$name] = $manager;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function unsetOtherManager(
        /*# string */ $name
    )/*# : EventManagerCompositeInterface */ {
        unset($this->manager_pool[$name]);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getOtherManagers()/*# : array */
    {
        return $this->manager_pool;
    }

    /**
     * {@inheritDoc}
     */
    public function processEvent(
        EventInterface $event,
        callable $callback = null
    )/*# : EventManagerInterface */ {
        $eventName = $event->getName();

        // check self's queue
        $queue = $this->matchEventQueue($eventName, $this);

        // merge with other managers' queue
        $managers = $this->getOtherManagers();
        foreach($managers as $m) {
            // other manager's own queue
            $q = $this->matchEventQueue($eventName, $m);
            if ($q->count()) $queue = $queue->combine($q);
        }

        // run thru the queue
        return $this->runEventQueue($event, $queue, $callback);
    }
}

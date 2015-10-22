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

/**
 * Implementation of EventManagerAdvancedInterface
 *
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
class EventManagerAdvanced extends EventManager implements
    EventManagerAdvancedInterface
{
    /**
     * Pool for other managers
     *
     * @var     array
     * @type    array
     * @access  protected
     */
    protected $manager_pool = [];

    /**
     * {@inheritDoc}
     */
    public function setOtherManager(
        /*# string */ $name,
        EventManagerInterface $manager
    ) {
        $this->manager_pool[(string) $name] = $manager;
    }

    /**
     * {@inheritDoc}
     */
    public function unsetOtherManager(
        /*# string */ $name
    ) {
        unset($this->manager_pool[(string) $name]);
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
    public function processEventUntil(
        EventInterface $event,
        callable $callback
    )/*# : EventInterface */ {
        $eventName = $event->getName();

        // check self first
        $queue = $this->matchEventQueue($eventName, $this);

        // other managers
        $managers = $this->getOtherManagers();
        foreach($managers as $n => $m) {
            $q = $this->matchEventQueue($eventName, $m);
            if ($q->count()) $queue->combine($q);
        }

        // run thru the queue
        return $this->runEventQueueUntil($event, $queue, $callback);
    }

    /**
     * Match proper event queue, including queues with globbing event names
     *
     * @param  string $eventName event name to match
     * @param  EventManager $manager which manager to look at
     * @return EventQueueInterface
     * @throws void
     * @access public
     * @api
     */
    public function matchEventQueue(
        /*# string */ $eventName,
        EventManagerInterface $manager
    )/*# : EventQueueInterface */ {
        $evts = $this->globNames($eventName, $manager->getEventNames());

        // combine all globbing queue
        $queue = new EventQueue();
        foreach($evts as $e) {
            if ($manager->hasEventQueue($e)) {
                $queue = $queue->combine($manager->getEventQueue($e));
            }
        }

        return $queue;
    }

    /**
     * Return those globbing event names based on the given one
     *
     * e.g.
     * Matching 'login.MobileFail' with those '*', 'login.*', 'login.*Fail'
     *
     * @param  string $eventName specific event name
     * @param  array $names name array to search thru
     * @return array
     * @access protected
     */
    protected function globNames(
        /*# string */ $eventName,
        array $names
    )/*# : array */ {
        $result = [];
        foreach($names as $n) {
            if ($n === '*' || $n === $eventName) {
                $result[] = $n;
            } elseif (strpos($n, '*') !== false) {
                $regex = str_replace(array('.', '*'), array('[.]', '.*?'), $n);
                if (preg_match('/^'.$regex.'$/', $eventName)) $result[] = $n;
            }
        }
        return $result;
    }
}

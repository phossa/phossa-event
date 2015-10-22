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
 * Provides advanced features other than EventManagerInterface
 *
 * - Set extra managers such as global or shared managers, and able to trigger
 *   callables in these managers in the event.
 *
 * - Able to globbing names, such as 'login.attempt' event will also triggers
 *   callables registered under 'login.*' event name and also triggers those
 *   registered under '*'
 *
 * @interface
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
interface EventManagerAdvancedInterface extends EventManagerInterface
{
    /**
     * Set extra event managers, global, shared, peer etc.
     *
     * @param  string $name name for this manager
     * @param  EventManagerInterface $manager
     * @return void
     * @access public
     * @api
     */
    public function setOtherManager(
        /*# string */ $name,
        EventManagerInterface $manager
    );

    /**
     * Unset extra manager
     *
     * @param  string $name manager name (id)
     * @return void
     * @access public
     * @api
     */
    public function unsetOtherManager(
        /*# string */ $name
    );

    /**
     * Get other managers in array [ name => $manager ]
     *
     * @param  void
     * @return array
     * @access public
     * @api
     */
    public function getOtherManagers()/*# : array */;

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
    )/*# : EventQueueInterface */;
}

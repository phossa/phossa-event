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
 * EventQueueInterface
 *
 * @interface
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
interface EventQueueInterface extends \IteratorAggregate
{
    /**
     * Insert callable into queue with priority
     *
     * @param  mixed $callable callable
     * @param  int $priority priority
     * @return void
     * @throws Exception\InvalidArgumentException if not a callable
     * @access public
     * @api
     */
    public function insert(
        $callable,
        /*# int */ $priority
    );

    /**
     * Remove a callable from the queue
     *
     * @param  mixed $callable callable to remove
     * @return void
     * @throws Exception\InvalidArgumentException if not a callable
     * @access public
     * @api
     */
    public function remove($callable);

    /**
     * Empty/flush the queue
     *
     * @param  void
     * @return void
     * @access public
     * @api
     */
    public function flush();

    /**
     * Combine with an event queue, return the new event queue
     *
     * @param  EventQueueInterface $queue
     * @return EventQueueInterface the new event queue
     * @access public
     * @api
     */
    public function combine(
        EventQueueInterface $queue
    )/*# : EventQueueInterface */;
}

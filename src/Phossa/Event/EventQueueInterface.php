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
 * Wrapper/proxy/decorator of SplPriorityQueue
 *
 * @interface
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \IteratorAggregate
 * @see     \Countable
 * @version 1.0.0
 * @since   1.0.0 added
 */
interface EventQueueInterface extends \IteratorAggregate, \Countable
{
    /**
     * Insert callable into queue with priority
     *
     * @param  callable $callable
     * @param  int $priority priority
     * @return void
     * @access public
     * @api
     */
    public function insert(
        callable $callable,
        /*# int */ $priority
    );

    /**
     * Remove a callable from the queue
     *
     * @param  callable $callable
     * @return void
     * @access public
     * @api
     */
    public function remove(callable $callable);

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
     * Combine self with another event queue
     *
     * return the result event queue, self is not changed
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

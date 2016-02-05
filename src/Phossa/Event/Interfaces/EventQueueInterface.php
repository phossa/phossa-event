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

/**
 * EventQueueInterface
 *
 * Wrapper/proxy/decorator of SplPriorityQueue
 *
 * @interface
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \IteratorAggregate
 * @see     \Countable
 * @version 1.0.3
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
     * @return void
     * @access public
     * @api
     */
    public function flush();

    /**
     * Combine self with another event queue and return the new queue
     *
     * return the result event queue, self queue is not changed
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

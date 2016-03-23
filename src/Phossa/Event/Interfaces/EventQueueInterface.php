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

use Phossa\Event\Exception;

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
 * @version 1.0.4
 * @since   1.0.0 added
 * @since   1.0.4 added sort()
 */
interface EventQueueInterface extends \IteratorAggregate, \Countable
{
    /**
     * Insert callable into queue with priority
     *
     * @param  callable $callable
     * @param  int $priority priority
     * @param  bool $sort sort by priority (1.0.4)
     * @return void
     * @throws Exception\InvalidArgumentException
     *         if $priority not in 0 - 100
     * @access public
     * @api
     */
    public function insert(
        callable $callable,
        /*# int */ $priority,
        /*# bool */ $sort = true
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
     * sort the queue by priority
     *
     * @return void
     * @access public
     * @since  1.0.4
     * @api
     */
    public function sort();

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

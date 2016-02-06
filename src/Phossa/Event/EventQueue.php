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

namespace Phossa\Event;

use Phossa\Event\Interfaces\EventQueueInterface;

use Phossa\Event\Exception;
use Phossa\Event\Message\Message;

/**
 * Implementation of EventQueueInterface
 *
 * Simple usage:
 * <code>
 *     $queue = new EventQueue();
 *     $queue->insert($callable, 20);
 *     foreach($queue as $c) {
 *         $callable = $c['data'];
 *         $priority = $c['priority'];
 *         call_user_func($callable, ...);
 *     }
 *     if ($queue->count()) $queue->flush();
 * </code>
 *
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\Interfaces\EventQueueInterface
 * @version 1.0.3
 * @since   1.0.0 added
 * @since   1.0.3 removed SplPriority to support HHVM
 */
class EventQueue implements EventQueueInterface
{
    /**
     * inner array
     *
     * @var    array
     * @access protected
     */
    protected $queue;

    /**
     * constructor
     *
     * @access public
     * @api
     */
    public function __construct()
    {
        $this->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->queue);
    }

    /**
     * {@inheritDoc}
     *
     * returns ['data' => data, 'priority' => priority]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->queue);
    }

    /**
     * {@inheritDoc}
     *
     * We are not using complex priority like [ priority, PHP_MAX--]
     */
    public function insert(
        callable $callable,
        /*# int */ $priority,
        /*# bool */ $sort = true
    ) {
        static $MAX = 9999999;

        if ($priority > 100 || $priority < 0) {
            throw new Exception\InvalidArgumentException(
                Message::get(Message::INVALID_EVENT_PRIORITY, $priority),
                Message::INVALID_EVENT_PRIORITY
            );
        }

        // key
        $key  = $priority * 10000000 + $MAX--;
        $this->queue[$key] = ['data' => $callable, 'priority' => $priority ];

        // sort by priority
        if ($sort) {
            $this->sort();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function remove(callable $callable)
    {
        foreach ($this->queue as $key => $val) {
            if ($val['data'] === $callable) {
                unset($this->queue[$key]);
                break;
            }
        }
    }

    /**
     * {@inheritdic}
     */
    public function flush()
    {
        $this->queue = [];
    }

    /**
     * {@inheritdic}
     */
    public function sort()
    {
        krsort($this->queue);
    }

    /**
     * {@inheritDoc}
     */
    public function combine(
        EventQueueInterface $queue
    )/*# : EventQueueInterface */ {
        $nqueue = clone $this;
        foreach ($queue as $data) {
            $nqueue->insert($data['data'], (int) $data['priority'], false);
        }
        $nqueue->sort();
        return $nqueue;
    }
}

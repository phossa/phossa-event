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

use Phossa\Event\Interfaces\EventQueueInterface;

/**
 * Implementation of EventQueueInterface, a wrapper of SplPriorityQueue
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
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\Interfaces\EventQueueInterface
 * @see     \SplPriorityQueue
 * @version 1.0.1
 * @since   1.0.0 added
 */
class EventQueue implements EventQueueInterface
{
    /**
     * the inner SplPriorityQueue
     *
     * @var    \SplPriorityQueue
     * @access protected
     */
    protected $queue;

    /**
     * constructor
     *
     * @param  void
     * @access public
     * @api
     */
    public function __construct()
    {
        $this->queue = new \SplPriorityQueue();
    }

    /**
     * {@inheritDoc}
     */
    public function count() {
        return $this->queue->count();
    }

    /**
     * {@inheritDoc}
     *
     * returns ['data' => data, 'priority' => priority]
     */
    public function getIterator() {
        $nqueue = clone $this->queue;
        $nqueue->setExtractFlags(\SplPriorityQueue::EXTR_BOTH);
        return $nqueue;
    }

    /**
     * {@inheritDoc}
     *
     * We are not using complex priority like [ priority, PHP_MAX--]
     */
    public function insert(
        callable $callable,
        /*# int */ $priority
    ) {
        $this->queue->insert($callable, (int) $priority);
    }

    /**
     * {@inheritDoc}
     */
    public function remove(callable $callable)
    {
        $nqueue = new \SplPriorityQueue();
        foreach($this as $p) {
            if ($p['data'] === $callable) continue;
            $nqueue->insert($p['data'], (int) $p['priority']);
        }
        $this->queue = $nqueue;
    }

    /**
     * {@inheritdic}
     */
    public function flush()
    {
        $this->queue = new \SplPriorityQueue();
    }

    /**
     * {@inheritDoc}
     */
    public function combine(
        EventQueueInterface $queue
    )/*# : EventQueueInterface */ {
        $nqueue = clone $this;
        foreach($queue as $data) {
            $nqueue->insert($data['data'], (int) $data['priority']);
        }
        return $nqueue;
    }
}

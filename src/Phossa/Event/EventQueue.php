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
 * Implementation of EventQueueInterface
 *
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     EventQueueInterface
 * @see     \SplPriorityQueue
 * @version 1.0.0
 * @since   1.0.0 added
 */
class EventQueue implements EventQueueInterface
{
    /**
     * the inner queue
     *
     * @var     SplPriorityQueue
     * @type    SplPriorityQueue
     * @access  protected
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

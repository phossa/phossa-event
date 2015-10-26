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
 * EventFactoryInterface
 *
 * Factory pattern used to create event.
 *
 * @interface
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
interface EventFactoryInterface
{
    /**
     * Create a concrete event
     *
     * @param  string $eventName event name
     * @param  mixed $context event context, object or static class name
     * @param  array $properties event properties
     * @return EventInterface
     * @throws Exception\InvalidArgumentException
     *         if $context is not an object or static class name
     * @access public
     * @api
     */
    public function createEvent(
        /*# string */ $eventName,
        $context,
        array $properties = []
    )/*# : EventInterface */;
}

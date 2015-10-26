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
 * A generic event factory to create the base event
 *
 * Why need a factory for such a simple object like event ?
 *
 * - user may extend event class and your event capable want to use the
 *   new event class. so you can pass a new factory which create the new
 *   type of event without hardcode your new event class in your event
 *   capable class
 *
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     EventFactoryInterface
 * @version 1.0.0
 * @since   1.0.0 added
 */
class EventFactory implements EventFactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createEvent(
        /*# string */ $eventName,
        $context,
        array $properties = []
    )/*# : EventInterface */ {
        return new Event($eventName, $context, $properties);
    }
}

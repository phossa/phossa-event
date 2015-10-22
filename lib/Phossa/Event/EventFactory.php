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
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
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

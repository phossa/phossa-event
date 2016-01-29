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
 * A basic implementation of EventManagerInterface
 *
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\Interfaces\EventManagerInterface
 * @version 1.0.2
 * @since   1.0.0 added
 * @since   1.0.2 converted to use trait
 */
class EventManager implements Interfaces\EventManagerInterface
{
    use Interfaces\EventManagerTrait;
}

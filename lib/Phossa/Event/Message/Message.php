<?php
/*
 * Phossa Project
 *
 * @see         http://www.phossa.com/
 * @copyright   Copyright (c) 2015 phossa.com
 * @license     http://mit-license.org/ MIT License
 */
/*# declare(strict_types=1); */

namespace Phossa\Event\Message;

use Phossa\Shared\Message\MessageAbstract;

/**
 * Message class for Phossa\Event
 *
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
class Message extends MessageAbstract
{
    /**#@+
     * @type  int
     */

    /**
     * Event property not found
     */
    const PROPERTY_NOT_FOUND    = 1510101158;

    /**
     * Event manager not found
     */
    const MANAGER_NOT_FOUND     = 1510101159;

    /**
     * Wrong event context
     */
    const WRONG_EVENT_TARGET    = 1510101200;

    /**
     * Wrong event listener
     */
    const WRONG_EVENT_LISTENER  = 1510101201;

    /**
     * Wrong event callable
     */
    const WRONG_EVENT_CALLABLE  = 1510101202;

    /**
     * Callables not found for event
     */
    const CALLABLE_NOT_FOUND    = 1510101203;

    /**
     * Callables runtime exception
     */
    const CALLABLE_RUNTIME      = 1510101204;

    /**
     * Wrong event name
     */
    const WRONG_EVENT_NAME      = 1510101205;

    /**#@-*/

    /**
     * {@inheritDoc}
     */
    protected static $messages = [
        self::PROPERTY_NOT_FOUND    => 'Event "%s" has no property "%s"',
        self::MANAGER_NOT_FOUND     => 'Event manager not set yet in "%s"',
        self::WRONG_EVENT_TARGET    => 'Wrong event context for "%s"',
        self::WRONG_EVENT_LISTENER  => '"%s" is not a event listener',
        self::WRONG_EVENT_CALLABLE  => 'Wrong event callable for "%s"',
        self::CALLABLE_NOT_FOUND    => 'Callables not found for event "%s"',
        self::CALLABLE_RUNTIME      => 'Callable runtime exception: %s',
        self::WRONG_EVENT_NAME      => 'Wrong event name found',
    ];
}

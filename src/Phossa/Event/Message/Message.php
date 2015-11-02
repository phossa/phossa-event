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
 * @see     Phossa\Shared\Message\MessageAbstract
 * @version 1.0.0
 * @since   1.0.0 added
 */
class Message extends MessageAbstract
{
    /**#@+
     * @var   int
     * @type  int
     */

    /**
     * Event "%s" has no property "%s"
     */
    const PROPERTY_NOT_FOUND    = 1510101158;

    /**
     * Event manager not set yet in "%s"
     */
    const MANAGER_NOT_FOUND     = 1510101159;

    /**
     * Invalid event context for "%s"
     */
    const INVALID_EVENT_CONTEXT = 1510101200;

    /**
     * "%s" is not a event listener
     */
    const INVALID_EVENT_LISTENER= 1510101201;

    /**
     * Invalid event callable for "%s"
     */
    const INVALID_EVENT_CALLABLE= 1510101202;

    /**
     * Callables not found for event "%s"
     */
    const CALLABLE_NOT_FOUND    = 1510101203;

    /**
     * Callable runtime exception: %s'
     */
    const CALLABLE_RUNTIME      = 1510101204;

    /**
     * Invalid event name found
     */
    const INVALID_EVENT_NAME    = 1510101205;

    /**#@-*/

    /**
     * {@inheritDoc}
     */
    protected static $messages = [
        self::PROPERTY_NOT_FOUND        => 'Event "%s" has no property "%s"',
        self::MANAGER_NOT_FOUND         => 'Event manager not set yet in "%s"',
        self::INVALID_EVENT_CONTEXT     => 'Invalid event context for "%s"',
        self::INVALID_EVENT_LISTENER    => '"%s" is not a event listener',
        self::INVALID_EVENT_CALLABLE    => 'Invalid event callable for "%s"',
        self::CALLABLE_NOT_FOUND        => 'Callables not found for event "%s"',
        self::CALLABLE_RUNTIME          => 'Callable runtime exception: %s',
        self::INVALID_EVENT_NAME        => 'Invalid event name found',
    ];
}

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

namespace Phossa\Event\Message;

use Phossa\Shared\Message\MessageAbstract;

/**
 * Message class for Phossa\Event
 *
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Shared\Message\MessageAbstract
 * @version 1.0.3
 * @since   1.0.0 added
 */
class Message extends MessageAbstract
{
    /**#@+
     * @var   int
     */

    /**
     * "%s" has no property "%s"
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
     * Invalid event name "%s" found
     */
    const INVALID_EVENT_NAME    = 1510101205;

    /**
     * Invalid event property "%s"
     */
    const INVALID_EVENT_PROPERTY= 1510101206;

    /**
     * Invalid event manager type "%s"
     */
    const INVALID_EVENT_MANAGER = 1510101207;

    /**
     * Immutable method "%s" called
     */
    const IMMUTABLE_EVENT_METHOD= 1510101208;

    /**
     * "%s" has no method "%s"
     */
    const METHOD_NOT_FOUND      = 1510101209;

    /**#@-*/

    /**
     * {@inheritDoc}
     */
    protected static $messages = [
        self::PROPERTY_NOT_FOUND        => '"%s" has no property "%s"',
        self::MANAGER_NOT_FOUND         => 'Event manager not set yet in "%s"',
        self::INVALID_EVENT_CONTEXT     => 'Invalid event context for "%s"',
        self::INVALID_EVENT_LISTENER    => '"%s" is not a event listener',
        self::INVALID_EVENT_CALLABLE    => 'Invalid event callable for "%s"',
        self::CALLABLE_NOT_FOUND        => 'Callables not found for event "%s"',
        self::CALLABLE_RUNTIME          => 'Callable runtime exception: %s',
        self::INVALID_EVENT_NAME        => 'Invalid event name "%s" found',
        self::INVALID_EVENT_PROPERTY    => 'Invalid event property "%s"',
        self::INVALID_EVENT_MANAGER     => 'Invalid event manager type "%s"',
        self::IMMUTABLE_EVENT_METHOD    => 'Immutable method "%s" called',
        self::METHOD_NOT_FOUND          => '"%s" has no method "%s"',
    ];
}

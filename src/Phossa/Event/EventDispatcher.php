<?php
/**
 * Phossa Project
 *
 * PHP version 5.4
 *
 * @category  Library
 * @package   Phossa\Event
 * @copyright 2015 phossa.com
 * @license   http://mit-license.org/ MIT License
 * @link      http://www.phossa.com/
 */
/*# declare(strict_types=1); */

namespace Phossa\Event;

use Phossa\Event\Exception;
use Phossa\Event\Message\Message;
use Phossa\Event\Interfaces\EventInterface;
use Phossa\Event\Interfaces\EventManagerInterface;
use Phossa\Event\Interfaces\EventDispatcherInterface;

/**
 * A basic implementation of EventDispatcherInterface
 *
 * e.g.
 * <code>
 *     $dispatcher = new EventDispatcher();
 *
 *     // bind event 'user.login' to a callable
 *     $dispatcher->on('user.login', function(Event $evt) {
 *          ...
 *     });
 *
 *     // trigger the 'user.login' event
 *     $dispatcher->trigger('user.login', [ 'user' => $user ]);
 * </code>
 *
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\Interfaces\EventDispatcherInterface
 * @version 1.0.3
 * @since   1.0.2 added
 * @since   1.0.3 changed to use trait
 */
class EventDispatcher implements EventDispatcherInterface
{
    use Interfaces\EventDispatcherTrait;

    /**
     * dispatcher instances
     *
     * @var    EventDispatcherInterface[]
     * @access private
     * @staticvar
     */
    private static $instances = [];

    /**
     * Constructor, setup event related stuff
     *
     * Optionally inject the event manager object. also, may provides a event
     * prototype if you are not using the default `Event` class
     *
     * <code>
     *     $dispatcher = new EventDispatcher(
     *         $globalEventManager,
     *         new MyEvent('prototype')
     *     );
     * </code>
     *
     * @param  EventManagerInterface $eventManager
     * @param  EventInterface $eventPrototype
     * @access public
     * @api
     */
    public function __construct(
        EventManagerInterface $eventManager = null,
        EventInterface $eventPrototype = null
    ) {
        // set event manager
        $this->event_manager = $eventManager ?: new EventManager();

        // set event prototype
        $this->event_proto   = $eventPrototype ?: new Event('prototype');
    }

    /**
     * Provides a static interface for all methods
     *
     * @param  string $name method name
     * @param  array $arguments arguments
     * @return mixed
     * @access public
     * @static
     */
    public static function __callStatic($name, array $arguments)
    {
        $dispatcher = static::get();
        if (method_exists($dispatcher, $name)) {
            return call_user_func_array([$dispatcher, $name], $arguments);
        }

        throw new Exception\BadMethodCallException(
            Message::get(
                Message::METHOD_NOT_FOUND,
                get_called_class(),
                $name
            ),
            Message::METHOD_NOT_FOUND
        );
    }

    /**
     * Set a dispatcher to be used by static methods
     *
     * @param  EventDispatcherInterface $dispatcher dispatcher to use
     * @return void
     * @access public
     * @static
     * @api
     */
    public static function set(EventDispatcherInterface $dispatcher)
    {
        $class = get_called_class();
        self::$instances[$class] = $dispatcher;
    }

    /**
     * Get a dispatcher to be used by static methods.
     *
     * Return a newly created dispatcher if it is not set
     *
     * @return EventDispatcherInterface
     * @access public
     * @static
     * @api
     */
    public static function get()/*# : EventDispatcherInterface */
    {
        $class = get_called_class();
        if (!isset(self::$instances[$class])) {
            self::$instances[$class] = new static();
        }
        return self::$instances[$class];
    }
}

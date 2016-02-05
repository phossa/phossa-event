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

namespace Phossa\Event\Interfaces;

use Phossa\Event\Exception;

/**
 * EventDispatcherInterface
 *
 * @interface
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.3
 * @since   1.0.2 added
 * @since   1.0.3 removed ::set()/::get()
 */
interface EventDispatcherInterface
{
    /**
     * Attach a callable to event name
     *
     * e.g.
     * <code>
     *     $dispatcher = new EventDispatcher();
     *
     *     // bind event 'user.login' to a callable
     *     $dispatcher->on('user.login', function(Event $evt) {
     *          $user = $evt->getProperty('user');
     *          ...
     *     });
     *
     *     // trigger the 'user.login' event with some data
     *     $dispatcher->trigger('user.login', [ 'user' => $user ]);
     * </code>
     *
     * @param  string $eventName event name
     * @param  callable $callable the callable
     * @param  int $priority (optional) high number higher priority, 0 - 100
     * @return this
     * @access public
     * @api
     */
    public function on(
        /*# string */ $eventName,
        callable $callable,
        /*# int */ $priority = 50
    );

    /**
     * Attach a callable to event name and execute only once.
     *
     * @param  string $eventName event name
     * @param  callable $callable the callable
     * @param  int $priority (optional) high number higher priority, 0 - 100
     * @return this
     * @access public
     * @api
     */
    public function one(
        /*# string */ $eventName,
        callable $callable,
        /*# int */ $priority = 50
    );

    /**
     * Attach a callable to event name and execute that many times.
     *
     * @param  string $eventName event name
     * @param  int $times times to execute
     * @param  callable $callable the callable
     * @param  int $priority (optional) high number higher priority, 0 - 100
     * @return this
     * @access public
     * @api
     */
    public function many(
        /*# string */ $eventName,
        /*# int */ $times,
        callable $callable,
        /*# int */ $priority = 50
    );

    /**
     * Does $eventName have registered handlers
     *
     * @param  string $eventName event name
     * @return bool
     * @access public
     * @api
     */
    public function has(/*# string */ $eventName)/*# : bool */;

    /**
     * Detach a callable from event name.
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
     *     // turn off 'user.login' event handling
     *     $dispatcher->off('user.login');
     *
     *     // turn off everything !!
     *     $dispatcher->off();
     * </code>
     *
     * @param  string $eventName (optional) event name
     * @param  callable $callable (optional) the callable
     * @return this
     * @access public
     * @api
     */
    public function off(
        /*# string */ $eventName = '',
        callable $callable = null
    );

    /**
     * Execute a callable when PHP error happens
     *
     * The callable signature is
     * <code>
     *     function errorHandler($errno, $errstr, $errfile, $errline):bool {
     *     }
     * </code>
     *
     * @param  callable $callable the callable
     * @return this
     * @access public
     * @api
     */
    public function error(callable $callable);

    /**
     * Execute a callable when script ends
     *
     * @param  callable $callable the callable
     * @return this
     * @access public
     * @api
     */
    public function ready(callable $callable);

    /**
     * Trigger an event and process it
     *
     * @param  string $eventName event name
     * @param  array $data data pass to $event->setProperties($data)
     * @param  bool $persistent same $eventName will use persistent/same event
     * @return array results from $event->getResults
     * @throws Exception\InvalidArgumentException if $eventName not right
     * @throws Exception\RuntimeException
     *         rethrow any exception catched as RuntimeException
     * @access public
     * @api
     */
    public function trigger(
        /*# string */ $eventName,
        array $data = [],
        /*# bool */ $persistent = true
    )/*# : array */;

    /**
     * Is an event triggered before
     *
     * @param  string $eventName event name
     * @return bool
     * @access public
     * @api
     */
    public function isTriggered(/*# string */ $eventName)/*# : bool */;
}

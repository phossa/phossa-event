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
use Phossa\Event\EventQueue;
use Phossa\Event\Message\Message;

/**
 * Implementation of EventManagerInterface
 *
 * @trait
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\Interfaces\EventManagerInterface
 * @version 1.0.3
 * @since   1.0.0 added
 */
trait EventManagerTrait
{
    /**
     * Events pool
     *
     * @var    EventQueueInterface[]
     * @access protected
     */
    protected $events = [];

    /**
     * {@inheritDoc}
     */
    public function processEvent(
        EventInterface $event,
        callable $callback = null
    )/*# : EventManagerInterface */ {
        // check self's queue
        $queue = $this->matchEventQueue($event->getName(), $this);

        // run thru the queue
        return $this->runEventQueue($event, $queue, $callback);
    }

    /**
     * {@inheritDoc}
     */
    public function attachListener(
        $listener,
        /*# string */ $eventName = '',
        /*# int */ $priority = 50
    )/*# : EventManagerInterface */ {
        // attach a callable
        if (is_callable($listener)) {
            // attach a callable directly to the event
            $this->attachIt($eventName, null, $listener, (int) $priority);

        // attach a listener object or static class
        } else {
            // get listener's events
            $evts = $this->getListenerEvents($listener, $eventName);

            // attach events
            foreach ($evts as $name => $callable) {
                $this->attachIt($name, $listener, $callable, (int) $priority);
            }
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function detachListener(
        $listener,
        /*# string */ $eventName = ''
    )/*# : EventManagerInterface */ {
        // detach a callable
        if (is_callable($listener)) {
            // detach a callable directly from the event
            $this->detachIt($eventName, null, $listener);

        // clear $eventName queue
        } elseif (is_null($listener)) {
            if ($eventName) {
                // clear $eventName queue
                $this->clearEventQueue($eventName);
            } else {
                // turn off everything
                $this->events = [];
            }
        // detach a listener object or static class
        } else {
            // get listener's events
            $evts = $this->getListenerEvents($listener, $eventName);

            // detach events
            foreach ($evts as $name => $callable) {
                $this->detachIt($name, $listener, $callable);
            }
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function hasEventQueue(/*# string */ $eventName)/*# : bool */
    {
        // event name has to be STRING
        if (!is_string($eventName)) {
            trigger_error(
                Message::get(Message::INVALID_EVENT_NAME, $eventName),
                E_USER_WARNING
            );
            return false;
        }

        // found event queue
        if (isset($this->events[$eventName]) &&
            $this->events[$eventName]->count()
        ) {
            return true;
        }

        // not found
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getEventQueue(
        /*# string */ $eventName
    )/*# : EventQueueInterface */ {
        if ($this->hasEventQueue($eventName)) {
            return $this->events[$eventName];
        }
        throw new Exception\NotFoundException(
            Message::get(
                Message::CALLABLE_NOT_FOUND,
                $eventName
            ),
            Message::CALLABLE_NOT_FOUND
        );
    }

    /**
     * {@inheritDoc}
     */
    public function clearEventQueue(
        /*# string */ $eventName
    )/*# : EventManagerInterface */ {
        if ($eventName && $this->hasEventQueue($eventName)) {
            unset($this->events[$eventName]);
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getEventNames()/*# : array */
    {
        return array_keys($this->events);
    }

    /**
     * Run an event with the provided queue
     *
     * The $callback defined as `function($event, $response) {}`, where
     * $reponse is the return value from previous event handler.
     *
     * @param  EventInterface $event
     * @param  EventQueueInterface $queue
     * @param  callable $callback (optional) a callback returns bool
     * @return EventManagerInterface this
     * @throws \Phossa\Event\Exception\RuntimeException
     *         rethrow any exception catched as RuntimeException
     * @access protected
     */
    protected function runEventQueue(
        EventInterface $event,
        EventQueueInterface $queue,
        callable $callback = null
    )/*# : EventManagerInterface */ {
        try {
            foreach ($queue as $data) {
                // execute each callable from the queue
                $res = call_user_func($data['data'], $event);

                // set results
                $event->setResult($res);

                // stop propagation if callable returns FALSE
                if ($res === false) {
                    $event->stopPropagation();
                }

                // break if event stopped
                if ($event->isPropagationStopped()) {
                    break;
                }

                // run callback
                if ($callback &&
                    call_user_func($callback, $event, $res) === false
                ) {
                    break;
                }
            }

            return $this;
        } catch (\Exception $e) {
            // rethrow any exception caught
            throw new Exception\RuntimeException(
                Message::get(Message::CALLABLE_RUNTIME, $e->getMessage()),
                $e->getCode(),
                $e
            );
        }
    }

    /**
     * Test if it is a valid listener
     *
     * @param  EventListenerInterface|string $listener object/static class name
     * @return bool
     * @access protected
     */
    protected function isListener($listener)/*# : bool */
    {
        // object
        if (is_object($listener) &&
            $listener instanceof EventListenerInterface) {
            return true;
        }

        // static class name
        if (is_string($listener) &&
            is_a(
                $listener,
                '\\Phossa\\Event\\Interfaces\\EventListenerStaticInterface',
                true
            )) {
            return true;
        }

        return false;
    }

    /**
     * Get listener events array
     *
     * @param  EventListenerInterface|string $listener object/static class name
     * @param  string $eventName event name or '' for all events
     * @return array
     * @throws Exception\InvalidArgumentException
     *         if $listener not a valid listener
     * @access protected
     */
    protected function getListenerEvents(
        $listener,
        /*# string */ $eventName = ''
    )/*# : array */ {
        // check listener
        if (!$this->isListener($listener)) {
            throw new Exception\InvalidArgumentException(
                Message::get(
                    Message::INVALID_EVENT_LISTENER,
                    is_object($listener) ?
                        get_class($listener) :
                        $listener
                ),
                Message::INVALID_EVENT_LISTENER
            );
        }

        // get listener's listening event definition array
        if (is_object($listener)) {
            /* @var $listener EventListenerInterface */
            $evts = $listener->getEventsListening();
        } else {
            /* @var $listener EventListenerStaticInterface */
            $evts = $listener::getEventsListening();
        }

        if ($eventName != '') {
            foreach (array_keys($evts) as $name) {
                if ($name !== $eventName) {
                    unset($evts[$name]);
                }
            }
        }

        return $evts;
    }

    /**
     * Returns an array of callables
     *
     * @param  EventListenerInterface|string $listener object/static class name
     * @param  mixed $callable user defined callables
     * @param  int $priority priority
     * @return array
     * @access protected
     */
    protected function makeCallables(
        $listener,
        $callable,
        /*# int */ $priority
    )/*# : array */ {
        $result = [];
        if (is_array($callable)) {
            if (isset($callable[1]) && is_int($callable[1])) {
                $priority = $callable[1];
                $xc = $callable[0];
            } else {
                foreach ($callable as $cc) {
                    $result = array_merge(
                        $result,
                        $this->makeCallables($listener, $cc, $priority)
                    );
                }
                return $result;
            }
        } elseif (is_string($callable)) {
            $xc = $callable;
        }

        if (isset($xc)) {
            if (!is_callable($xc)) {
                $xc = array($listener, $xc);
            }
            if (is_callable($xc)) {
                $result[] = [ $xc, $priority ];
            }
        }
        return $result;
    }

    /**
     * Attach callable to event queque
     *
     * @param  string $eventName event name
     * @param  EventListenerInterface|string|null $listener
     *         object/static class name or a callable
     * @param  mixed $callable string | array
     * @param  int $priority priority integer
     * @return void
     * @throws Exception\InvalidArgumentException
     *         if callable is the wrong format
     * @access protected
     */
    protected function attachIt(
        /*# string */ $eventName,
        $listener,
        $callable,
        /*# int */ $priority
    ) {
        // get named event queue
        if ($this->hasEventQueue($eventName)) {
            $q = $this->getEventQueue($eventName);
        } else {
            $q = new EventQueue();
            $this->events[$eventName] = $q;
        }

        // attach callable directory
        if (is_null($listener)) {
            // need $eventName
            if (empty($eventName)) {
                throw new Exception\InvalidArgumentException(
                    Message::get(Message::INVALID_EVENT_NAME, 'EMPTY'),
                    Message::INVALID_EVENT_NAME
                );
            }
            $xc = [
                [ $callable, (int) $priority ]
            ];
        // get callables from listener object or static listener class
        } else {
            $xc = $this->makeCallables($listener, $callable, (int) $priority);
        }

        if (empty($xc)) {
            throw new Exception\InvalidArgumentException(
                Message::get(Message::INVALID_EVENT_CALLABLE, $eventName),
                Message::INVALID_EVENT_CALLABLE
            );
        } else {
            foreach ($xc as $c) {
                $q->insert($c[0], $c[1]);
            }
        }
    }

    /**
     * Detach callable to event queque
     *
     * @param  string $eventName event name
     * @param  EventListenerInterface|string|null $listener
     *         object/static class name or a callable
     * @param  mixed $callable string | array
     * @return void
     * @throws Exception\InvalidArgumentException
     *         if callable is the wrong format
     * @access protected
     */
    protected function detachIt(
        /*# string */ $eventName,
        $listener,
        $callable
    ) {
        // queue not found
        if ($eventName != '' && !$this->hasEventQueue($eventName)) {
            return;
        }

        // detach callable directly
        if (is_null($listener)) {
            $xc = [
                [ $callable, 50 ]
            ];
        } else {
            // get callables from listener object or static listener class
            $xc = $this->makeCallables($listener, $callable, 50);
        }

        if (empty($xc)) {
            throw new Exception\InvalidArgumentException(
                Message::get(Message::INVALID_EVENT_CALLABLE, $eventName),
                Message::INVALID_EVENT_CALLABLE
            );
        } else {
            if ($eventName != '') {
                $q = $this->getEventQueue($eventName);
                foreach ($xc as $c) {
                    $q->remove($c[0]);
                }
                if ($q->count() === 0) {
                    unset($this->events[$eventName]);
                }
            } else {
                $names = $this->getEventNames();
                foreach ($names as $n) {
                    $q = $this->getEventQueue($n);
                    foreach ($xc as $c) {
                        $q->remove($c[0]);
                    }
                    if ($q->count() === 0) {
                        unset($this->events[$n]);
                    }
                }
            }
        }
    }

    /**
     * Match proper event queue with globbing support
     *
     * Including queues with globbing event names
     *
     * @param  string $eventName event name to match
     * @param  EventManagerInterface $manager which manager to look at
     * @return EventQueueInterface
     * @access protected
     */
    protected function matchEventQueue(
        /*# string */ $eventName,
        EventManagerInterface $manager
    )/*# : EventQueueInterface */ {
        $names = $this->globNames($eventName, $manager->getEventNames());

        // combine all globbing queue
        $queue = new EventQueue();
        foreach ($names as $n) {
            if ($manager->hasEventQueue($n)) {
                $queue = $queue->combine($manager->getEventQueue($n));
            }
        }

        return $queue;
    }

    /**
     * Return those globbing event names based on the given one
     *
     * e.g.
     * Matching specific name 'login.MobileFail' with those '*',
     * 'login.*', 'login.*Fail'
     *
     * @param  string $eventName specific event name
     * @param  string[] $names name array to search thru
     * @return string[]
     * @access protected
     */
    protected function globNames(
        /*# string */ $eventName,
        array $names
    )/*# : array */ {
        $result = [];
        foreach ($names as $n) {
            if ($n === '*' || $n === $eventName || $eventName === '') {
                $result[] = $n;
            } elseif (strpos($n, '*') !== false) {
                $regex = str_replace(array('.', '*'), array('[.]', '.*?'), $n);
                if (preg_match('/^'.$regex.'$/', $eventName)) {
                    $result[] = $n;
                }
            }
        }
        return $result;
    }
}

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

namespace Phossa\Event;

use Phossa\Event\Message\Message;
use Phossa\Event\Interfaces\EventInterface;

/**
 * Basic event class
 *
 * Simple usage:
 * <code>
 *     // $this is current context
 *     $evt = new Event(
 *         'login.attempt',         // event name
 *         $this,                   // event context
 *         ['username' => 'phossa'] // event properties
 *     );
 *
 *     // stop event propagation
 *     $evt->stopPropagation();
 * </code>
 *
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\Interfaces\EventInterface
 * @version 1.0.3
 * @since   1.0.0 added
 * @since   1.0.2 added setResult()/getResults()/__invoke()
 */
class Event implements EventInterface
{
    /**
     * event name
     *
     * @var    string
     * @access protected
     */
    protected $name;

    /**
     * event context
     *
     * an object OR static class name (string)
     *
     * @var    object|string
     * @access protected
     */
    protected $context = null;

    /**
     * event properties
     *
     * @var    array
     * @access protected
     */
    protected $properties;

    /**
     * results from handlers
     *
     * @var    array
     * @access protected
     */
    protected $results = [];

    /**
     * stop propagation
     *
     * @var    bool
     * @access protected
     */
    protected $stopped = false;

    /**
     * Constructor
     *
     * @param  string $eventName event name
     * @param  mixed $context (optional) event context, object/static classname
     * @param  array $properties (optional) event properties
     * @throws Exception\InvalidArgumentException
     *         if $eventName empty or $context is not the right type
     * @access public
     * @api
     */
    public function __construct(
        /*# string */ $eventName,
        $context = null,
        array $properties = []
    ) {
        $this->setName($eventName)
             ->setContext($context)
             ->setProperties($properties);
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(
        /*# string */ $eventName,
        $context = null,
        array $properties = []
    ) {
        return $this->setName($eventName)
             ->setContext($context)
             ->setProperties($properties);
    }

    /**
     * {@inheritDoc}
     */
    public function setName(/*# string */ $eventName)/*# : EventInterface */
    {
        if (!is_string($eventName) || trim($eventName) === '') {
            throw new Exception\InvalidArgumentException(
                Message::get(
                    Message::INVALID_EVENT_NAME,
                    $eventName
                ),
                Message::INVALID_EVENT_NAME
            );
        }
        $this->name = trim($eventName);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()/*# : string */
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function setContext($context)/*# : EventInterface */
    {
        // null context
        if (is_null($context)) {
            return $this;
        }

        // right context
        if (is_object($context) ||
            is_string($context) && class_exists($context, false)) {
            $this->context = $context;
            return $this;
        }

        // not right
        throw new Exception\InvalidArgumentException(
            Message::get(
                Message::INVALID_EVENT_CONTEXT,
                $this->getName()
            ),
            Message::INVALID_EVENT_CONTEXT
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * {@inheritDoc}
     */
    public function hasProperty(/*# string */ $name)/*#: bool */
    {
        if (!is_scalar($name)) {
            return false;
        }
        return isset($this->properties[(string) $name]);
    }

    /**
     * {@inheritDoc}
     */
    public function getProperty(/*# string */ $name)
    {
        if ($this->hasProperty($name)) {
            return $this->properties[(string) $name];
        }

        // not found
        throw new Exception\NotFoundException(
            Message::get(
                Message::PROPERTY_NOT_FOUND,
                $this->getName(),
                $name
            ),
            Message::PROPERTY_NOT_FOUND
        );
    }

    /**
     * {@inheritDoc}
     */
    public function setProperty(
        /*# string */ $name,
        $value
    )/*# : EventInterface */ {
        if (!is_scalar($name)) {
            throw new Exception\InvalidArgumentException(
                Message::get(
                    Message::INVALID_EVENT_PROPERTY,
                    $name
                ),
                Message::INVALID_EVENT_PROPERTY
            );
        }
        $this->properties[(string) $name] = $value;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getProperties()/*# : array */
    {
        return $this->properties;
    }

    /**
     * {@inheritDoc}
     */
    public function setProperties(
        array $properties,
        /*# bool */ $merge = false
    )/*# : EventInterface */ {
        if ($merge) {
            foreach ($properties as $n => $v) {
                $this->setProperty($n, $v);
            }
        } else {
            $this->properties = $properties;
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setResult(
        $result,
        /*# string */ $id = ''
    )/*# : EventInterface */ {
        if ($id) {
            $this->results[$id] = $result;
        } else {
            $this->results[] = $result;
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getResults()/*# : array */
    {
        return $this->results;
    }

    /**
     * {@inheritDoc}
     */
    public function stopPropagation()/*# : EventInterface */
    {
        $this->stopped = true;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function isPropagationStopped()/*# : bool */
    {
        return $this->stopped;
    }
}

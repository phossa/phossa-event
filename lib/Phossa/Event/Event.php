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

use Phossa\Event\Message\Message;

/**
 * Base event class
 *
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
class Event implements EventInterface
{
    /**
     * event name
     *
     * @var    string
     * @type   string
     * @access protected
     */
    protected $name = '';

    /**
     * event context
     *
     * it is an object or string (static class name)
     *
     * @var    mixed
     * @type   mixed
     * @access protecteds
     */
    protected $context = null;

    /**
     * event properties
     *
     * @var    array
     * @type   array
     * @access protected
     */
    protected $properties = [];

    /**
     * stop propagation
     *
     * @var    bool
     * @type   bool
     * @access protected
     */
    protected $stopped = false;

    /**
     * Constructor
     *
     * @param  string $eventName event name
     * @param  mixed $context event context, object or static class name
     * @param  array $properties event properties
     * @throws Exception\InvalidArgumentException
     *         if $eventName empty or $context is not the right context type
     * @access public
     * @api
     */
    public function __construct(
        /*# string */ $eventName,
        $context,
        array $properties = []
    ) {
        $this->setName($eventName)
             ->setContext($context)
             ->setProperties($properties);
    }

    /**
     * {@inheritDoc}
     */
    public function setName(
        /*# string */ $eventName
    )/*# : EventInterface */ {
        if (!is_string($eventName) || $eventName === '') {
            throw new Exception\InvalidArgumentException(
                Message::get(
                    Message::WRONG_EVENT_NAME
                ),
                Message::WRONG_EVENT_NAME
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
    public function setContext($context)/*# : EventInterface */ {
        if (is_object($context) ||
            is_string($context) && class_exists($context, false)) {
            $this->context = $context;
            return $this;
        }
        throw new Exception\InvalidArgumentException(
            Message::get(
                Message::WRONG_EVENT_TARGET,
                $this->getName()
            ),
            Message::WRONG_EVENT_TARGET
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
    )/*# : EventInterface */
    {
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
    public function setProperties(array $properties)/*# : EventInterface */
    {
        $this->properties = $properties;
        return $this;
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

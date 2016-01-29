<?php
/*
 * Phossa Project
 *
 * @see         http://www.phossa.com/
 * @copyright   Copyright (c) 2015 phossa.com
 * @license     http://mit-license.org/ MIT License
 */
/*# declare(strict_types=1); */

namespace Phossa\Event\Interfaces;

/**
 * Event interface
 *
 * @interface
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.2
 * @since   1.0.0 added
 * @since   1.0.2 added setResults()/getResults()/__invoke()
 */
interface EventInterface
{
    /**
     * Force the __invoke(), used after protyping cloning
     *
     * @param  string $eventName event name
     * @param  mixed $context event context, object or static class name
     * @param  array $properties (optional) event properties
     * @return this
     * @throws Exception\InvalidArgumentException
     *         if $eventName empty or $context is not the right type
     * @access public
     * @api
     */
    public function __invoke(
        /*# string */ $eventName,
        $context = null,
        array $properties = []
    );

    /**
     * Set event name
     *
     * @param  string $eventName event name
     * @return this
     * @throws \Phossa\Event\Exception\InvalidArgumentException
     *         if $eventName empty or not a string
     * @access public
     * @api
     */
    public function setName(
        /*# string */ $eventName
    )/*# : EventInterface */;

    /**
     * Get event name
     *
     * @param  void
     * @return string
     * @access public
     * @api
     */
    public function getName()/*# : string */;

    /**
     * Set event context, usually an object or static class name
     *
     * @param  object|string $context object or static classname
     * @return this
     * @throws \Phossa\Event\Exception\InvalidArgumentException
     *         if context not an object or not static class name
     * @access public
     * @api
     */
    public function setContext($context)/*# : EventInterface */;

    /**
     * Get event context, usually an object or static class name
     *
     * @param  void
     * @return object|string
     * @access public
     * @api
     */
    public function getContext();

    /**
     * Has event property with $name
     *
     * Try use this hasProperty() before getProperty() to avoid exception
     * Always return false if $name is not a string. Enforce strong type
     * checking.
     *
     * @param  string $name property name
     * @return bool
     * @access public
     * @api
     */
    public function hasProperty(/*# string */ $name)/*#: bool */;

    /**
     * Get event property with $name
     *
     * Always not found if $name is not a string
     *
     * @param  string $name property name
     * @return mixed
     * @throws \Phossa\Event\Exception\NotFoundException
     *         if no property with $name found
     * @access public
     * @api
     */
    public function getProperty(/*# string */ $name);

    /**
     * Set the event property
     *
     * @param  string $name property name
     * @param  mixed $value property value
     * @return this
     * @throws \Phossa\Event\Exception\InvalidArgumentException
     *         if $name is not a string
     * @access public
     * @api
     */
    public function setProperty(
        /*# string */ $name,
        $value
    )/*# : EventInterface */;

    /**
     * Get event's all properties in array
     *
     * @param  void
     * @return array
     * @access public
     * @api
     */
    public function getProperties()/*# : array */;

    /**
     * Set the event all properties
     *
     * @param  array $properties property array
     * @param  bool $merge (optional) merge with existing properties
     * @return this
     * @access public
     * @since  1.0.2 added $merge param
     * @api
     */
    public function setProperties(
        array $properties,
        /*# bool */ $merge = false
    )/*# : EventInterface */;

    /**
     * Set results from event handlers
     *
     * @param  mixed $result the result from event handler
     * @param  string $id (optional) id for this result
     * @return this
     * @access public
     * @since  1.0.2 added
     * @api
     */
    public function setResults(
        $result,
        /*# string */ $id = ''
    )/*# : EventInterface */;

    /**
     * Get results from event handlers
     *
     * @param  void
     * @return array
     * @access public
     * @since  1.0.2 added
     * @api
     */
    public function getResults()/*# : array */;

    /**
     * Stop event propagation
     *
     * @param  void
     * @return this
     * @access public
     * @api
     */
    public function stopPropagation()/*# : EventInterface */;

    /**
     * Is event propagation stopped
     *
     * @param  void
     * @return bool
     * @access public
     * @api
     */
    public function isPropagationStopped()/*# : bool */;
}

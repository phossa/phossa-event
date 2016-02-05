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

/**
 * Event interface
 *
 * @interface
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.3
 * @since   1.0.0 added
 * @since   1.0.2 added setResult()/getResults()/__invoke()
 */
interface EventInterface
{
    /**
     * Force the __invoke(), used after protyping cloning
     *
     * <code>
     * // if prototype is set
     * if (is_object($this->event_prototype)) {
     *    // clone the prototype
     *    $evt = clone $this->event_prototype;
     *
     *    // set name etc. with the new event
     *    $evt($eventName, null, $data);
     * }
     * </code>
     *
     * @param  string $eventName event name
     * @param  mixed $context event context, object or static class name
     * @param  array $properties (optional) event properties
     * @return EventInterface
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
     * @return EventInterface
     * @throws \Phossa\Event\Exception\InvalidArgumentException
     *         if $eventName empty or not a string
     * @access public
     * @api
     */
    public function setName(/*# string */ $eventName)/*# : EventInterface */;

    /**
     * Get event name
     *
     * @return string
     * @access public
     * @api
     */
    public function getName()/*# : string */;

    /**
     * Set event context, usually an object or static class name
     *
     * @param  object|string $context object or static classname
     * @return EventInterface
     * @throws \Phossa\Event\Exception\InvalidArgumentException
     *         if context not an object or not static class name
     * @access public
     * @api
     */
    public function setContext($context)/*# : EventInterface */;

    /**
     * Get event context, usually an object or static class name
     *
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
     * @return EventInterface
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
     * @return EventInterface
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
     * @return EventInterface
     * @access public
     * @since  1.0.2 added
     * @api
     */
    public function setResult(
        $result,
        /*# string */ $id = ''
    )/*# : EventInterface */;

    /**
     * Get results from event handlers
     *
     * @return array
     * @access public
     * @since  1.0.2 added
     * @api
     */
    public function getResults()/*# : array */;

    /**
     * Stop event propagation
     *
     * @return EventInterface
     * @access public
     * @api
     */
    public function stopPropagation()/*# : EventInterface */;

    /**
     * Is event propagation stopped
     *
     * @return bool
     * @access public
     * @api
     */
    public function isPropagationStopped()/*# : bool */;
}

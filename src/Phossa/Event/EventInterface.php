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

/**
 * Event interface
 *
 * @interface
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.0
 * @since   1.0.0 added
 */
interface EventInterface
{
    /**
     * Set event name
     *
     * @param  string $eventName event name
     * @return EventInterface $this
     * @throws Exception\InvalidArgumentException
     *         if $eventName empty or not string
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
     * @param  mixed object or static classname
     * @return EventInterface $this
     * @throws Exception\InvalidArgumentException
     *         if context not an object or static class name
     * @access public
     * @api
     */
    public function setContext($context)/*# : EventInterface */;

    /**
     * Get event context, usually an object or static class name
     *
     * @param  void
     * @return mixed object or static classname
     * @access public
     * @api
     */
    public function getContext();

    /**
     * Has event property with $name
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
     * @param  string $name property name
     * @return mixed
     * @throws Exception\NotFoundException if no property with $name found
     * @access public
     * @api
     */
    public function getProperty(/*# string */ $name);

    /**
     * Set the event property
     *
     * @param  string $name property name
     * @param  mixed $value property value
     * @return EventInterface $this
     * @access public
     * @api
     */
    public function setProperty(
        /*# string */ $name,
        $value
    )/*# : EventInterface */;

    /**
     * Get event all properties array
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
     * @return EventInterface $this
     * @access public
     * @api
     */
    public function setProperties(array $properties)/*# : EventInterface */;

    /**
     * Stop event propagation
     *
     * @param  void
     * @return EventInterface $this
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

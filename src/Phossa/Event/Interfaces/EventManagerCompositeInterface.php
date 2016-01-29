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
 * Provides advanced features other than EventManagerInterface
 *
 * - Set extra managers, such as global or shared managers, and able to trigger
 *   callables in these managers in the event.
 *
 * - Not able to recursively trigger extra managers' extra managers !
 *
 * @interface
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.2
 * @since   1.0.0 added
 */
interface EventManagerCompositeInterface
{
    /**
     * Set extra event managers, global, shared, peer etc.
     *
     * @param  string $name unique name for this manager
     * @param  EventManagerInterface $manager
     * @return this
     * @throws \Phossa\Event\Exception\InvalidArgumentException
     *         if $manager is also EventManagerCompositeInterface
     * @access public
     * @api
     */
    public function setOtherManager(
        /*# string */ $name,
        EventManagerInterface $manager
    )/*# : EventManagerCompositeInterface */;

    /**
     * Unset extra manager
     *
     * @param  string $name manager name (id)
     * @return this
     * @access public
     * @api
     */
    public function unsetOtherManager(
        /*# string */ $name
    )/*# : EventManagerCompositeInterface */;

    /**
     * Get other managers in array [ name => $manager ]
     *
     * @param  void
     * @return EventManagerInterface[]
     * @access public
     * @api
     */
    public function getOtherManagers()/*# : array */;
}

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
 * EventListenerInterface
 *
 * @interface
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.3
 * @since   1.0.0 added
 */
interface EventListenerInterface
{
    /**
     * Get the list of events $this is listening
     *
     * e.g.
     * <code>
     * public function getEventsListening()
     * {
     *     return array(
     *         eventName1 => 'method1', // method1 of $this
     *         eventName2 => array('method2', 20), // priority 20
     *         eventName3 => array(
     *             [ 'method3', 70 ],
     *             [ 'method4', 50 ]
     *         )
     *     );
     * }
     * </code>
     *
     * @return array array of events handling
     * @access public
     * @api
     */
    public function getEventsListening()/*# : array */;
}

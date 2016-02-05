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
 * Static version of EventListenerInterface
 *
 * @interface
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @version 1.0.3
 * @since   1.0.0 added
 */
interface EventListenerStaticInterface
{
    /**
     * Get the list of events this static class is listening
     *
     * e.g.
     * <code>
     * public static function getEventsListening()
     * {
     *     return array(
     *         eventName1 => 'method1', // method1 of static class
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
     * @static
     * @api
     */
    public static function getEventsListening()/*# : array */;
}

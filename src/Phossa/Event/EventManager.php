<?php
/**
 * Phossa Project
 *
 * PHP version 5.4
 *
 * @category  Library
 * @package   Phossa\Event
 * @copyright 2015 phossa.com
 * @license   http://mit-license.org/ MIT License
 * @link      http://www.phossa.com/
 */
/*# declare(strict_types=1); */

namespace Phossa\Event;

/**
 * A basic implementation of EventManagerInterface
 *
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\Interfaces\EventManagerInterface
 * @version 1.0.3
 * @since   1.0.0 added
 * @since   1.0.2 converted to use trait
 */
class EventManager implements Interfaces\EventManagerInterface
{
    use Interfaces\EventManagerTrait;
}

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

namespace Phossa\Event\Exception;

use Phossa\Shared\Exception\RuntimeException as RException;

/**
 * RuntimeException for \Phossa\Event
 *
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\Exception\ExceptionInterface
 * @see     \Phossa\Shared\Exception\RuntimeException
 * @version 1.0.3
 * @since   1.0.0 added
 */
class RuntimeException extends RException implements ExceptionInterface
{
}

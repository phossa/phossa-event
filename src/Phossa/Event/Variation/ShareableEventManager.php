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

namespace Phossa\Event\Variation;

use Phossa\Event\Interfaces\EventManagerInterface;

/**
 * You can have lots of event managers but only one global copy
 *
 * <code>
 *     // get global copy by using `getShareable()`
 *     $globalEventManager = ShareableEventManager::getShareable();
 *
 *     // normal event managers
 *     $localEventManager  = new ShareableEventManager();
 *
 *     // is this the global copy?
 *     if ($evtManager->isShareable()) {
 *
 *     } else {
 *
 *     }
 * </code>
 *
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\Interfaces\EventManagerInterface
 * @version 1.0.3
 * @since   1.0.2 added
 */
class ShareableEventManager implements
    EventManagerInterface,
    \Phossa\Shared\Pattern\ShareableInterface
{
    use \Phossa\Event\Interfaces\EventManagerTrait,
        \Phossa\Shared\Pattern\ShareableTrait;
}

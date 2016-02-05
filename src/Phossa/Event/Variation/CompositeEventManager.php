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

use Phossa\Event\Interfaces;
use Phossa\Event\EventManager;

/**
 * Implementation of EventManagerCompositeInterface
 *
 * A composite event manager dispatches events not only to its own listeners
 * but also to other event managers.
 *
 * e.g.
 * <code>
 *      $local_manager  = new EventManagerComposite();
 *      $global_manager = new EventManager();
 *      $local_manager->setOtherManager('global', $global_manager);
 *
 *      $evt = new Event('oneEvent');
 *
 *      // which will look into $local_manager's event queue,
 *      // and also the $global_manager's event queue
 *      $local_manager->processEvent($evt);
 * </code>
 *
 * @package Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\EventManager
 * @see     \Phossa\Event\Interfaces\EventManagerCompositeInterface
 * @version 1.0.3
 * @since   1.0.0 added
 * @since   1.0.2 converted to use trait
 */
class CompositeEventManager extends EventManager implements
    Interfaces\EventManagerCompositeInterface
{
    use Interfaces\EventManagerCompositeTrait;
}

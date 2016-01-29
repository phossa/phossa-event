<?php
/*
 * Phossa Project
 *
 * @see         http://www.phossa.com/
 * @copyright   Copyright (c) 2015 phossa.com
 * @license     http://mit-license.org/ MIT License
 */
/*# declare(strict_types=1); */

namespace Phossa\Event\Variation;

use Phossa\Event\Interfaces;

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
 * @package \Phossa\Event
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa\Event\Interfaces\EventManagerInterface
 * @version 1.0.2
 * @since   1.0.2 added
 */
class ShareableEventManager
    implements
        Interfaces\EventManagerInterface,
        \Phossa\Shared\Pattern\ShareableInterface
{
    use Interfaces\EventManagerTrait,
        \Phossa\Shared\Pattern\ShareableTrait;
}

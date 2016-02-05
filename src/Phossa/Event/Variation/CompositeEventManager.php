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

use Phossa\Event\Exception;
use Phossa\Event\Interfaces;
use Phossa\Event\EventManager;
use Phossa\Event\Message\Message;

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
 * @since   1.0.3 removed trait
 */
class CompositeEventManager extends EventManager implements
    Interfaces\EventManagerCompositeInterface
{
    /**
     * Pool for other managers
     *
     * @var     EventManagerInterface[]
     * @access  protected
     */
    protected $manager_pool = [];

    /**
     * {@inheritDoc}
     */
    public function setOtherManager(
        /*# string */ $name,
        Interfaces\EventManagerInterface $manager
    )/*# : EventManagerCompositeInterface */ {
        // you can NOT use composite type as other manager
        if ($manager instanceof Interfaces\EventManagerCompositeInterface) {
            throw new Exception\InvalidArgumentException(
                Message::get(
                    Message::INVALID_EVENT_MANAGER,
                    get_class($manager)
                ),
                Message::INVALID_EVENT_MANAGER
            );
        }

        $this->manager_pool[$name] = $manager;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function unsetOtherManager(
        /*# string */ $name
    )/*# : EventManagerCompositeInterface */ {
        unset($this->manager_pool[$name]);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getOtherManagers()/*# : array */
    {
        return $this->manager_pool;
    }

    /**
     * Process events by all managers in the pool
     *
     * {@inheritDoc}
     */
    public function processEvent(
        Interfaces\EventInterface $event,
        callable $callback = null
    )/*# : EventManagerInterface */ {
        $eventName = $event->getName();

        // check self's queue
        $queue = $this->matchEventQueue($eventName, $this);

        // merge with other managers' queue
        $managers = $this->getOtherManagers();
        foreach ($managers as $mgr) {
            // other manager's own queue
            $q = $this->matchEventQueue($eventName, $mgr);
            if ($q->count()) {
                $queue = $queue->combine($q);
            }
        }

        // run thru the queue
        return $this->runEventQueue($event, $queue, $callback);
    }
}

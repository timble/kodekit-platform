<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Bootstrapper Chain
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Bootstrapper
 */
class BootstrapperChain extends ObjectQueue implements BootstrapperInterface
{
    /**
     * Priority levels
     */
    const PRIORITY_HIGHEST = 1;
    const PRIORITY_HIGH    = 2;
    const PRIORITY_NORMAL  = 3;
    const PRIORITY_LOW     = 4;
    const PRIORITY_LOWEST  = 5;

    /**
     * Bootstrap the object manager
     *
     * @return void
     */
    public function bootstrap()
    {
        foreach($this as $bootstrapper) {
            $bootstrapper->bootstrap();
        }
    }

    /**
     * Add a bootsstrapper to the queue based on priority
     *
     * @param BootstrapperInterface $bootstrapper A bootstrapper object
     * @param integer	            $priority   The bootstrapper priority, usually between 1 (high priority) and 5 (lowest),
     *                                          default is 3. If no priority is set, the bootstrapper priority will be used
     *                                          instead.
     *
     * @return BootstrapperChain
     */
    public function addBootstrapper(BootstrapperInterface $bootstrapper, $priority = null)
    {
        $this->enqueue($bootstrapper, $priority);
        return $this;
    }

    /**
     * Attach a bootstrapper to the queue
     *
     * The priority parameter can be used to override the filter priority while enqueueing the filter.
     *
     * @param   BootstrapperInterface  $bootstrapper
     * @param   integer          $priority The filter priority, usually between 1 (high priority) and 5 (lowest),
     *                                     default is 3. If no priority is set, the filter priority will be used
     *                                     instead.
     * @return BootstrapperInterface
     * @throws \InvalidArgumentException if the object doesn't implement BoostrapperInterface
     */
    public function enqueue(ObjectHandlable $bootstrapper, $priority = null)
    {
        if (!$bootstrapper instanceof BootstrapperInterface) {
            throw new \InvalidArgumentException('Filter needs to implement BootstrapperInterface');
        }

        $priority = is_int($priority) ? $priority : $bootstrapper->getPriority();
        return parent::enqueue($bootstrapper, $priority);
    }

    /**
     * Removes a bootstrapper from the queue
     *
     * @param   BootstrapperInterface   $bootstrapper
     * @return  boolean    TRUE on success FALSE on failure
     * @throws \InvalidArgumentException if the object doesn't implement ObjectBootstrapperInterface
     */
    public function dequeue(ObjectHandlable $bootstrapper)
    {
        if (!$bootstrapper instanceof BootstrapperInterface) {
            throw new \InvalidArgumentException('Filter needs to implement BootstrapperInterface');
        }

        return parent::dequeue($bootstrapper);
    }

    /**
     * Check if the queue does contain a given bootstrapper
     *
     * @param  BootstrapperInterface   $boostrapper
     * @return bool
     * @throws \InvalidArgumentException if the object doesn't implement BootstrapperInterface
     */
    public function contains(ObjectHandlable $bootstrapper)
    {
        if (!$bootstrapper instanceof BootstrapperInterface) {
            throw new \InvalidArgumentException('Filter needs to implement BootstrapperInterface');
        }

        return parent::contains($bootstrapper);
    }
}
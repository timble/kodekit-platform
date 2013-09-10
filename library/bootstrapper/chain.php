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
class BootstrapperChain extends BootstrapperAbstract
{
   /**
     * The bootstrapper queue
     *
     * @var	ObjectQueue
     */
    protected $_queue;

    /**
     * Constructor.
     *
     * @param ObjectConfig $config	An optional ObjectConfig object with configuration options.
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        //Create the queue
        $this->_queue = $this->getObject('lib:object.queue');
    }

    /**
     * Bootstrap the object manager
     *
     * @return void
     */
    public function bootstrap()
    {
        foreach($this->_queue as $bootstrapper) {
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
     * @return BootstrapperChain
     */
    public function addBootstrapper(BootstrapperInterface $bootstrapper, $priority = null)
    {
        $priority = $priority == null ? $bootstrapper->getPriority() : $priority;
        $this->_queue->enqueue($bootstrapper, $priority);
        return $this;
    }
}
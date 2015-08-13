<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2015 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Limitable Dispatcher Behavior
 *
 * Sets a maximum and a default limit for GET requests
 *
 * @author  Johan Janssens <https://github.com/johanjanssens>
 * @package Koowa\Library\Dispatcher\Behavior
 */
class DispatcherBehaviorLimitable extends DispatcherBehaviorAbstract
{
    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options.
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'default' => 20,
            'max'     => 1000
        ));

        parent::_initialize($config);
    }
    /**
     * Sets a maximum and a default limit for GET requests
     *
     * @param 	DispatcherContextInterface $context The active command context
     * @return 	void
     */
    protected function _beforeGet(DispatcherContextInterface $context)
    {
        $controller = $this->getController();

        if($controller instanceof ControllerModellable)
        {
            $controller->getModel()->getState()->setProperty('limit', 'default', $this->getConfig()->default);

            $limit = $this->getRequest()->query->get('limit', 'int');

            // Set to default if there is no limit. This is done for both unique and non-unique states
            // so that limit can be transparently used on unique state requests rendering lists.
            if(empty($limit)) {
                $limit = $this->getConfig()->default;
            }

            if($this->getConfig()->max && $limit > $this->getConfig()->max) {
                $limit = $this->getConfig()->max;
            }

            $this->getRequest()->query->limit = $limit;
            $controller->getModel()->getState()->limit = $limit;
        }
    }
}

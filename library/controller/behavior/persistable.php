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
 * Persistable Controller Behavior
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Controller
 */
class ControllerBehaviorPersistable extends ControllerBehaviorPersistableAbstract
{
    /**
     * Check if the behavior is supported
     *
     * Disable controller state persistency on non-HTTP requests, e.g. AJAX. This avoids changing the model state session
     * variable of the requested model, which is often undesirable under these circumstances.
     *
     * @return  boolean  True on success, false otherwise
     */
    public function isSupported()
    {
        $result = false;

        $mixer   = $this->getMixer();
        $request = $mixer->getRequest();

        if ($mixer instanceof ControllerModellable && $mixer->isDispatched() && $request->isGet() && !$request->isAjax())
        {
            $result = true;
        }

        return $result;
    }

    protected function _setContainer(ControllerContextInterface $context)
    {
        $model            = $context->subject->getModel();
        $this->_container = $model->getIdentifier() . '.' . $context->action . '.state';
    }

    /**
     * Load the model state from the request and persist it.
     *
     * This functions merges the request information with any model state information that was saved in the session
     * and returns the result.
     *
     * @param ControllerContextInterface $context	A controller context object
     * @return 	void
     */
    protected function _beforeBrowse(ControllerContextInterface $context)
    {
        $model      = $this->getModel();
        $query      = $context->getRequest()->query;

        $query->add((array) $this->_getData($context));

        $model->getState()->setValues($query->toArray());
    }

    /**
     * Saves the model state in the session.
     *
     * @param ControllerContextInterface $context	A controller context object
     * @return 	void
     */
    protected function _afterBrowse(ControllerContextInterface $context)
    {
        $model  = $this->getModel();
        $state  = $model->getState();

        $vars = array();
        foreach($state->toArray() as $var)
        {
            if(!$var->unique && !$var->internal) {
                $vars[$var->name] = $var->value;
            }
        }

        $this->_setData($vars, $context);
    }
}
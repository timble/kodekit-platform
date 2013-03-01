<?php
/**
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Dispatcher Class
.*
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultDispatcher extends KDispatcherComponent
{
    /**
     * Constructor
     *
     * @param 	object 	An optional KConfig object with configuration options.
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        /*
         * Disable controller persistency on non-HTTP requests, e.g. AJAX. This avoids changing the model state session
         * variable of the requested model, which is often undesirable under these circumstances.
         */
        if($this->getRequest()->isGet() && !$this->getRequest()->isAjax()) {
            $this->attachBehavior('persistable');
        }
    }

    /**
     * Dispatch the controller
     * 
     * Redirect if no view information can be found in the request.
     * 
     * @param   KCommandContext	$context A command context object
     * @return	mixed
     */
    protected function _actionDispatch(KCommandContext $context)
    {
        //Redirect if no view information can be found in the request
        if(!$context->request->query->has('view'))
        {
            $route = $this->getController()->getView()->getRoute();

            $context->response->setRedirect($route);
            return false;
        }

        return parent::_actionDispatch($context);
    }
}
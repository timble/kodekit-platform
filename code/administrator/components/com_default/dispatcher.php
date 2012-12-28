<?php
/**
 * @version     $Id$
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
     * Dispatch the controller
     * 
     * Redirect if no view information can be found in the request.
     * 
     * @param   string      The view to dispatch. If null, it will default to retrieve the controller information
     *                      from the request or default to the component name if no controller info can be found.
     *
     * @return  KDispatcherDefault
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
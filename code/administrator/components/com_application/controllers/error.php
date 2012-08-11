<?php
/**
 * @version		$Id$
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Error Controller Class
 *   
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
 
class ComApplicationControllerError extends KControllerResource
{
    protected function _actionGet(KCommandContext $context)
    {
        //Set the error in the view
        $this->getView()->error = KConfig::unbox($context->data);

        //Set the status code
        header('Status: '.$context->data->getMessage(), true, (int) $context->data->getCode());

        //Make sure the buffers are cleared
        while(@ob_get_clean());

        return parent::_actionGet($context);
    }
}
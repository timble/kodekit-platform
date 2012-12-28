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
 * Exception Controller Class
 *   
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
 
class ComApplicationControllerException extends KControllerView
{
    protected function _actionRender(KCommandContext $context)
    {
        //Make sure the buffers are cleared
        while(@ob_get_clean());

        $result = parent::_actionRender($context);

        $exception = $this->getView()->exception;
        $context->response->setStatus($exception->getCode(), $exception->getMessage());

        return $result;
    }
}
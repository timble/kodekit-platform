<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Dispatcher Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files   
 */

class ComFilesDispatcher extends ComDefaultDispatcher
{
    /**
     * Overloaded execute function to handle exceptions in JSON requests
     */
    public function execute($action, KCommandContext $context)
    {
        try {
            return parent::execute($action, $context);
        }
        catch (KControllerException $e) {
            $this->_handleException($e);
        }
        catch (KModelException $e) {
            $this->_handleException($e);
        }
    }
    
    protected function _handleException(Exception $e) {
    	if (KRequest::get('get.format', 'cmd') == 'json') {
    		$obj = new stdClass;
    		$obj->status = false;
    		$obj->error = $e->getMessage();
    		$obj->code = $e->getCode();
    		
    		// Plupload do not pass the error to our application if the status code is not 200
    		$code = KRequest::get('get.plupload', 'int') ? 200 : $e->getCode();
    		
    		JResponse::setHeader('status', $code.' '.str_replace("\n", ' ', $e->getMessage()));
    		
    		echo json_encode($obj);
    		JFactory::getApplication()->close();
    	}
    	else {
    		throw $e;
    	}
    }
	/**
	 * Overloaded to comply with FancyUpload.
	 * It doesn't let us pass AJAX headers so this is needed.
	 */
	public function _actionForward(KCommandContext $context)
	{
		if(KRequest::type() == 'FLASH' || KRequest::format() == 'json') {
			$context->result = $this->getController()->execute('display', $context);
		} else {
			parent::_actionForward($context);
		}

		return $context->result;

	}
}
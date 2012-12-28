<?php
/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Dispatcher Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
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
        catch (UnexpectedValueException $e) {
            $this->_handleException($e);
        }
    }

    protected function _handleException(Exception $e) 
    {
    	if ($this->getRequest()->getFormat() == 'json')
        {
    		$obj = new stdClass;
    		$obj->status = false;
    		$obj->error  = $e->getMessage();
    		$obj->code   = $e->getCode();

    		// Plupload do not pass the error to our application if the status code is not 200
    		$code = $this->getRequest()->query->get('plupload', 'int') ? 200 : $e->getCode();

            $this->getResponse()->setStatus($code, $e->getMessage());

    		echo json_encode($obj);
    		exit(0);
    	}
    	else throw $e;
    }
    
	/**
	 * Overloaded to comply with FancyUpload. It doesn't let us pass AJAX headers so this is needed.
	 */
	/*public function _actionForward(KCommandContext $context)
	{
		if ($context->result->getStatus() != KDatabase::STATUS_DELETED)
        {
			if(KRequest::type() == 'FLASH' || KRequest::format() == 'json') {
				$context->result = $this->getController()->execute('display', $context);
			} else {
				parent::_actionForward($context);
			}
		}

		return $context->result;
	}*/
}
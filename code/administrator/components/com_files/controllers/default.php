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
 * Node Controller Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesControllerDefault extends ComDefaultControllerDefault
{
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'persistable' => false,
		    'request' => array(
		        'container' => 'files-files'
		    )
		));

		parent::_initialize($config);
	}

	public function getRequest()
	{
		$request = parent::getRequest();
		
		// "e_name" is needed to be compatible with com_content of Joomla
		if ($request->e_name) {
		    $request->editor = $request->e_name;
		}

		// "config" state is only used in HMVC requests and passed to the JS application
		if ($this->isDispatched()) {
			unset($request->config);
		}

		return $request;
	}

	/**
	 * Overridden method to be able to use it with both resource and service controllers
	 */
	protected function _actionGet(KCommandContext $context)
    {
    	if ($this->getIdentifier()->name == 'image'
    		|| ($this->getIdentifier()->name == 'file' && $this->getRequest()->format == 'html')) 
    	{
	        //Load the language file for HMVC requests who are not routed through the dispatcher
	        if(!$this->isDispatched()) {
	            JFactory::getLanguage()->load('com_'.$this->getIdentifier()->package);
	        }
	
	        $result = $this->getView()->display();
		    return $result;
    	}
    	
    	return parent::_actionGet($context);
    	
    }
}

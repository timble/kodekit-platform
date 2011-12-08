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
		
		// e_name still needs to work for compatibility reasons with Joomla com_content.
		// here we map it to "editor" state
		if ($request->e_name) {
		    $request->editor = $request->e_name;
		}

		// "config" state is only used in HMVC requests and passed to the JS application
		if ($this->isDispatched()) {
			unset($request->config);
		}
 
		$config = $this->getService('com://admin/files.model.configs')
			->set($request)
			->getItem();
			
		$request->container = $config->container;

		return $request;
	}

	protected function _actionGet(KCommandContext $context)
    {
    	if ($this->getIdentifier()->name != 'image') {
    		return parent::_actionGet($context);
    	}
    	
        //Load the language file for HMVC requests who are not routed through the dispatcher
        if(!$this->isDispatched()) {
            JFactory::getLanguage()->load('com_'.$this->getIdentifier()->package);
        }

        $result = $this->getView()->display();
	    return $result;
    }
    
 	public function __set($property, $value)
    {
        if ($property === 'container' && is_string($value)) {
            $value = $this->getService('com://admin/files.model.containers')->slug($value)->getItem();
        }
        
    	parent::__set($property, $value);
  	}
}

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

class ComFilesControllerNode extends ComDefaultControllerDefault
{
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'persistable' => false,
			'request'     => array(
				'container' => 'com_files.files'
			)
		));

		parent::_initialize($config);
	}

	public function getRequest()
	{
		$request = parent::getRequest();

		KFactory::get('com://admin/files.model.configs')
			->set($request)
			->getItem();

		return $request;
	}

	protected function _actionGet(KCommandContext $context)
    {
        //Load the language file for HMVC requests who are not routed through the dispatcher
        if(!$this->isDispatched()) {
            JFactory::getLanguage()->load('com_'.$this->getIdentifier()->package);
        }

        $result = $this->getView()->display();
	    return $result;
    }
}

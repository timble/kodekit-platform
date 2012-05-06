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
 * File Controller Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */
class ComFilesControllerFile extends ComFilesControllerDefault
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->registerCallback(array('before.add', 'before.edit'), array($this, 'addFile'));
	}
	
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
    		'behaviors' => array(
    			'com://admin/activities.controller.behavior.loggable' =>  array(
    				'title_column' => 'name'
    	    )),
        ));
    
    	parent::_initialize($config);
    }	

	public function addFile(KCommandContext $context)
	{
		if (empty($context->data->file) && KRequest::has('files.file.tmp_name'))
		{
			$context->data->file = KRequest::get('files.file.tmp_name', 'raw');
			if (empty($context->data->name)) {
				$context->data->name = KRequest::get('files.file.name', 'raw');
			}

		}
	}
}

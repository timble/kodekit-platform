<?php
/**
 * @version     $Id: node.php 911 2011-09-16 13:28:15Z ercanozkaya $
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
class ComFilesModelGalleries extends KModelAbstract
{
	public function __construct(KConfig $config) 
	{
		parent::__construct($config);
		
		$this->_state
			->insert('container', 'cmd', null)
			->insert('folder'	, 'com://admin/files.filter.path', '')
			;
	}

	/*
	 * TODO: this is to keep ComDefaultTemplateDefault happy. it calls getItem and creates a notice
	 */
    public function getItem()
    {
        return null;
    }	
}
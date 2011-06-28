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
 * Folder Uploadable Filter Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files   
 */

class ComFilesFilterFolderUploadable extends KFilterAbstract
{
	protected $_walk = false;

	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_chain->enqueue(KFactory::tmp('admin::com.files.filter.folder.name'), KCommand::PRIORITY_HIGH);
		$this->_chain->enqueue(KFactory::tmp('admin::com.files.filter.folder.exists'), KCommand::PRIORITY_HIGH);
	}

	protected function _validate($context)
	{
	    
	}

	protected function _sanitize($context)
	{

	}
}

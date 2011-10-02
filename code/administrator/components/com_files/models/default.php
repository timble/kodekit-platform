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
 * Default Model Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */

class ComFilesModelDefault extends KModelAbstract
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_state
			->insert('limit'    , 'int', JFactory::getApplication()->getCfg('list_limit'))
			->insert('offset'   , 'int', 0)
			->insert('search'	, 'filename')
			->insert('direction', 'word', 'asc')
            ->insert('sort'     , 'cmd')

			->insert('container', 'identifier', null)
			->insert('path'		, 'com://admin/files.filter.path', null, true) // unique
			->insert('folder'	, 'com://admin/files.filter.path', '')
			->insert('types'	, 'cmd', '')
			->insert('editor'   , 'cmd', '') // used in modal windows
			;
	}

	protected function _initialize(KConfig $config)
	{
		$config->append(array(
            'state'      => $this->getService('com://admin/files.model.state.node'),
       	));

       	parent::_initialize($config);
    }
}
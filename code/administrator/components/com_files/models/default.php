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
 * Default Model Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
  * @package     Nooku_Components
 * @subpackage  Files
 */

class ComFilesModelDefault extends KModelAbstract
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

        $this->_state
            ->insert('limit'    , 'int')
            ->insert('offset'   , 'int')
            ->insert('sort'     , 'cmd')
            ->insert('direction', 'word', 'asc')
            ->insert('search'   , 'string')
            // callback state for JSONP, needs to be filtered as cmd to prevent XSS
            ->insert('callback' , 'cmd')

			->insert('container', 'com://admin/files.filter.container', null)
			->insert('folder'	, 'com://admin/files.filter.path', '')
			->insert('name'		, 'com://admin/files.filter.path', '', true)

			->insert('types'	, 'cmd', '')
			->insert('editor'   , 'string', '') // used in modal windows
			->insert('config'   , 'json', '') // used to pass options to the JS application in HMVC
			;
	}

	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'state' => new ComFilesConfigState()
		));

		parent::_initialize($config);
	}
}
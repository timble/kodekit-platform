<?php
/**
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Default Model Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
  * @package     Nooku_Components
 * @subpackage  Files
 */

class ComFilesModelDefault extends Framework\ModelAbstract
{
	public function __construct(Framework\Config $config)
	{
		parent::__construct($config);

        $this->_state
            ->insert('limit'    , 'int')
            ->insert('offset'   , 'int')
            ->insert('sort'     , 'cmd')
            ->insert('direction', 'word', 'asc')
            ->insert('search'   , 'string')

			->insert('container', 'com://admin/files.filter.container', null)
			->insert('folder'	, 'com://admin/files.filter.path', '')
			->insert('name'		, 'com://admin/files.filter.path', '', true)

			->insert('types'	, 'cmd', '')
			->insert('editor'   , 'string', '') // used in modal windows
			->insert('config'   , 'json', '')   // used to pass options to the JS application in HMVC
			;
	}

	protected function _initialize(Framework\Config $config)
	{
		$config->append(array(
			'state' => new ComFilesModelState()
		));

		parent::_initialize($config);
	}
}
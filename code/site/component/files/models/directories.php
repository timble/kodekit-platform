<?php
/**
 * @version		$Id$
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Default Directories Class
 *   
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
 
class ComFilesModelDirectories extends KModelAbstract
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->getState()
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
			;
	}
}
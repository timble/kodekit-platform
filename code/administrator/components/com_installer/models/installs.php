<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Installs Model Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 */
class ComInstallerModelInstalls extends KModelAbstract
{
    /**
	 * Constructor
	 *
	 * @param  object  An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config = null)
	{
		parent::__construct($config);

		$this->_state
		    ->insert('directory'        , 'path', JFactory::getApplication()->getCfg('config.tmp_path'))
		    ->insert('url'              , 'url', 'http://')
		    ->insert('extension_message', 'string')
		    ->insert('message'          , 'string')
		    ->insert('name'             , 'string');
	}
}
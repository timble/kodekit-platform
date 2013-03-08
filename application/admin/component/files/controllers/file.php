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
 * File Controller Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package     Nooku_Components
 * @subpackage  Files
 */
class ComFilesControllerFile extends ComFilesControllerDefault
{
	public function __construct(Framework\Config $config)
	{
		parent::__construct($config);

		$this->registerCallback(array('before.add', 'before.edit'), array($this, 'addFile'));
	}
	
    protected function _initialize(Framework\Config $config)
	{
		$config->append(array(
			'behaviors' => array('thumbnailable')
		));

		parent::_initialize($config);
	}

	public function addFile(Framework\CommandContext $context)
	{
		$file = $context->request->data->get('file', 'raw');
		$name = $context->request->data->get('name', 'raw');

		if (empty($file) && Framework\Request::has('files.file.tmp_name'))
		{
			$context->request->data->set('file', Framework\Request::get('files.file.tmp_name', 'raw'));
			
			if (empty($name)) {
				$context->request->data->set('name', Framework\Request::get('files.file.name', 'raw'));
			}

		}
	}
}

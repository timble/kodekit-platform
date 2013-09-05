<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * File Controller
 *
 * @author  Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @package Nooku\Component\Files
 */
class ControllerFile extends ControllerAbstract
{
	public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);

		$this->registerCallback('before.add' , array($this, 'addFile'));
        $this->registerCallback('before.edit', array($this, 'addFile'));
	}
	
    protected function _initialize(Library\ObjectConfig $config)
	{
		$config->append(array(
			'behaviors' => array('com:files.controller.behavior.thumbnailable')
		));

		parent::_initialize($config);
	}

	public function addFile(Library\CommandContext $context)
	{
		$file = $context->request->data->get('file', 'raw');
		$name = $context->request->data->get('name', 'raw');

		if (empty($file) && $context->request->files->has('file.tmp_name'))
		{
			$context->request->data->set('file', $context->request->files->get('file.tmp_name', 'raw'));
			
			if (empty($name)) {
				$context->request->data->set('name', $context->request->files->get('file.name', 'raw'));
			}
		}
	}

    protected function _actionRender(Library\CommandContext $context)
    {
        if($context->request->getFormat() == 'html') {
            return Library\ControllerView::_actionRender($context);
        }

        return parent::_actionRender($context);
    }
}

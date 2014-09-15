<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * File Controller
 *
 * @author  Ercan Ozkaya <http://github.com/ercanozkaya>
 * @package Nooku\Component\Files
 */
class ControllerFile extends ControllerAbstract
{
	public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);

        $this->addCommandCallback('before.add' , 'addFile');
        $this->addCommandCallback('before.edit', 'addFile');
	}
	
    protected function _initialize(Library\ObjectConfig $config)
	{
		$config->append(array(
			'behaviors' => array('com:files.controller.behavior.thumbnailable')
		));

		parent::_initialize($config);
	}

	public function addFile(Library\ControllerContextInterface $context)
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

    protected function _actionRender(Library\ControllerContextInterface $context)
    {
        $model = $this->getModel();

        if($model->getState()->isUnique())
        {
            $file = $this->getModel()->fetch();

            try
            {
                $this->getResponse()
                    ->attachTransport('stream')
                    ->setContent($file->fullpath, $file->mimetype);
            }
            catch (\InvalidArgumentException $e) {
                throw new Library\ControllerExceptionResourceNotFound('File not found');
            }
        }
        else parent::_actionRender($context);
    }
}

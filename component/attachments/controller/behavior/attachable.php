<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Attachments;

use Nooku\Library;

/**
 * Attachable Controller Behavior
 *
 * @author  Steven Rombauts <https://nooku.assembla.com/profile/stevenrombauts>
 * @package Nooku\Component\Attachments
 */
class ControllerBehaviorAttachable extends Library\ControllerBehaviorAbstract
{
	/**
	 * Attachments array coming from $_FILES
	 */
	protected $_attachments = array();
	
	/**
	 * Controller to handle file uploads
	 */
	protected $_file_controller = null;
	
	/**
	 * Controller to handle attachment saving
	 */
	protected $_attachment_controller = null;
	
	/**
	 * Container to use in com_files
	 */
	protected $_container = null;
	
	/**
	 * If true, file list wil be populated from $_FILES['attachments'] automatically
	 */
	protected $_populate_from_request = true;
	
	/**
	 * You can limit allowed attachment number per node with this property. False for unlimited.
	 */
	protected $_attachment_limit = false;
	
	public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);
		
		$this->_container = $config->container;
		$this->_populate_from_request = $config->populate_from_request;
		
		$this->_file_controller = $this->getObject($config->file_controller, array(
			'request' => array('container' => $this->_container)
		));
        
        $this->_file_controller = $this->getObject($config->file_controller, array(
			'request' => $this->getObject('lib:controller.request', array(
				'query' => array(
					'container' => $this->_container
				)
			))
		));
        
        $this->_attachment_controller = $this->getObject($config->attachment_controller, array(
			'request' => $this->getObject('lib:controller.request', array(
				'query' => array(
					'container' => $this->_container
				)
			))
		));
		
		$this->_attachment_limit = $config->attachment_limit;
	}
	
	protected function _initialize(Library\ObjectConfig $config)
	{
		$config->append(array(
			'container'             => 'attachments-attachments',
			'file_controller'       => 'com:files.controller.file',
			'attachment_controller' => 'com:attachments.controller.attachment',
			'populate_from_request' => true,
			'attachment_limit'      => false
		));
		
		parent::_initialize($config);
	}
	
	public function getAttachments()
	{
		return $this->_attachments;
	}
	
	public function setAttachments(array $attachments)
	{
		$this->_attachments = $attachments;
		
		return $this->_attachments;
	}
	
	protected function _populateFilesFromRequest(Library\CommandContext $context)
	{
		if ($this->_populate_from_request)
        {
			$attachments = $context->request->files->get('attachments', 'raw');
			$files = array();
	
			if (is_array($attachments['name']))
            {
				// Why do you return such a weird array for files PHP? why?
				for ($i = 0, $n = count($attachments['name']); $i < $n; $i++)
                {
					if ($attachments['error'][$i] === UPLOAD_ERR_NO_FILE) {
						continue;
					}
					
					$file = array();
					foreach (array_keys($attachments) as $key) {
						$file[$key] = $attachments[$key][$i]; 
					}
				
					$files[] = $file;
				}

			} elseif (is_array($attachments)) {
				$files[] = $attachments;
			}
			
			$this->_attachments = $files;
		}
	}
	
	protected function _saveFile(Library\CommandContext $context, $attachment)
	{
		$row = $context->result;

		try {
			$ext = pathinfo($attachment['name'], PATHINFO_EXTENSION);
			$name = md5(time().rand()).'.'.$ext;
			$hash = md5_file($attachment['tmp_name']);
			$file = $this->_file_controller->add(array(
				'file' => $attachment['tmp_name'],
				'name' => $name,
				'parent' => ''
			));

			$data = $this->_attachment_controller->add(array(
				'name' => $attachment['name'],
				'path' => $name,
				'container' => $this->_container,
				'hash' => $hash,
				'row' => $row->id,
				'table' => $row->getTable()->getBase()
			));

            // Reset models
			$model  = $this->_file_controller->getModel();
            $container = $model->getState()->container;

			$model->reset(false)->getState()->set('container', $container);

			$this->_attachment_controller->getModel()->reset(false);

            // Clear the data in controllers for the next file
            $this->_file_controller->getRequest()->data->clear();
            $this->_attachment_controller->getRequest()->data->clear();
		}
		catch (Library\ControllerException $e) {
			$context->response->setStatus($e->getCode() , $e->getMessage());
			return false;
		}
		
		return true;
	}
	
	protected function _saveFiles(Library\CommandContext $context)
    {
		if ($context->error) {
			return;
		}
	
		$row = $context->result;
        
        $count = $this->getObject('com:attachments.controller.attachment', array(
			'request' => $this->getObject('lib:controller.request', array(
				'query' => array(
					'row' => $row->id,
					'table' => $row->getTable()->getBase()
				)
			))
		))->browse();
		$count = count($count);
		$limit = $this->_attachment_limit;

		foreach ($this->_attachments as $attachment)
        {
			if ($limit !== false && $count >= $limit) {
                $context->response->setStatus(500, 'You have reached the attachment limit for this item.');
				return false;
			}

			if ($this->_saveFile($context, $attachment)) {
				$count++;	
			}
		}

		return true;
	}
	
	protected function _beforeControllerAdd(Library\CommandContext $context) {
		$this->_populateFilesFromRequest($context);
	}
	
	protected function _beforeControllerEdit(Library\CommandContext $context) {
		$this->_populateFilesFromRequest($context);
	}
	
	protected function _afterControllerAdd(Library\CommandContext $context) {
		$this->_saveFiles($context);
	}
	
	protected function _afterControllerEdit(Library\CommandContext $context) {
		$this->_saveFiles($context);
	}
	
	protected function _afterControllerDelete(Library\CommandContext $context)
    {
        $status = $context->result->getStatus();

        if($status == Library\Database::STATUS_DELETED || $status == 'trashed')
        {
            $id = $context->result->get('id');
            $table = $context->result->getTable()->getBase();

            if(!empty($id) && $id != 0)
            {
                $rows = $this->getObject('com:attachments.model.attachments')
                    ->row($id)
                    ->table($table)
                    ->getRowset();

                $rows->delete();
            }
        }
	} 
}
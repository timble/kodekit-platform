<?php

class ComAttachmentsControllerBehaviorAttachable extends KControllerBehaviorAbstract
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
	
	public function __construct(KConfig $config)
	{
		parent::__construct($config);
		
		$this->_container = $config->container;
		$this->_populate_from_request = $config->populate_from_request;
		
		$this->_file_controller = $this->getService($config->file_controller, array(
			'request' => array('container' => $this->_container)
		));
		
		$this->_attachment_controller = $this->getService($config->attachment_controller);
		
		$this->_attachment_limit = $config->attachment_limit;
	}
	
	protected function _initialize(KConfig $config)
	{
		$config->append(array(
			'container' => 'attachments-attachments',
			'file_controller' => 'com://admin/files.controller.file',
			'attachment_controller' => 'com://admin/attachments.controller.attachment',
			'populate_from_request' => true,
			'attachment_limit' => false
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
	
	protected function _populateFilesFromRequest(KCommandContext $context)
	{
		if ($this->_populate_from_request) {
			$attachments = KRequest::get('files.attachments', 'raw');
			$files = array();
	
			if (is_array($attachments['name'])) {
				// Why do you return such a weird array for files PHP? why?
				for ($i = 0, $n = count($attachments['name']); $i < $n; $i++) {
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
	
	protected function _saveFile(KCommandContext $context, $attachment)
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
			
			$model = $this->_file_controller->getModel(); 
			$data = $model->getState()->getData();
			$model->reset(false)->set($data);
			$this->_attachment_controller->getModel()->reset(false);
		}
		catch (KControllerException $e) {
			$context->setError($e);
			return false;
		}
		
		return true;
	}
	
	protected function _saveFiles(KCommandContext $context) {
		if ($context->error) {
			return;
		}
	
		$row = $context->result;
		
		$count = $this->getService('com://admin/attachments.controller.attachment')
			->row($row->id)
			->table($row->getTable()->getBase())
			->browse();
		$count = count($count);
		$limit = $this->_attachment_limit;

		foreach ($this->_attachments as $attachment) {
			if ($limit !== false && $count >= $limit) {
				$context->setError(new KControllerException(
					'You have reached the attachment limit for this item.'
				));
				return false;
			}
			if ($this->_saveFile($context, $attachment)) {
				$count++;	
			}
		}
		
		return true;
	}
	
	protected function _beforeAdd(KCommandContext $context) {
		$this->_populateFilesFromRequest($context);
	}
	
	protected function _beforeEdit(KCommandContext $context) {
		$this->_populateFilesFromRequest($context);
	}
	
	protected function _afterAdd(KCommandContext $context) {
		$this->_saveFiles($context);
	}
	
	protected function _afterEdit(KCommandContext $context) {
		$this->_saveFiles($context);
	}
	
	protected function _afterDelete(KCommandContext $context) {
		// TODO
	} 
}
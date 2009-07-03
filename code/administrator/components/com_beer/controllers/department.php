<?php
class BeerControllerDepartment extends KControllerForm
{
	public function __construct($options = array())
	{
		parent::__construct($options);

		$this->registerFilterBefore('save'   , 'filterInput');
		$this->registerFilterBefore('add'   , 'add');
	}

	public function filterInput($args)
	{
		$alias 			= KRequest::get('post.alias', 'ascii');
		$title 			= KRequest::get('post.title', 'string');
		$description	= KRequest::get('post.description', 'raw' );

		if(empty($alias)) {
			$alias = KRequest::get('post.title', 'ascii');
		}

		KRequest::set('post.alias', $alias);
		KRequest::set('post.description', $description);
	}
	
	public function add($args)
	{
		$user = KFactory::get('lib.joomla.user');
		
		KRequest::set('post.created_by', $user->get('id'));
	}
}
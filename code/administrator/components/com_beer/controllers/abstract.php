<?php
abstract class BeerControllerAbstract extends KControllerForm
{
	public function __construct($options = array())
	{
		parent::__construct($options);

		$this->registerFilterBefore('save'   , 'filterInput');
		$this->registerFilterBefore('add'   , 'filterCreated');
		$this->setFilters();
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

	public function filterCreated($args)
	{
		KRequest::set('post.created_by', KFactory::get('lib.joomla.user')->get('id'));
	}

	public function setFilters()
	{
		$suffix = KInflector::pluralize($this->getClassName('suffix'));
		$model = KFactory::get('admin::com.beer.model.'.$suffix);

		$model->setState('enabled',		KRequest::get('post.enabled', 'int'));
		$model->setState('department', 	KRequest::get('post.beer_department_id', 'int'));
		$model->setState('office', 		KRequest::get('post.beer_office_id', 'int'));
		$model->setState('search', 		KRequest::get('post.search', 'string'));
	}
}
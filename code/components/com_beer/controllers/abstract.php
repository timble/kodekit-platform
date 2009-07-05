<?php

abstract class BeerControllerAbstract extends KControllerForm
{
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct($options = array())
	{
		parent::__construct($options);

		$this->setFilters();
	}

	public function setFilters()
	{
		$suffix = KInflector::pluralize($this->getClassName('suffix'));
		$model = KFactory::get('site::com.beer.model.'.$suffix);

		$model->setState('enabled',				KRequest::get('request.enabled', 'int'));
		$model->setState('beer_department_id', 	KRequest::get('request.beer_department_id', 'int'));
		$model->setState('beer_office_id', 		KRequest::get('request.beer_office_id', 'int'));
		$model->setState('search', 				KRequest::get('request.search', 'string'));
	}
}
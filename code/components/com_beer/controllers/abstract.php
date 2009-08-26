<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * @version		$Id$
 * @package		Beer
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

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
		$suffix = KInflector::pluralize($this->getIdentifier()->name);
		$model = KFactory::get('site::com.beer.model.'.$suffix);

		$model->setState('enabled',				1);
		$model->setState('beer_department_id', 	KRequest::get('request.beer_department_id', 'int'));
		$model->setState('beer_office_id', 		KRequest::get('request.beer_office_id', 'int'));
		$model->setState('search', 				KRequest::get('request.search', 'string'));
	}
}
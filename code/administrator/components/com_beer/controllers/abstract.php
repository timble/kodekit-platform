<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * Developed for Brian Teeman's Developer Showdown, using Nooku Framework
 * @version		$Id$
 * @package		Beer
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * Abstract Controller
 *
 * @package		Beer
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

		$this->registerFilterBefore('save'   , 'filterInput');
		$this->registerFilterBefore('apply'   , 'filterInput');
		$this->registerFilterBefore('add'   , 'filterCreated');
		$this->setFilters();
	}


	/**
	 * Set the created by field
	 *
	 * @param	Arguments
	 * @return 	void
	 */
	public function filterCreated($args)
	{
		KRequest::set('post.created_by', KFactory::get('lib.joomla.user')->get('id'));
	}

	/**
	 * Set the state of the filters in the model
	 *
	 * @return void
	 */
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
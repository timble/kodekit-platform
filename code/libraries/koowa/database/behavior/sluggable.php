<?php
/**
 * @version 	$Id: abstract.php 1528 2010-01-26 23:14:08Z johan $
 * @category	Koowa
 * @package		Koowa_Database
 * @subpackage 	Behavior
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 */

/**
 * Database Creatable Behavior
 *
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Database
 * @subpackage 	Behavior
 */
class KDatabaseBehaviorSluggable extends KDatabaseBehaviorAbstract
{
 	/**
	 * The column name from where to generate the slug, or a set of column
	 * names to concatenate for generating the slug.
	 *
	 * @var	array
	 */
	protected $_columns;

	/**
	 * Separator character / string to use for replacing non alphabetic
	 * characters in generated slug
	 *
	 * @var	string
	 */
	protected $_separator;

	/**
	 * Maximum length the generated slug can have. If this is null the length of
	 * the slug column will be used.
	 *
	 * @var	integer
	 */
	protected $_length;

	/**
	 * Set to true if slugs should be re-generated when updating an existing
	 * row.
	 *
	 * @var	boolean
	 */
	protected $_updatable;

	/**
	 * Constructor.
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct( KConfig $config = null)
	{
		parent::__construct($config);

		foreach($config as $key => $value) {
			$this->{'_'.$key} = $value;
		}
	}

	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options
     * @return void
     */
	protected function _initialize(KConfig $config)
    {
    	$config->append(array(
			'columns'   => array('title'),
    		'separator' => '-',
    		'updatable' => true,
    		'length' 	=> null
	  	));

    	parent::_initialize($config);
   	}

	/**
	 * Get the methods that are available for mixin based
	 *
	 * This function conditionaly mixes the behavior. Only if the mixer
	 * has a 'slug' property the behavior will be mixed in.
	 *
	 * @param object The mixer requesting the mixable methods.
	 * @return array An array of methods
	 */
	public function getMixableMethods(KObject $mixer = null)
	{
		$methods = array();

		if(isset($mixer->slug)) {
			$methods = parent::getMixableMethods($mixer);
		}

		return $methods;
	}
	
	/**
	 * Insert a slug
	 *
	 * If multiple columns are set they will be concatenated and seperated by the
	 * separator in the order they are defined.
	 *
	 * Requires a 'slug' column
	 *
	 * @return void
	 */
 	protected function _afterTableInsert(KCommandContext $context)
	{
		$row = $context->data;
		
		//Create the slug filter
		$filter = $this->_createFilter($context);

		$slugs = array();
		foreach($this->_columns as $column) {
			$slugs[] = $filter->sanitize($row->$column);
		}

		$row->slug = implode($this->_separator, $slugs);
		
		$row->save();
	}

	/**
	 * Update the slug
	 *
	 * Only works if {@link $updatable} property is TRUE. If the slug is empty
	 * the slug will be regenerated. If the slug has been modified it will be
	 * sanitized.
	 *
	 * Requires a 'slug' column
	 *
	 * @return void
	 */
	protected function _beforeTableUpdate(KCommandContext $context)
	{
		if($this->_updatable)
		{
			$row = $context->data;

			if(empty($row->slug))
			{
				//Create the slug filter
				$filter = $this->_createFilter($context);

				$slugs = array();
				foreach($this->_columns as $column) {
					$slugs[] = $filter->sanitize($row->$column);
				}

				$row->slug = implode($this->_separator, $slugs);
			}
			else
			{
				if(in_array('slug', $context->data->getModified()))
				{
					//Create the slug filter
					$filter = $this->_createFilter($context);

					//Create the filter
					$row->slug = $filter->sanitize($row->slug);
				}
			}
		}
	}

	/**
	 * Create a sluggable filter
	 *
	 * @return void
	 */
	protected function _createFilter(KCommandContext $context)
	{
		$config = array();
		$config['separator'] = $this->_separator;

		if(!isset($this->_length)) {
			$config['length'] = $context->caller->getColumn('slug')->length;
		} else {
			$config['length'] = $this->_length;
		}
		
		//Create the filter
		$filter = KFactory::tmp('lib.koowa.filter.slug', $config);
		return $filter;
	}
}
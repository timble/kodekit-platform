<?php
/**
 * @version      $Id$
 * @category	 Nooku
 * @package      Nooku_Server
 * @subpackage   Articles
 * @copyright    Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

/**
 * Cascadable Database Behavior Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Articles    
 */
class ComArticlesDatabaseBehaviorCascadable extends KDatabaseBehaviorAbstract
{
    /**
     * List of dependent columns
     * 
     * @var array
     */
    protected $_dependents;
    
	/**
	 * Constructor.
	 *
	 * $config->dependents array An array of identifiers of the dependent tables 
	 * in the format: com://app/package.model.name.column where column contains the 'foreign key'
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct( KConfig $config = null) 
	{ 
	    parent::__construct($config);
	    
	    $this->_dependents = $config->dependents;
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
			'dependents' => array('com://admin/categories.model.categories.parent'),
	  	));

    	parent::_initialize($config);
   	}
    
    /**
     * Deletes dependent rows.
     *
     * This performs an intelligent delete 
     *
     * @return KDatabaseRowAbstract
     */
    protected function _beforeTableDelete(KCommandContext $context)
    {
        $result = true;
        
        $id = $this->get($this->getTable()->getIdentityColumn());
        
        foreach($this->_dependents as $dependent)
        {                 
            $parts  = explode('.', $dependent);
            $column = array_pop($parts);
            $name   = array_pop($parts);
            
            $identifier = implode('.', $parts).'.'.$name;
            
            $rowset = KFactory::get($identifier)
                        ->set($column, $id)
                        ->limit(0)
                        ->getList();

            if($rowset->count()) {
                $result = $rowset->delete();
            } 
        }
        
        return $result;       
    }
}
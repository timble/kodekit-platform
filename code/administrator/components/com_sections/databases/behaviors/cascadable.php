<?php
/**
 * @version      $Id$
 * @category	 Nooku
 * @package      Nooku_Server
 * @subpackage   Sections
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
 * @subpackage  Sections    
 */
class ComSectionsDatabaseBehaviorCascadable extends KDatabaseBehaviorAbstract
{

    protected $_dependents;
    
	/**
	 * Constructor.
	 *
	 * $config->dependents array An array of identifiers of the dependent tables 
	 * in the format: app::com.package.model.name.column where column contains the 'foreign key'
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct( KConfig $config = null) 
	{ 
	    $this->_dependents = $config->dependents;
		
		parent::__construct($config);
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
        $id = $this->get($this->getTable()->getIdentityColumn());

        foreach($this->_dependents as $dependent)
        {                 
            $parts = explode('.', $dependent);
            $column = array_pop($parts);
            $name = array_pop($parts);
            
            $identifier = implode('.', $parts).'.'.$name;
    
            $rowset = KFactory::tmp($identifier)->set($column, $id)->limit(0)->getList();

            $count = $rowset->count();

            $what  = ucfirst( ($count == 1) ? KInflector::singularize($name) : $name);        
            if($count) 
            {
                if ( $result = $rowset->delete() ) {
                    KFactory::get('lib.joomla.application')->enqueueMessage("$count $what deleted");
                } else {
                    KFactory::get('lib.joomla.application')->enqueueMessage("Delete $what failed",'notice');
                }
            } 
            else $result = true;
        }
        
        return $result;
              
    }
}
<?php
/**
 * @version      $Id$
 * @category	 Nooku
 * @package      Nooku_Server
 * @subpackage   Categories
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
 * @subpackage  Categories    
 */
class ComCategoriesDatabaseBehaviorCascadable extends KDatabaseBehaviorAbstract
{
    /**
     * Deletes dependent rows.
     *
     * This performs an intelligent delete 
     *
     * @return KDatabaseRowAbstract
     */
    protected function _beforeTableDelete(KCommandContext $context)
    {
        $section = $this->section;
        if ( is_numeric($section) || !$section) {
            $section = 'com_content';
        } 
        
        $parts = explode('_',$section);
            
        //@TODO : Remove when refactoring is completed
        switch ($parts[1])
        {
            case 'content':
                $name = 'articles';
                $package ='content';
                break;
            case 'contact':
                $name = 'contacts';
                $package ='contact';
                break;
            default :
                $name = KInflector::pluralize($parts[1]);
                $package = $name;
        }
                 
        $identifier = 'admin::com.'.$package.'.model.'.$name;
        
        $rowset = KFactory::tmp($identifier)->category($this->id)->limit(0)->getList();

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
        
        return $result;
              
    }
}
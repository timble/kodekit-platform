<?php
/**
 * @version      $Id$
 * @category     Nooku
 * @package      Nooku_Server
 * @subpackage   Banners
 * @copyright    Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license      GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link         http://www.nooku.org
 */

/**
 * Banners Orderable Behavior Class
 *
 * @author      Cristiano Cucco <http://nooku.assembla.com/profile/cristiano.cucco>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Banners    
 */
class ComBannersDatabaseBehaviorHittable extends KDatabaseBehaviorAbstract
{
	/**
     * Get the methods that are available for mixin based
     * 
     * This function conditionaly mixies the behavior. Only if the mixer 
     * has a 'hits' property the behavior will be mixed in.
     * 
     * @param object The mixer requesting the mixable methods. 
     * @return array An array of methods
     */
    public function getMixableMethods(KObject $mixer = null)
    {
        $methods = array();
          
        if(isset($mixer->hits)) {
            $methods = parent::getMixableMethods($mixer);
        }
      
       return $methods;    
    }
        
    /**
     * Increase hit counter by 1
     *
     * Requires a 'hits' column
     */
    public function hit()
    {
         $this->hits++;
                
         if(!$this->isNew()) {
             $this->save();
         }

         return $this->_mixer;
     }
}
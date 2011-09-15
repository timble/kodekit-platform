<?php
/**
 * @version     $Id: config.php 860 2011-08-12 11:18:55Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Config Database Row Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */
class ComFilesDatabaseBehaviorSluggable extends KDatabaseBehaviorSluggable
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'columns'   => array('title')
        ));

        parent::_initialize($config);
    }
    	
    protected function _createSlug()
    {
        if(empty($this->slug))
        {
            $this->slug = 'com_files.container'.$this->id;
                        
            //Canonicalize the slug
            $this->_canonicalizeSlug();
        }
        else
        {
            if(in_array('slug', $this->getModified())) 
            {
                //Canonicalize the slug
                $this->_canonicalizeSlug();
            }
        }
    }	
}
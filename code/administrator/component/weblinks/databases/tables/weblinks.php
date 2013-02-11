<?php
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Weblink Database Table Class
 *
 * @author      Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 */
class ComWeblinksDatabaseTableWeblinks extends KDatabaseTableDefault
{
    public function  _initialize(KConfig $config) 
    {
        $config->append(array(
        	'name'         => 'weblinks',
            'behaviors'    =>  array(
            	'creatable', 'modifiable', 'lockable', 'sluggable',
        		'com://admin/categories.database.behavior.orderable' => array('parent_column' => 'categories_category_id'),
            ),
          	'filters' => array(
             	'description' => array('html', 'tidy'),
               	'url'         => array('url'),
             	'params'      => array('ini')
              ),
        ));
     
        parent::_initialize($config);
     }
}
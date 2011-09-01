<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
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
        $config->identity_column = 'id';
        $orderable = $this->getBehavior('com://admin/categories.database.behavior.orderable', array('parent_column' => 'catid'));
        
        $config->append(array(
        	'name'         => 'weblinks',
            'base'         => 'weblinks',
            'behaviors'    =>  array('lockable', $orderable, 'sluggable'),
        	'column_map'   =>  array(
            	'enabled'   => 'published',
             	'locked_on' => 'checked_out_time',
              	'locked_by' => 'checked_out',
              	'slug'      => 'alias'
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
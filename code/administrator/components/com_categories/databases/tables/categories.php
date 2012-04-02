<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Categories Database Table Class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 */
class ComCategoriesDatabaseTableCategories extends KDatabaseTableDefault
{
    public function  _initialize(KConfig $config)
    {
        $config->identity_column = 'id';
       
        $config->append(array(
            'name'       => 'categories',
            'behaviors'  => array(
            	'lockable', 'sluggable', 'cascadable',
            	'com://admin/categories.database.behavior.orderable'  => array('parent_column' => 'section_id'),
            ),
            'column_map' => array(
                'enabled'      => 'published',
                'locked_on'    => 'checked_out_time',
                'locked_by'    => 'checked_out',
                'section_id'   => 'section',
                'slug'         => 'alias'
                ),
            ));

        parent::_initialize($config);
    }
}

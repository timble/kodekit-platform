<?php
/**
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
 * @package     Nooku_Server
 * @subpackage  Categories
 */
class ComCategoriesDatabaseTableCategories extends KDatabaseTableDefault
{
    public function  _initialize(KConfig $config)
    {
        $config->append(array(
            'name'       => 'categories',
            'behaviors'  => array(
            	'creatable', 'modifiable', 'lockable', 'sluggable', 'cascadable', 'nestable',
            	'orderable'  => array('parent_column' => 'parent_id'),
            )
            ));

        parent::_initialize($config);
    }
}

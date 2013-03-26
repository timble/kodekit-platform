<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Categories;

use Nooku\Framework;

/**
 * Categories Database Table
 *
 * @author  John Bell <http://nooku.assembla.com/profile/johnbell>
 * @package Nooku\Component\Categories
 */
class DatabaseTableCategories extends Framework\DatabaseTableDefault
{
    public function  _initialize(Framework\Config $config)
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

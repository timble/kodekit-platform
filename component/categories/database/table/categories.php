<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Categories;

use Nooku\Library;

/**
 * Categories Database Table
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Categories
 */
class DatabaseTableCategories extends Library\DatabaseTableAbstract
{
    public function  _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'name'       => 'categories',
            'behaviors'  => array(
            	'creatable', 'modifiable', 'lockable', 'sluggable', 'cascadable', 'nestable',
            	'orderable'  => array('parent_column' => 'parent_id'),
                'com:attachments.database.behavior.attachable',
            )
            ));

        parent::_initialize($config);
    }
}

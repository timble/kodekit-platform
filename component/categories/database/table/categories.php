<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Categories;

use Nooku\Library;

/**
 * Categories Database Table
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Categories
 */
class DatabaseTableCategories extends Library\DatabaseTableAbstract
{
    public function  _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'name'       => 'categories',
            'behaviors'  => array(
            	'creatable', 'modifiable', 'lockable', 'sluggable', 'cascadable', 'nestable', 'identifiable',
            	'orderable'  => array('parent_column' => 'parent_id'),
                'com:attachments.database.behavior.attachable',
            ),
            'filters' => array(
                'description'   => array('html', 'tidy')
            )
            ));

        parent::_initialize($config);
    }
}

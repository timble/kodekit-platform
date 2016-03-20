<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-categories for the canonical source repository
 */

namespace Kodekit\Component\Categories;

use Kodekit\Library;

/**
 * Categories Database Table
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Categories
 */
class DatabaseTableCategories extends Library\DatabaseTableAbstract
{
    public function  _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'name'       => 'categories',
            'behaviors'  => array(
            	'creatable', 'modifiable', 'lockable', 'sluggable', 'nestable', 'recursable', 'identifiable', 'orderable' ,
                'com:attachments.database.behavior.attachable',
            ),
            'filters' => array(
                'description'   => array('html', 'tidy')
            )
            ));

        parent::_initialize($config);
    }
}

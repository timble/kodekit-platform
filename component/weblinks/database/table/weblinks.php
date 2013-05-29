<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Weblinks;

use Nooku\Library;

/**
 * Weblinks Database Table
 *
 * @author  Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @package Nooku\Component\Weblinks
 */
class DatabaseTableWeblinks extends Library\DatabaseTableDefault
{
    public function  _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
        	'name'         => 'weblinks',
            'behaviors'    =>  array(
            	'creatable', 'modifiable', 'lockable', 'sluggable',
        		'com:categories.database.behavior.orderable' => array('parent_column' => 'categories_category_id'),
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
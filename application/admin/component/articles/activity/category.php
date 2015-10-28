<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Articles Category Activity
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Component\Users
 */
class ArticlesActivityCategory extends ActivitiesModelEntityActivity
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'object_table'  => 'categories',
            'object_column' => 'categories_category_id'
        ));

        parent::_initialize($config);
    }
}

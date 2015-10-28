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
 * Articles Tag Activity
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Component\Users
 */
class ArticlesActivityTag extends ActivitiesModelEntityActivity
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'object_table' => 'tags',
            'object_column' => 'tags_tag_id'
        ));

        parent::_initialize($config);
    }
}
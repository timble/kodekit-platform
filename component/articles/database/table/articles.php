<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Articles;

use Nooku\Library;

/**
 * Articles Database Table
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Articles
 */
class DatabaseTableArticles extends Library\DatabaseTableAbstract
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'name'       => 'articles',
            'behaviors'  => array(
            	'creatable', 'modifiable', 'lockable', 'sluggable', 'revisable', 'publishable', 'parameterizable', 'identifiable', 'com:varnish.database.behavior.varnishable',
                'orderable' => array(
                    'strategy' => 'flat'
                ),
                'com:languages.database.behavior.translatable',
                'com:attachments.database.behavior.attachable',
                'com:categories.database.behavior.categorizable',
                'com:tags.database.behavior.taggable',
                'com:comments.database.behavior.commentable'
            ),
            'filters' => array(
                'parameters' => 'json',
                'introtext'   => array('html', 'tidy'),
                'fulltext'    => array('html', 'tidy'),
		    ),
            'column_map' => array(
                'parameters' => 'params',
            )
        ));

        parent::_initialize($config);
    }
}
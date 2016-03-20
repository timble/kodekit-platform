<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-articles for the canonical source repository
 */

namespace Kodekit\Component\Articles;

use Kodekit\Library;

/**
 * Articles Database Table
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Component\Articles
 */
class DatabaseTableArticles extends Library\DatabaseTableAbstract
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'name'       => 'articles',
            'behaviors'  => array(
            	'creatable', 'modifiable', 'lockable', 'sluggable', 'revisable', 'publishable', 'parameterizable', 'identifiable',
                'com:languages.database.behavior.translatable',
                'com:attachments.database.behavior.attachable',
                'com:categories.database.behavior.categorizable',
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
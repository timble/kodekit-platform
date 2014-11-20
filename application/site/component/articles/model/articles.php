<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Articles;

/**
 * Article Model
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Articles
 */
class ArticlesModelArticles extends Articles\ModelArticles
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array(
                'searchable' => array('columns' => array('title', 'introtext', 'fulltext')),
                'com:categories.model.behavior.categorizable',
                'com:revisions.model.behavior.revisable',
                'com:tags.model.behavior.taggable',
            ),
        ));

        parent::_initialize($config);
    }
}
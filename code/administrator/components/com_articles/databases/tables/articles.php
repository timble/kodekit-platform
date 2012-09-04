<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Articles Database Table class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Articles
 */
class ComArticlesDatabaseTableArticles extends KDatabaseTableDefault
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'name'       => 'articles',
            'behaviors'  => array(
            	'creatable', 'modifiable', 'lockable', 'orderable', 'sluggable', 'revisable', 'publishable',
                'com://admin/languages.database.behavior.translatable'
            ),
            'column_map' => array(
                'slug'       	   => 'alias',
            ),
            'filters' => array(
                'introtext'   => array('html', 'tidy'),
                'fulltext'    => array('html', 'tidy'),
		    )
        ));

        parent::_initialize($config);
    }
}
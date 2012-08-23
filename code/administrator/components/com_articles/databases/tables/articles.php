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
                'locked_on'        => 'checked_out_time',
                'locked_by'        => 'checked_out',
                'slug'       	   => 'alias',
                'created_on' 	   => 'created',
                'modified_on'      => 'modified',
                'published_on'     => 'publish_up',
                'unpublished_on'   => 'publish_down',
                'description'      => 'metadesc',
                'params'		   => 'attribs'
            ),
            'filters' => array(
                'introtext'   => array('html', 'tidy'),
                'fulltext'    => array('html', 'tidy'),
                'params'	  => 'ini'
		    )
        ));

        parent::_initialize($config);
    }
}
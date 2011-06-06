<?php
/**
 * @version     $Id: articles.php 1475 2011-05-25 10:29:08Z gergoerdosi $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Sections Database Table class
 *
 * @author      John Bell <http://nooku.assembla.com/profile/johnbell>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 */
class ComArticlesDatabaseTableArticles extends KDatabaseTableDefault
{
    public function  _initialize(KConfig $config)
    {
        $config->identity_column = 'id';

        $config->append(array(
            'name'          => 'content',
            'behaviors'     => array('creatable', 'modifiable', 'lockable', 'orderable', 'sluggable'),
            'column_map'    => array(
                'locked_on'   => 'checked_out_time',
                'locked_by'   => 'checked_out',
                'slug'        => 'alias',
                'section_id'  => 'sectionid',
                'category_id' => 'catid',
                'created_on'  => 'created',
                'modified_on' => 'modified',
                'meta_description' => 'metadesc',
                'meta_keywords'    => 'metakey',
                'meta_data'        => 'metadata'
            ),
            'filters' => array(
    			'description' => array('html', 'tidy')
		    )
        ));

        parent::_initialize($config);
    }
}
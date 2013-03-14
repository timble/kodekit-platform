<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Articles;

use Nooku\Framework;

/**
 * Articles Database Table
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Articles
 */
class DatabaseTableArticles extends Framework\DatabaseTableDefault
{
    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
            'name'       => 'articles',
            'behaviors'  => array(
            	'creatable', 'modifiable', 'lockable', 'sluggable', 'revisable', 'publishable',
                'orderable' => array(
                    'strategy' => 'flat'
                ),
                'com://admin/languages.database.behavior.translatable',
                'com://admin/attachments.database.behavior.attachable',
                'com://admin/terms.database.behavior.taggable'
            ),
            'filters' => array(
                'introtext'   => array('html', 'tidy'),
                'fulltext'    => array('html', 'tidy'),
		    )
        ));

        parent::_initialize($config);
    }
}
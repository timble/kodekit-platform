<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Frontpage
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Frontpage Database Table Class
 *
 * @author      Richie Mortimer <http://nooku.assembla.com/profile/ravenlife>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Frontpage
 */
class ComFrontpageDatabaseTableArticles extends KDatabaseTableDefault
{
    public function  _initialize(KConfig $config)
    {
        $config->append(array(
            'identity_column' => 'content_id',
            'name'            => 'content_frontpage',
            'behaviors'       => array('orderable'),
            'column_map'      => array(
                'section_id'  => 'sectionid',
                'category_id' => 'catid'
            )
        ));

        parent::_initialize($config);
    }
}
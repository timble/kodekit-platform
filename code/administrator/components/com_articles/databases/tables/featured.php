<?php
/**
 * @version     $Id: articles.php 1629 2011-06-07 16:28:00Z johanjanssens $
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
class ComArticlesDatabaseTableFeatured extends KDatabaseTableDefault
{
    public function  _initialize(KConfig $config)
    {
        $config->identity_column = 'content_id';
        
        $config->append(array(
            'name'       => 'content_frontpage',
            'behaviors'  => array('orderable')
        ));

        parent::_initialize($config);
    }
}
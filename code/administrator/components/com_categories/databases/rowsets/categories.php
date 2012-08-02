<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Categories Database Rowset Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Categories
 */

class ComCategoriesDatabaseRowsetCategories extends ComCategoriesDatabaseRowsetNodes
{
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'new'               => false,
            'identity_column'   => 'id'
        ));

        parent::_initialize($config);
    }
}
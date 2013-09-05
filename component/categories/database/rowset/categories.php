<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Categories;

use Nooku\Library;

/**
 * Categories Database Rowset
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Categories
 */
class DatabaseRowsetCategories extends DatabaseRowsetNodes
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'status'          => Library\Database::STATUS_LOADED,
            'identity_column' => 'id'
        ));

        parent::_initialize($config);
    }
}
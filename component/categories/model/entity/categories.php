<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Categories;

use Nooku\Library;

/**
 * Categories Model Entity
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Categories
 */
class ModelEntityCategories extends ModelEntityNodes
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'status'          => self::STATUS_FETCHED,
            'identity_column' => 'id'
        ));

        parent::_initialize($config);
    }
}
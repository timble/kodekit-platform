<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Platform\Articles;

use Nooku\Library;
use Nooku\Component\Pages;

/**
 * Articles Module Html View
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Component\Articles
 */
class ModuleArticlesHtml extends Pages\ModuleEntity
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'parameters' => array(
                'access'    => $this->getObject('user')->isAuthentic(),
                'limit'     => 5,
                'sort'      => 'created_on',
                'direction' => 'DESC',
                'published' => 1,
                'category'  => null
            ),
        ));

        parent::_initialize($config);
    }
}
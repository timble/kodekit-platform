<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Pages;

/**
 * Pages Module Html View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Pages
 */
class PagesModuleMenuHtml extends Pages\ModuleEntity
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'controller' => 'com:pages.controller.pages',
            'parameters' => array(
                'menu'      => 1,
                'hidden'    => 0,
                'published' => true,
            ),
        ));

        parent::_initialize($config);
    }
}


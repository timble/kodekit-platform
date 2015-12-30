<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Application;

/**
 * Document Controller
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Application
 */
class ApplicationControllerDocument extends Application\ControllerDocument
{
    protected function  _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'toolbars'  => array(
                'actionbar',
                'com:pages.controller.toolbar.menubar',
                'com:pages.controller.toolbar.tabbar'
            ),
        ));

        parent::_initialize($config);
    }
}
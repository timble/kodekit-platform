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
 * Page Controller
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Application
 */
class ApplicationControllerPage extends Application\ControllerPage
{
    /**
     * Constructor.
     *
     * @param  Library\ObjectConfig $config  An optional Library\ObjectConfig object with configuration options.
     */
    protected function  _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'toolbars'  => array('menubar', 'tabbar', 'actionbar'),
        ));

        parent::_initialize($config);
    }
}
<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

use Nooku\Framework;

/**
 * Default Model
 *
 * @author  Johan Janssens <johan@nooku.org>
 * @package Nooku\Component\Debug
 */
class ComDebugModelDefault extends Framework\ModelAbstract
{
    protected function _initialize(Framework\Config $config)
    {
        parent::_initialize($config);

        //Don't dispatch event or allow callbacks
        $config->dispatch_events  = false;
        $config->enable_callbacks = false;
    }
}
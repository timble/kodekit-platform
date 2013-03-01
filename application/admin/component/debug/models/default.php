<?php
/**
 * @package     Nooku_Server
 * @subpackage  Debug
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */


/**
 * Default Debug Model
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Debug
 */
class ComDebugModelDefault extends KModelAbstract
{
    protected function _initialize(KConfig $config)
    {
        parent::_initialize($config);

        //Don't dispatch event or allow callbacks
        $config->dispatch_events  = false;
        $config->enable_callbacks = false;
    }
}
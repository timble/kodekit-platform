<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Debug Controller Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 */
class ComDebugControllerDebug extends ComDefaultControllerResource
{
    /**
     * Constructor.
     *
     * @param   object  An optional KConfig object with configuration options.
     */
    protected function  _initialize(KConfig $config) 
    {
        parent::_initialize($config);

        //We don't need events and other magic
        $config->dispatch_events  = false;
        $config->enable_callbacks = false;

        //Force request variables
        $config->request->view   = 'debug';
        $config->request->layout = 'default';
    }
}
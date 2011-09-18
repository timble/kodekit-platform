<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Component Controller Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 */
class ComInstallerControllerComponent extends ComInstallerControllerDefault
{
    /**
     * Get the request information
     *
     * Resetting the option state
     *
     * @return KConfig	A KConfig object with request information
     */
    public function getRequest()
    {
        $request = parent::getRequest();
        
        $request->option = null;
        $request->parent = 0;
    
    	return $request;
    }
}
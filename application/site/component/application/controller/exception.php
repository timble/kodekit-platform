<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;
use Nooku\Component\Application;

/**
 * Exception Controller Class
 *   
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ApplicationControllerException extends Application\ControllerException
{
    public function getRequest()
    {
        $request = parent::getRequest();

        //Force layout to default.
        $request->query->layout = 'default';

        return $request;
    }
}
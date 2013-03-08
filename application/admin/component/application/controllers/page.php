<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Page Controller Class
 *   
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationControllerPage extends ComBaseControllerView
{
    protected function _actionRender(Framework\CommandContext $context)
    {
        $content = parent::_actionRender($context);

        //Make images paths absolute
        $base = $this->getService('request')->getBaseUrl();
        $site = $this->getService('application')->getSite();

        $path = $base->getPath().'/files/'.$site.'/images/';

        $content = str_replace($base.'/images/', $path, $content);
        $content = str_replace(array('../images', './images') , '"'.$path, $content);

        $context->response->setContent($content);
        return $content;
    }
}
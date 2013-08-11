<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Page Controller
 *   
 * @author   Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @@package Component\Application
 */
class ApplicationControllerPage extends Library\ControllerView
{
    protected function _actionRender(Library\CommandContext $context)
    {
        $content = parent::_actionRender($context);

        //Make images paths absolute
        $base = $this->getObject('request')->getBaseUrl();
        $site = $this->getObject('application')->getSite();

        $path = $base->getPath().'/files/'.$site.'/images/';

        $content = str_replace($base.'/images/', $path, $content);
        $content = str_replace(array('"images/','"/images/', '"sites/'.$site.'/images') , '"'.$path, $content);

        $context->response->setContent($content);

        return $content;
    }
}
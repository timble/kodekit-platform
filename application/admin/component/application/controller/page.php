<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;

/**
 * Page Controller Class
 *   
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ApplicationControllerPage extends Library\ControllerView
{
    /**
     * Constructor.
     *
     * @param   object  An optional Library\ObjectConfig object with configuration options.
     */
    protected function  _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'toolbars'  => array('menubar', 'tabbar', 'toolbar'),
        ));

        parent::_initialize($config);
    }

    protected function _actionRender(Library\CommandContext $context)
    {
        $content = parent::_actionRender($context);

        //Make images paths absolute
        $base = $this->getObject('request')->getBaseUrl();
        $site = $this->getObject('application')->getSite();

        $path = $base->getPath().'/files/'.$site.'/images/';

        $content = str_replace($base.'/images/', $path, $content);
        $content = str_replace(array('../images', './images') , '"'.$path, $content);

        $context->response->setContent($content);
        return $content;
    }
}
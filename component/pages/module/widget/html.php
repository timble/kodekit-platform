<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Widget Module Html View
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Pages
 */
class ModuleWidgetHtml extends ModuleDefaultHtml
{
    protected function _actionRender(Library\ViewContext $context)
    {
        $params = $this->module->getParameters();

        $function = '_'.$params->get('layout', 'overlay');
    	return $this->$function();
    }

    public function _inline()
    {
        $params = $this->module->getParameters();

        $url = $this->getObject('lib:http.url', array('url' => $params->get('url')));

        $parts   = $url->getQuery(true);
        $package = substr($parts['option'], 4);
        $view    = Library\StringInflector::singularize($parts['view']);

        $identifier = 'com:'.$package.'.controller.'.$view;

        //Render the component
        $html = $this->getObject($identifier, array('request' => $parts))->render();

        return $html;
    }

    public function _overlay()
    {
        $params = $this->module->getParameters();
        $helper = $this->getTemplate()->getHelper('behavior');

        $route   = $this->getRoute($params->get('url'));
        $options = array('options' => array('selector' => $params->get('selector', 'body')));

        //Create the overlay
        $html = $helper->overlay(array('url' => $route, $options));

        return $html;
    }
} 
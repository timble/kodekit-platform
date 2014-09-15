<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Widget Module Html View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
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
        $package = $parts['component'];
        $view    = Library\StringInflector::singularize($parts['view']);

        $identifier = 'com:'.$package.'.controller.'.$view;

        //Render the component
        $html = $this->getObject($identifier, array('request' => $parts))->render();

        return $html;
    }

    public function _overlay()
    {
        $params = $this->module->getParameters();
        $route   = $this->getRoute($params->get('url'));
        $options = array('options' => array('selector' => $params->get('selector', 'body')));

        //Create the overlay
        $html = $this->getTemplate()->createHelper('behavior')->overlay(array('url' => $route, $options));

        return $html;
    }
} 
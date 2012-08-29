<?php
/**
 * @version     $Id: html.php 1481 2012-02-10 01:46:24Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Default
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Widget Module Html View Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @package     Nooku_Server
 * @subpackage  Default
 */
 
class ComDefaultModuleWidgetHtml extends ComDefaultModuleDefaultHtml
{
    public function display()
    {
    	$function = '_'.$this->module->params->get('layout', 'overlay'));
    	return $this->$function();
    }

    public function _inline()
    {
        $url = $this->getService('koowa:http.url', array('url' => $this->module->params->get('url')));

        $parts   = $url->getQuery(true);
        $package = substr($parts['option'], 4);
        $view    = KInflector::singularize($parts['view']);

        $identifier = 'com://site/'.$package.'.controller.'.$view;

        //Render the component
        $html = $this->getService($identifier, array('request' => $parts))->display();

        return $html;
    }

    public function _overlay()
    {
        $helper = $this->getTemplate()->getHelper('behavior');

        $route   = $this->getRoute($this->params->get('url'));
        $options = array('options' => array('selector' => $this->module->params->get('selector', 'body')));

        //Create the overlay
        $html = $helper->overlay(array('url' => $route, $options));

        return $html;
    }
} 
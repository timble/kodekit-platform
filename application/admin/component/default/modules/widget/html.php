<?php
/**
 * @package     Nooku_Modules
 * @subpackage  Widget
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Widget Module Html View Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @package     Nooku_Modules
 * @subpackage  Widget
 */
 
class ComDefaultModuleWidgetHtml extends ComDefaultModuleDefaultHtml
{
    public function render()
    {
    	$function = '_'.$this->module->params->get('layout', 'overlay');
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
        $html = $this->getService($identifier, array('request' => $parts))->render();

        return $html;
    }

    public function _overlay()
    {
        $helper = $this->getTemplate()->getHelper('behavior');

        $route   = $this->getRoute($this->module->params->get('url'));
        $options = array('options' => array('selector' => $this->module->params->get('selector', 'body')));

        //Create the overlay
        $html = $helper->overlay(array('url' => $route, $options));

        return $html;
    }
} 
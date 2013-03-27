<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Default Module
 *
 * @author  Johan Janssens <johan@nooku.org>
 * @package Nooku\Component\Pages
 */
class ModuleDefaultHtml extends Library\ViewTemplate
{
    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   Config $config An optional Library\Config object with configuration options
     * @return  void
     */
    protected function _initialize(Library\Config $config)
    {
        $config->append(array(
            'mimetype'   => 'text/html',
            'model'      => 'com:pages.model.module',
            'media_url'  => $this->getService('request')->getBaseUrl()->getPath().'/media',
        ));

        parent::_initialize($config);
    }

    /**
     * Renders and echo's the views output
     *
     * @return DefaultHtml
     */
    public function render()
    {
        //Dynamically attach the chrome filter
        if(!empty($this->module->chrome))
        {
            $this->getTemplate()->attachFilter('com:pages.template.filter.chrome', array(
                'module' => $this->getIdentifier(),
                'styles' => $this->module->chrome
            ));
        }

        return parent::render();
    }

    /**
     * Get a route based on a full or partial query string.
     *
     * This function force the route to be not fully qualified and not escaped
     *
     * @param   string  $route      The query string used to create the route
     * @param   boolean $fqr        If TRUE create a fully qualified route. Default FALSE.
     * @param   boolean $escape     If TRUE escapes the route for xml compliance. Default FALSE.
     * @return  string  The route
     */
    public function getRoute($route = '', $fqr = null, $escape = null)
    {
        //If not set force to false
        if ($fqr === null) {
            $fqr = false;
        }

        return parent::getRoute($route, $fqr, $escape);
    }
}
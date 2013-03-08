<?php
/**
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Default Module View
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultModuleDefaultHtml extends Framework\ViewTemplate
{
    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   KCofnig $config An optional Framework\Config object with configuration options
     * @return  void
     */
    protected function _initialize(Framework\Config $config)
    {
        $config->append(array(
            'mimetype'   => 'text/html',
            'model'      => 'com://admin/default.model.module',
            'media_url'  => $this->getService('request')->getBaseUrl()->getPath().'/media',
        ));

        parent::_initialize($config);
    }

	/**
     * Renders and echo's the views output
     *
     * @return ModDefaultHtml
     */
    public function render()
    {
        //Dynamically attach the chrome filter
        if(!empty($this->module->chrome)) {
            $this->getTemplate()->attachFilter('chrome', array('styles' => $this->module->chrome));
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
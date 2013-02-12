<?php
/**
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Default Module View
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Modules
 * @subpackage  Default
 */
class ComDefaultModuleDefaultHtml extends KViewTemplate
{
    /**
     * Initializes the default configuration for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'mimetype'   => 'text/html',
            'model'      => 'com://admin/default.model.module',
            'media_url'  => KRequest::root() . '/media',
        ));

        parent::_initialize($config);
    }

    /**
     * Renders and echo's the views output
     *
     * @return ModDefaultHtml
     */
    public function display()
    {
        JFactory::getLanguage()->load($this->getIdentifier()->package, $this->module->name);

        //Dynamically attach the chrome filter
        if(!empty($this->module->chrome)) {
            $this->getTemplate()->attachFilter('chrome', array('styles' => $this->module->chrome));
        }

        return parent::display();
    }

    /**
     * Get a route based on a full or partial query string.
     *
     * This function force the route to be not fully qualified and not escaped
     *
     * @param    string    The query string used to create the route
     * @param     boolean    If TRUE create a fully qualified route. Default FALSE.
     * @param     boolean    If TRUE escapes the route for xml compliance. Default FALSE.
     * @return     string     The route
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
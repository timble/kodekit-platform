<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Rss View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\View
 */
class ViewRss extends ViewTemplate
{
    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param ObjectConfig $config	An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'mimetype' => 'application/rss+xml',
            'data'     => array(
                'update_period'    => 'hourly',
                'update_frequency' => 1
            )
        ));

        parent::_initialize($config);
    }

    /**
     * Get the layout to use
     *
     * @return   string The layout name
     */
    public function getLayout()
    {
        return 'default';
    }

    /**
     * Force the route to fully qualified and escaped by default
     *
     * @param   string  $route   The query string used to create the route
     * @param   boolean $fqr     If TRUE create a fully qualified route. Default TRUE.
     * @param   boolean $escape  If TRUE escapes the route for xml compliance. Default TRUE.
     * @return 	DispatcherRouterRoute 	The route
     */
    public function getRoute($route = '', $fqr = true, $escape = true)
    {
        return parent::getRoute($route, $fqr, $escape);
    }

    /**
     * Prepend the xml prolog
     *
     * @param  ViewContext  $context A view context object
     * @return string  The output of the view
     */
    protected function _actionRender(ViewContext $context)
    {
        //Prepend the xml prolog
        $result  = '<?xml version="1.0" encoding="utf-8" ?>';
        $result .=  parent::_actionRender($context);

        return $result;
    }
}
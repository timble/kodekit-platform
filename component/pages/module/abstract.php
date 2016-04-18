<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-pages for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Abstract Module
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Pages
 */
abstract class ModuleAbstract extends Library\ViewHtml
{
    /**
     * Initializes the config for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   Library\ObjectConfig $config  An optional ObjectConfig object with configuration options
     * @return  void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array(
                'com:pages.module.behavior.decoratable',
                'com:pages.module.behavior.localizable'
            )
        ));

        parent::_initialize($config);

        //Remove the localizable behavior
        $config->behaviors->remove('localizable');
    }

    /**
     *  A module is never a collection
     *
     * @return bool
     */
    public function isCollection()
    {
        return false;
    }

    /**
     * Fetch the view data
     *
     * This function will always fetch the model state. Model data will only be fetched if the auto_fetch property is
     * set to TRUE.
     *
     * @param Library\ViewContext   $context A view context object
     * @return void
     */
    protected function _fetchData(Library\ViewContext $context)
    {
        //Set the layout and view in the parameters.
        $context->parameters->layout = $context->layout;
        $context->parameters->view   = $this->getName();
    }

    /**
     * Qualify the layout
     *
     * Convert a relative layout URL into an absolute layout URL
     *
     * @param string $layout The view layout name
     * @param string $type   The filesystem locator type
     * @return string   The fully qualified template url
     */
    public function qualifyLayout($layout, $type = 'mod')
    {
        return parent::qualifyLayout($layout, $type);
    }
}
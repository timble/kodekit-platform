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
 * Abstract Module View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Pages
 */
abstract class ModuleAbstract extends Library\ViewHtml
{
    /**
     * Get the title
     *
     * @return 	string 	The title of the view
     */
    public function getTitle()
    {
        //return $this->getData()->module->title;
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
     * @param Library\ViewContext	$context A view context object
     * @return void
     */
    protected function _fetchData(Library\ViewContext $context)
    {
        //Set the module parameter1
        $context->parameters->append($context->data->module->getParameters()->toArray());

        //Set the layout and view in the parameters.
        $context->parameters->layout = $context->layout;
        $context->parameters->view   = $this->getName();
    }

    /**
     * Renders and echo's the views output
     *
     * @return ModuleAbstract
     */
    protected function _actionRender(Library\ViewContext $context)
    {
        //Force layout type to 'mod' to force using the module locator for partial layouts
        $layout = $context->layout;

        if (is_string($layout) && strpos($layout, '.') === false)
        {
            $identifier = $this->getIdentifier()->toArray();
            $identifier['type'] = 'mod';
            $identifier['name'] = $layout;
            unset($identifier['path'][0]);

            $context->layout = $this->getIdentifier($identifier);
        }

        return parent::_actionRender($context);
    }
}
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
 * Default Module Html View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Pages
 */
class ModuleDefaultHtml extends Library\ViewHtml
{
    /**
     * Renders and echo's the views output
     *
     * @return ModuleDefaultHtml
     */
    protected function _actionRender(Library\ViewContext $context)
    {
        //Dynamically attach the chrome filter
        if(!empty($this->module->chrome))
        {
            $this->getTemplate()->addFilter('com:pages.template.filter.chrome', array(
                'module' => $this->getIdentifier(),
                'styles' => $this->module->chrome
            ));
        }

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
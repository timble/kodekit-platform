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
 * Entity Module
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Pages
 */
abstract class ModuleEntity extends ModuleAbstract
{
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

        $config = array('request_query' => $context->parameters->toArray());
        $package = $this->getIdentifier()->package;
        $name    = $this->getName();

        $html = $this->getObject('com:'.$package.'.controller.'.$name, $config)
            ->layout($context->layout)
            ->render();

        //Set the html in the module
        $this->setContent($html);

        return $html;
    }
}
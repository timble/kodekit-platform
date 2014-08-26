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
 * Dynamic Module Html View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Pages
 */
class ModuleDynamicHtml extends ModuleDefaultHtml implements Library\ObjectMultiton
{
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

        $this->_content = (string) $this->getTemplate()->setContent($this->_content)->filter();

        return $this->_content;
    }
}
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
class ModuleDefaultHtml extends Library\ViewHtml
{
    /**
     * Renders and echo's the views output
     *
     * @return ModuleDefaultHtml
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
}
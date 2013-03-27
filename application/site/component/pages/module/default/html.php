<?php
/**
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;
use Nooku\Component\Pages;

/**
 * Default Module View
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Modules
 * @subpackage  Default
 */
class PagesModuleDefaultHtml extends Pages\ModuleDefaultHtml
{
    /**
     * Renders and echo's the views output
     *
     * @return PagesModuleDefaultHtml
     */
    public function render()
    {
        JFactory::getLanguage()->load($this->getIdentifier()->package, $this->module->name);
        return parent::render();
    }
}
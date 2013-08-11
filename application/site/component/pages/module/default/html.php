<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Pages;

/**
 * Default Module Html View
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Pages
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
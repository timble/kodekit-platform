<?php
/**
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Route Template Helper Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultTemplateHelperRoute extends Framework\TemplateHelperDefault
{
    /**
     * Find a page based on list of needles
     *
     * @param array $needles   An associative array of needles
     * @return
     */
    protected function _findPage($needles)
	{
        $component = $this->getService('application.components')->getComponent($this->getIdentifier()->package);
        $pages     = $this->getService('application.pages');

        return $pages->find(array('extensions_component_id' => $component->id, 'link' => $needles));
	}
}
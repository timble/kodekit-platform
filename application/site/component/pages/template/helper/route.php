<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Route Template Helper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Pages
 */
class PagesTemplateHelperRoute extends Library\TemplateHelperDefault
{
    /**
     * Find a page based on list of needles
     *
     * @param array $needles   An associative array of needles
     * @return
     */
    protected function _findPage($needles)
	{
        $extension = $this->getObject('application.extensions')->getExtension($this->getIdentifier()->package);
        $pages     = $this->getObject('application.pages');

        return $pages->find(array('extensions_extension_id' => $extension->id, 'link' => $needles));
	}
}
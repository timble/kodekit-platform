<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Route Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Pages
 */
class PagesTemplateHelperRoute extends Library\TemplateHelperAbstract
{
    /**
     * Find a page based on list of needles
     *
     * @param array $needles   An associative array of needles
     * @return
     */
    protected function _findPage($needles)
	{
        $pages  = $this->getObject('application.pages');
        return $pages->find(array('component' => $this->getIdentifier()->package, 'link' => $needles));
	}
}
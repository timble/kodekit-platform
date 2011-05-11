<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Plugins
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Plugin HTML View class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Plugins    
 */
class ComPluginsViewPluginHtml extends ComDefaultViewHtml
{
    public function display()
    {
		$plugin       = $this->getModel()->getItem();
		$manifest     = JPATH_SITE.'/plugins/'.$plugin->folder.'/'.$plugin->element.'.xml';
		$this->params = new JParameter($plugin->params, $manifest, 'plugin');

		return parent::display();
	}
}
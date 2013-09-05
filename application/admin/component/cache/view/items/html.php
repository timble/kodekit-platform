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
 * Items Html View
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Cache
 */
class CacheViewItemsHtml extends Library\ViewHtml
{
	public function render()
	{
        $group = $this->getModel()->getState()->group;
        
	    $this->groups = $this->getObject('com:cache.model.groups')->getRowset();
	    $this->size   = !empty($group) ? $this->groups->find($group)->size : $this->groups->size;
        $this->count  = !empty($group)? $this->groups->find($group)->count : $this->groups->count;
        
		return parent::render();
	}
}
<?php
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Items HTML View class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache    
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
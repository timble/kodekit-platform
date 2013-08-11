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
 * Attachement File View
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Attachments
 */
class AttachmentsViewAttachmentFile extends Library\ViewFile
{
	public function render()
	{
		$item = $this->getModel()->getRow();
    	$state = $this->getModel()->getState();
    	
    	$container = $this->getObject('com:files.model.containers')
    		->slug($item->container)
    		->getRow();

        $this->path = $container->path.'/'.$item->path;
        $this->filename = $item->name;

        if (!file_exists($this->path)) {
        	throw new Library\ViewException('File not found');
        }

        return parent::render();
	}
}
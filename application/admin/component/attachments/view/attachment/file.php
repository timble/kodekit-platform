<?php
/**
 * @package     Nooku_Server
 * @subpackage  Attachments
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;

/**
 * Attachment File View Class
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Attachments
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

        $this->path     = $container->path.'/'.$item->path;
        $this->filename = $item->name;

        if (!file_exists($this->path)) {
        	throw new Library\ViewException('File not found');
        }

        return parent::render();
	}
}
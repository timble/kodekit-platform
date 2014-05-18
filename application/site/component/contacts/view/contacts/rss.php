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
 * Contacts Rss View
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Contacts
 */
class ContactsViewContactsRss extends Library\ViewRss
{
    public function render()
    {
        //Get the category
        $this->category = $this->getCategory();
        return parent::render();
    }

    public function getCategory()
    {
        //Get the category
        $category = $this->getObject('com:contacts.model.categories')
                         ->table('contacts')
                         ->id($this->getModel()->getState()->category)
                         ->getRow();

        // Get the attachments container
        $container = $this->getObject('com:files.model.containers')
            ->slug('attachments-attachments')
            ->getRow();

        //Set the category image
        if (isset( $category->image ) && !empty($category->image))
        {
            $path = JPATH_FILES.'/'.$container->path.'/'.$category->image;
            $size = getimagesize($path);

            $category->image = (object) array(
                'path'   => '/'.str_replace(JPATH_ROOT.DS, '', $path),
                'width'  => $size[0],
                'height' => $size[1]
            );
        }

        return $category;
    }
}
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
 * Contacts Html View
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Contacts
 */
class ContactsViewContactsHtml extends Library\ViewHtml
{
    /**
     * Display the view
     *
     * @return	string	The output of the view
     */
    public function render()
    {
        //Get the parameters
        $params = $this->getObject('application')->getParams();

        //Get the category
        $category = $this->getCategory();

        //Set the pathway
        $page = $this->getObject('application.pages')->getActive();
        if($page->getLink()->query['view'] == 'categories' ) {
            $this->getObject('application')->getPathway()->addItem($category->title, '');
        }

        //Set the breadcrumbs
        $this->params   = $params;
        $this->category = $category;
        
        return parent::render();
    }

    public function getCategory()
    {
        //Get the category
        $category = $this->getObject('com:contacts.model.categories')
                         ->table('contacts')
                         ->id($this->getModel()->getState()->category)
                         ->getRow();

        return $category;
    }
}
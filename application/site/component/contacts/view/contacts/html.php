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

        //Get the contacts
        $contacts = $this->getModel()->fetch();

        //Get the category
        $category = $contacts->getCategory();

        //Get the parameters of the active menu item
        if ($page = $this->getObject('application.pages')->getActive())
        {
            $menu_params = new JParameter( $page->params );
            if (!$menu_params->get( 'page_title')) {
                $params->set('page_title',	$category->title);
            }
        }
        else $params->set('page_title',	$category->title);

        //Set the page title
        //JFactory::getDocument()->setTitle( $params->get( 'page_title' ) );

        //Set the pathway
        if($page->getLink()->query['view'] == 'categories' ) {
            $this->getObject('application')->getPathway()->addItem($category->title, '');
        }

        $this->params   = $params;
        $this->category = $category;
        
        return parent::render();
    }

    public function getCategory()
    {
        $category = $this->getObject('com:articles.model.categories')
            ->table('articles')
            ->id($this->getModel()->getState()->category)
            ->fetch();

        return $category;
    }
}
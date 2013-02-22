<?php
/**
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Contacts Html View
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Contacts
 */
class ComContactsViewContactsHtml extends ComDefaultViewHtml
{
    /**
     * Display the view
     *
     * @return	string	The output of the view
     */
    public function display()
    {
        //Get the parameters
        $params = $this->getService('application')->getParams();

        //Get the category
        $category = $this->getCategory();

        //Get the parameters of the active menu item
        if ($page = $this->getService('application.pages')->getActive())
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
            $this->getService('application')->getPathway()->addItem($category->title, '');
        }

        //Set the breadcrumbs
        $this->params   = $params;
        $this->category = $category;
        
        return parent::display();
    }

    public function getCategory()
    {
        //Get the category
        $category = $this->getService('com://site/contacts.model.categories')
                         ->table('contacts')
                         ->id($this->getModel()->getState()->category)
                         ->getRow();

        return $category;
    }
}
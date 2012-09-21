<?php
/**
* @version		$Id: html.php 3541 2012-04-02 18:24:42Z johanjanssens $
* @package     	Nooku_Server
* @subpackage  	Contacts
* @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
* @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
* @link			http://www.nooku.org
*/

/**
 * Contact Html View
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Contacts
 */
class ComContactsViewContactHtml extends ComDefaultViewHtml
{
    public function display()
    {
        //Get the parameters
        $params = $this->getService('application')->getParams();
        
        //Get the contact
        $contact = $this->getModel()->getData();

        //Get the category
        $category = $this->getCategory();

        //Get the parameters of the active menu item
        if ($page = $this->getService('application.pages')->getActive())
        {
            $menu_params = new JParameter( $page->params );
            if (!$menu_params->get( 'page_title')) {
                $params->set('page_title',	$contact->name);
            }
        }
        else $params->set('page_title',	$contact->name);

        //Set the page title
        JFactory::getDocument()->setTitle( $params->get( 'page_title' ) );

        //Set the breadcrumbs
        $pathway =$this->getService('application')->getPathway();

        if($page->link->query['view'] == 'categories' ) {
            $pathway->addItem($category->title, $this->getTemplate()->getHelper('route')->category(array('row' => $category)));
            $pathway->addItem($contact->name, '');
        }

        if($page->link->query['view'] == 'contacts' ) {
            $pathway->addItem($contact->name, '');
        }

        $this->assign('params', $params);
        return parent::display();
    }

    public function getCategory()
    {
        //Get the category
        $category = $this->getService('com://site/contacts.model.categories')
                         ->table('contacts')
                         ->id($this->getModel()->getState()->category)
                         ->getItem();

        return $category;
    }
}
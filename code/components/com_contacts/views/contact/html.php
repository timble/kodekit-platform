<?php
/**
* @version		$Id: html.php 3541 2012-04-02 18:24:42Z johanjanssens $
* @category		Nooku
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
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts
 */
class ComContactsViewContactHtml extends ComDefaultViewHtml
{
    public function display()
    {
        //Get the parameters
        $params = JFactory::getApplication()->getParams();
        
        //Get the contact
        $contact = $this->getModel()->getData();
        
        //Get the category
        $category = $this->getService('com://site/contacts.model.contacts')
                        ->id($this->getModel()->getState()->category)
                        ->getItem();

        // Set the page title
        $menu = JSite::getMenu()->getActive();

        if (is_object( $menu ))
        {
            $menu_params = new JParameter( $menu->params );
            if (!$menu_params->get( 'page_title')) {
                $params->set('page_title',	$category->title);
            }
        }
        else $params->set('page_title',	$category->title);

        JFactory::getDocument()->setTitle( $params->get( 'page_title' ) );

        //Set the breadcrumbs
        $pathway  = JFactory::getApplication()->getPathway();

        $view = JRequest::getString('view');
        if ( $view == 'categories' ) {
            $pathway->addItem($contact->category, 'index.php?view=category&id='.$contact->catslug);
        }

        $pathway->addItem($contact->title, '');

        $this->assign('params', $params);
        return parent::display();
    }
}
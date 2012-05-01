<?php
/**
 * @version		$Id: html.php 3541 2012-04-02 18:24:42Z johanjanssens $
 * @category	Nooku
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
 * @category 	Nooku
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
        $params = JFactory::getApplication()->getParams();
        
        //Get the category
        $category = $this->getService('com://site/contacts.model.contacts')
                        ->id($this->getModel()->getState()->category)
                        ->getItem();
        	
        // Get the parameters of the active menu item
        $menu = JSite::getMenu()->getActive();
        
        // Set the page title
        if (is_object( $menu ))
        {
            $menu_params = new JParameter( $menu->params );
            if (!$menu_params->get( 'page_title')) {
                $params->set('page_title',	$category->title);
            }
        }
        else $params->set('page_title',	$category->title);
        
        JFactory::getDocument()->setTitle( $params->get( 'page_title' ) );
        
        //set breadcrumbs
        JFactory::getApplication()->getPathway()->addItem($category->title, '');
        
        // Prepare category description
        $category->description = JHTML::_('content.prepare', $category->description);
      
        // Set up the category image
        if (isset( $category->image ) && $category->image != '')
        {
            $category->image = array(
        		'src'  		=> KRequest::base().'/'.str_replace(JPATH_ROOT.DS, '', JPATH_IMAGES.'/stories/'.$category->image),
        		'attribs' => array(
        			'align'  => $category->image_position,
        			'hspace' => 6,
        			'title'  => JText::_('Contacts')
                )
            );
        }
        
        $this->assign('params',	  $params);
        $this->assign('category', $category);
        
        return parent::display();
    }
}
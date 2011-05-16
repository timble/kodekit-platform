<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2009 - 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Category Html View
 *
 * @author    	Jeremy Wilken <http://nooku.assembla.com/profile/gnomeontherun>
 * @category 	Nooku
 * @package     Nooku_Components
 * @subpackage  Weblinks
 */
class ComWeblinksViewWeblinksHtml extends ComDefaultViewHtml
{
	/**
	 * Display the view
	 *
	 * @return	string	The output of the view
	 */
	public function display()
	{
	    $category   = $this->getModel()->getCategory();
	    $categories = KFactory::get('site::com.weblinks.model.categories')->getList();
		$params     = KFactory::get('lib.joomla.application')->getParams();

		// Set up the category image
		if (isset( $category->image ) && $category->image != '')
		{
			$category->image = array(
			    'src'  		=> KRequest::base().'/'.str_replace(JPATH_ROOT.DS, '', JPATH_IMAGES.'/stories/'.$category->image),
			    'attribs' => array(
			    		'align'  => $category->image_position,
			    		'hspace' => 6,
						'title'  => JText::_('Web Links')
			    )
		    );
		}

		// Set up icon for table display
		if ( $params->get( 'link_icons' ) != -1 ) 
		{
			$image = array(
				'src'   => 'media://system/images/'.$params->get('weblink_icons', 'weblink.png'),
			    'title' => JText::_('Link')
			);
			
			$this->assign('image', $image);
		}

		$this->assign('params'    , $params);
		$this->assign('category'  , $category);
		$this->assign('categories', $categories);

		return parent::display();
	}
}
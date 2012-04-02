<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Category Html View
 *
 * @author    	Babs Gšsgens <http://nooku.assembla.com/profile/babsgosgens>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 */
class ComNewsfeedsViewNewsfeedHtml extends ComDefaultViewHtml
{
	public function display()
	{
		//Get the parameters
		$params = JFactory::getApplication()->getParams();
		
		//Get the newsfeed
		$newsfeed = $this->getModel()->getData();
		
		//  get RSS parsed object
		$options = array();
		$options['rssUrl']		= $newsfeed->link;
		$options['cache_time']	= $newsfeed->cache_time;

		$xml = JFactory::getXMLparser('RSS', $options);

		if ( $xml == false ) 
		{
			$msg = JText::_('Error: Feed not retrieved');
			JFactory::getApplication()->redirect('index.php?option=com_newsfeeds&view=newsfeeds&category='. $newsfeed->catid, $msg);
			return;
		}
		
		$lists = array();

		// channel header and link
		$channel['title'] 	    = $xml->get_title();
		$channel['link'] 		= $xml->get_link();
		$channel['description'] = $xml->get_description();
		$channel['language'] 	= $xml->get_language();

		// channel image if exists
		$image['url']    = $xml->get_image_url();
		$image['title']  = $xml->get_image_title();
		$image['link']   = $xml->get_image_link();
		$image['height'] = $xml->get_image_height();
		$image['width']  = $xml->get_image_width();

		// items
		$items = $xml->get_items();

		// feed elements
		$items = array_slice($items, 0, $newsfeed->numarticles);

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

		//set breadcrumbs
		$pathway  = JFactory::getApplication()->getPathway();
		
		$view = JRequest::getString('view');
		if ( $view == 'categories' ) {
			$pathway->addItem($newsfeed->category, 'index.php?view=category&id='.$newsfeed->catslug);
		}
		
		$pathway->addItem($newsfeed->name, '');

		$this->assign('channel' , $channel  );
		$this->assign('image'   , $image   );
		$this->assign('items'   , $items   );
		$this->assign('params'  , $params   );
		$this->assign('newsfeed', $newsfeed );

		return parent::display();
	}

	function limitText($text, $wordcount)
	{
		if(!$wordcount) {
			return $text;
		}

		$texts = explode( ' ', $text );
		$count = count( $texts );

		if ( $count > $wordcount )
		{
			$text = '';
			for( $i=0; $i < $wordcount; $i++ ) {
				$text .= ' '. $texts[$i];
			}
			$text .= '...';
		}

		return $text;
	}
}

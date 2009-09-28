<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.koowa.org
 */

/**
 * Template Select Helper
 *
 * @author		Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 */
class KTemplateHelperPaginator extends KObject
{
	/**
	 * Render item pagination
	 *
	 * @param	int	Total number of items
	 * @param	int	Offset for the current page
	 * @param	int	Limit of items per page
	 * @param	int	Number of links to show before and after the current page link
	 * @return	string	Html
	 * @see  	http://developer.yahoo.com/ypatterns/navigation/pagination/
	 */
	public function pagination($total, $offset, $limit, $display = 4)
	{
		KFactory::get('lib.joomla.document')->addStylesheet(KRequest::root().'/media/plg_koowa/css/pagination.css');
		
		// Paginator object
		$paginator = KFactory::tmp('lib.koowa.model.paginator')->setData(
				array('total'  => $total,
					  'offset' => $offset,
					  'limit'  => $limit,
					  'display' => $display)
		);
				
		// Get the paginator data
		$list = $paginator->getList();
		
		$html  = '<div class="pagination">';
		$html .= '<div class="limit">'.JText::_('Display').'# '.$this->limit($limit).'</div>';
		$html .=  $this->pages($list);
		$html .= '<div class="count"> '.JText::_('Pages').' '.$paginator->current.' '.JText::_('of').' '.$paginator->count.'</div>';
		$html .= '</div>';
		
		return $html;
	}
	
	/**
	 * Render a list of pages links
	 *
	 * @param	araay 	An array of page data
	 * @return	string	Html
	 */
	public function pages($pages)
	{
		$html = '<ul class="pages">';
		
		$html .= '<li class="first">&laquo; '.$this->link($pages['first'], 'First').'</li>';
		$html .= '<li class="previous">&lt; '.$this->link($pages['previous'], 'Previous').'</li>';
		
		foreach($pages['pages'] as $page) {
			$html .= '<li>'.$this->link($page, $page->page).'</li>';
		}
		
		$html .= '<li class="next">'.$this->link($pages['next'], 'Next').' &gt;</li>';
		$html .= '<li class="previous">'.$this->link($pages['last'], 'Last').' &raquo;</li>';

		$html .= '</ul>';
		return $html;
	}
	
	/**
	 * Render a page link
	 *
	 * @param	object The page data
	 * @param	string The link title
	 * @return	string	Html
	 */
	public function link($page, $title)
	{
		$url   = clone KRequest::url();
		$query = $url->getQuery(true);
		
		$query['limit']  = $page->limit;	
		$query['offset'] = $page->offset;
		
		$class = $page->current ? 'class="active"' : '';
		
		if($page->active && !$page->current) {
			$html = '<a href="'.(string) $url->setQuery($query).'" '.$class.'>'.JText::_($title).'</a>';
		} else {
			$html = '<span '.$class.'>'.JText::_($title).'</span>';
		}
		
		return $html;
	}
	
	/**
	 * Render a select box with limit values
	 *
	 * @param 	int		Currenct limit
	 * @return 	string	Html select box
	 */
	public function limit($limit)
	{
		KTemplate::loadHelper('script', KRequest::root().'/media/plg_koowa/js/pagination.js');

		// Modify the url to include the limit
		$url   = clone KRequest::url();
		$query = $url->getQuery(true);
		
		$selected = '';
		foreach(array(10 => 10, 20 => 20, 50 => 50, 100 => 100, 0 => 'all' ) as $value => $text)
		{
			$query['limit'] = $value;
			$redirect       = (string) $url->setQuery($query);
			
			if($value == $limit) {
				$selected = $redirect;
			}
			
			$limits[] = KTemplate::loadHelper('select.option', $redirect,  JText::_($text));
		}

		$html = KTemplate::loadHelper('select.genericlist',  $limits, 'limit', 'class="inputbox autoredirect"', 'value', 'text', $selected);
		return $html;
	}
}
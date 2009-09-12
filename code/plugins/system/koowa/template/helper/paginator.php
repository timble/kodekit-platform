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
	 * Render a select box with limit values for a grid
	 *
	 * @param 	int		Currenct limit
	 * @return 	string	Html select box
	 */
	public function limit($limit)
	{
		KTemplate::loadHelper('script', KRequest::root().'/media/plg_koowa/js/pagination.js');

		// modify url
		$url   = clone KRequest::url();
		$query = $url->getQuery(true);
		
		$selected = '';
		foreach(array(10 => 10, 20 => 20, 50 => 50, 100 => 100, 0 =>'all' ) as $value => $text)
		{
			$query['limit'] = $value;
			$redirect = (string) $url->setQuery($query);
			
			if($value==$limit) {
				$selected = $redirect;
			}
			$limits[] = KTemplate::loadHelper('select.option', $redirect,  JText::_($text));
		}

		// Build the select list
		$html = KTemplate::loadHelper('select.genericlist',  $limits, 'limit', 'class="inputbox autoredirect"', 'value', 'text', $selected);

		return $html;
	}

	/*
	 * Render a list of pages links
	 *
	 * @param	int	Total number of items
	 * @param	int	Offset for the current page
	 * @param	int	Limit of items per page
	 * @param	int	Number of links to show before and after the current page link
	 * @return	string	Html
	 */
	public function pages($total, $offset, $limit, $display = 4)
	{
		KFactory::get('lib.joomla.document')->addStylesheet(KRequest::root().'/media/plg_koowa/css/pagination.css');
		
		// Paginator object
		$p = KFactory::tmp('lib.koowa.model.paginator')->setData(
				array('total'  => $total,
					  'offset' => $offset,
					  'limit'  => $limit,
					  'display' => $display));

		// modify url
		$url = clone KRequest::url();
		$query = $url->getQuery(true);
		
		$query['limit'] = $p->limit;

		// Html
		$html = '<ul class="pagination"><li>«</li>';

		foreach($p->getList() as $elem)
		{
			if($elem->active && !$elem->current)
			{
				$query['offset']	= $elem->offset;
				$link = (string) $url->setQuery($query);
				$link = '<a href="'.$link.'">'.JText::_($elem->text).'</a>';
			} else {
				$link = JText::_($elem->text);
			}
			$html .= '<li><span>'.$link.'</span></li>';
		}

		$html .= '<li>»</li></ul>';
		return $html;
	}
}
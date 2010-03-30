<?php
/**
 * @version     $Id: koowa.php 1296 2009-10-24 00:15:45Z johan $
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Default Paginator Helper
.*
 * @author		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Default
 */
class ComDefaultHelperPaginator extends KTemplateHelperPaginator
{
	/**
	 * Render item pagination
	 *
	 * @param	int	Total number of items
	 * @param	int	Offset for the current page
	 * @param	int	Limit of items per page
	 * @param	int	Number of links to show before and after the current page link
	 * @return	string	Html
	 */
	public function pagination($total, $offset, $limit, $display = 4)
	{
		// Paginator object
		$paginator = KFactory::tmp('lib.koowa.model.paginator')->setData(
				array('total'  => $total,
					  'offset' => $offset,
					  'limit'  => $limit,
					  'display' => $display)
		);
				
		// Get the paginator data
		$list = $paginator->getList();
		
		$html  = '<del class="container">';
		$html  = '<div class="pagination">';
		$html .= '<div class="limit">'.JText::_('Display').'# '.$this->limit($limit).'</div>';
		$html .=  $this->pages($list);
		$html .= '<div class="limit"> '.JText::_('Page').' '.$paginator->current.' '.JText::_('of').' '.$paginator->count.'</div>';
		$html .= '</div>';
		$html .= '</del>';
		
		return $html;
	}
	
	/**
	 * Render a list of pages links
	 * 
	 * This function is overriddes the default behavior to render the links in the khepri template
	 * backend style.
	 *
	 * @param	araay 	An array of page data
	 * @return	string	Html
	 */
	public function pages($pages)
	{
		$class = $pages['first']->active ? '' : 'off';
		$html  = '<div class="button2-right '.$class.'"><div class="start">'.$this->link($pages['first'], 'First').'</div></div>';
		
		$class = $pages['previous']->active ? '' : 'off';
		$html  .= '<div class="button2-right '.$class.'"><div class="prev">'.$this->link($pages['previous'], 'Prev').'</div></div>';
		
		$html  .= '<div class="button2-left"><div class="page">';
		foreach($pages['pages'] as $page) {
			$html .= self::link($page, $page->page);
		}
		$html .= '</div></div>';
		
		$class = $pages['next']->active ? '' : 'off';
		$html  .= '<div class="button2-left '.$class.'"><div class="next">'.$this->link($pages['next'], 'Next').'</div></div>';
		
		$class = $pages['last']->active ? '' : 'off';
		$html  .= '<div class="button2-left '.$class.'"><div class="end">'.$this->link($pages['last'], 'Last').'</div></div>';

		return $html;
	}
}
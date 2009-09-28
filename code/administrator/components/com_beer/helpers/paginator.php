<?php
/**
 * Business Enterprise Employee Repository (B.E.E.R)
 * 
 * @version		$Id: chart.php 228 2009-09-23 13:59:34Z tom $
 * @package		Beer
 * @copyright	Copyright (C) 2009 Nooku. All rights reserved.
 * @license 	GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

class BeerHelperPaginator extends KTemplateHelperPaginator
{
	/**
	 * Render item pagination
	 *
	 * @param	int	Total number of items
	 * @param	int	Offset for the current page
	 * @param	int	Limit of items per page
	 * @param	int	Number of links to show before and after the current page link
	 * @return	string	Html
	 * @see  	http://developer.yahoo.com/ypatterns/navigation/pagination/item.html
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
		$html .= '<div class="limit"> '.JText::_('Pages').' '.$paginator->current.' '.JText::_('of').' '.$paginator->count.'</div>';
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
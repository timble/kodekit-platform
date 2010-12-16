<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Templates
 * @subpackage	Witblits
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * This is a file to add template specific chrome to pagination rendering.
 *
 * pagination_list_footer
 *	 Input variable $list is an array with offsets:
 *		 $list[limit]		: int
 *		 $list[limitstart]	: int
 *		 $list[total]		: int
 *		 $list[limitfield]	: string
 *		 $list[pagescounter]	: string
 *		 $list[pageslinks]	: string
 *
 * pagination_list_render
 *	 Input variable $list is an array with offsets:
 *		 $list[all]
 *			 [data]		: string
 *			 [active]	: boolean
 *		 $list[start]
 *			 [data]		: string
 *			 [active]	: boolean
 *		 $list[previous]
 *			 [data]		: string
 *			 [active]	: boolean
 *		 $list[next]
 *			 [data]		: string
 *			 [active]	: boolean
 *		 $list[end]
 *			 [data]		: string
 *			 [active]	: boolean
 *		 $list[pages]
 *			 [{PAGE}][data]		: string
 *			 [{PAGE}][active]	: boolean
 *
 * pagination_item_active
 *	 Input variable $item is an object with fields:
 *		 $item->base	: integer
 *		 $item->link	: string
 *		 $item->text	: string
 *
 * pagination_item_inactive
 *	 Input variable $item is an object with fields:
 *		 $item->base	: integer
 *		 $item->link	: string
 *		 $item->text	: string
 *
 * This gives template designers ultimate control over how pagination is rendered.
 *
 * NOTE: If you override pagination_item_active OR pagination_item_inactive you MUST override them both
 */

function pagination_list_footer($list)
{
	// Initialize variables
	$lang =& JFactory::getLanguage();
	$html = "<div class=\"list-footer\">\n";

	if ($lang->isRTL())
	{
		$html .= "\n<div class=\"counter\">".$list['pagescounter']."</div>";
		$html .= $list['pageslinks'];
		$html .= "\n<div class=\"limit\">".JText::_('Display Num').$list['limitfield']."</div>";
	}
	else
	{
		$html .= "\n<div class=\"limit\">".JText::_('Display Num').$list['limitfield']."</div>";
		$html .= $list['pageslinks'];
		$html .= "\n<div class=\"counter\">".$list['pagescounter']."</div>";
	}

	$html .= "\n<input type=\"hidden\" name=\"limitstart\" value=\"".$list['limitstart']."\" />";
	$html .= "\n</div>";

	return $html;
}

function pagination_list_render($list){
	// Initialize variables
	$lang = JFactory::getLanguage();
	$html = "<ul class=\"pagination\">";
	$html .= '<li class="bookends">&laquo;</li>';

	// Reverse output rendering for right-to-left display
	if($lang->isRTL()){
		
		// next
		$html .= '<li class="next">'.$list['next']['data'].'</li>';
		
		// end
		$html .= '<li class="end">'.$list['end']['data'].'</li>';
			
		// pages
		$list['pages'] = array_reverse( $list['pages'] );
		$i = 1;
		foreach($list['pages'] as $page){
			if($page['active'] == true){ 
				$html .= '<li class="page'.$i.'">'.$page['data'].'</li>';
			}else{
				$html .= '<li class="page'.$i.' active">'.$page['data'].'</li>';
			}
			$i++;
		}
		
		// start
		$html .= '<li class="start">'.$list['start']['data'].'</li>';
		
		// previous
		$html .= '<li class="previous">'.$list['previous']['data'].'</li>';
		
	}else{		
		// start
		$html .= '<li class="start">'.$list['start']['data'].'</li>';
		
		// previous
		$html .= '<li class="previous">'.$list['previous']['data'].'</li>';
		
		// pages
		$i = 1;
		foreach($list['pages'] as $page){
			if($page['active'] == true){ 
				$html .= '<li class="page'.$i.'">'.$page['data'].'</li>';
			}else{
				$html .= '<li class="page'.$i.' active">'.$page['data'].'</li>';
			}
			$i++;
		}
		
		// next
		$html .= '<li class="next">'.$list['next']['data'].'</li>';
		
		// end
		$html .= '<li class="end">'.$list['end']['data'].'</li>';
	}
	$html .= '<li class="bookends">&raquo;</li>';
	$html .= "</ul>";
	return $html;
}

function pagination_item_active($item) {
	return "<a href=\"".$item->link."\" title=\"".$item->text."\">".$item->text."</a>";
}

function pagination_item_inactive($item) {
	return "<span>".$item->text."</span>";
}
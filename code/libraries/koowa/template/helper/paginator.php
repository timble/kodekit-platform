<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2010 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Template Select Helper
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @category	Koowa
 * @package		Koowa_Template
 * @subpackage	Helper
 */
class KTemplateHelperPaginator extends KTemplateHelperSelect
{
	/**
	 * Render item pagination
	 * 
	 * @param 	array 	An optional array with configuration options
	 * @return	string	Html
	 * @see  	http://developer.yahoo.com/ypatterns/navigation/pagination/
	 */
	public function pagination($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'total'   => 0,
			'display' => 4,
			'offset'  => 0,
			'limit'	  => 0,
			'attribs' => array('attribs' => array('onchange' => 'this.form.submit();'))
		));
		
		$html = '';
		$html .= '<style src="media://lib_koowa/css/koowa.css" />';
	
		// Paginator object
		$paginator = KFactory::tmp('lib.koowa.model.paginator')->setData(
				array('total'  => $config->total,
					  'offset' => $config->offset,
					  'limit'  => $config->limit,
					  'display' => $config->display)
		);

		// Get the paginator data
		$list = $paginator->getList();

		$html .= '<div class="-koowa-pagination">';
		$html .= '<div class="limit">'.JText::_('Display NUM').' '.$this->limit($config).'</div>';
		$html .=  $this->_pages($list);
		$html .= '<div class="count"> '.JText::_('Page').' '.$paginator->current.' '.JText::_('of').' '.$paginator->count.'</div>';
		$html .= '</div>';

		return $html;
	}
	
	/**
	 * Render a select box with limit values
	 *
	 * @param 	array 	An optional array with configuration options
	 * @return 	string	Html select box
	 */
	public function limit($config = array())
	{
		$config = new KConfig($config);
		$config->append(array(
			'limit'	  	=> 0,
			'attribs'	=> array(),
		));
		
		$html = '';
		
		$selected = '';
		foreach(array(10 => 10, 20 => 20, 50 => 50, 100 => 100, 0 => 'all' ) as $value => $text)
		{
			if($value == $config->limit) {
				$selected = $value;
			}

			$options[] = $this->option(array('text' => $text, 'value' => $value));
		}

		$html .= $this->optionlist(array('options' => $options, 'name' => 'limit', 'attribs' => $config->attribs, 'selected' => $selected));
		return $html;
	}

	/**
	 * Render a list of pages links
	 *
	 * @param	araay 	An array of page data
	 * @return	string	Html
	 */
	protected function _pages($pages)
	{
		$html = '<ul class="pages">';

		$html .= '<li class="first">&laquo; '.$this->_link($pages['first'], 'First').'</li>';
		$html .= '<li class="previous">&lt; '.$this->_link($pages['previous'], 'Prev').'</li>';

		foreach($pages['pages'] as $page) {
			$html .= '<li>'.$this->_link($page, $page->page).'</li>';
		}

		$html .= '<li class="next">'.$this->_link($pages['next'], 'Next').' &gt;</li>';
		$html .= '<li class="previous">'.$this->_link($pages['last'], 'Last').' &raquo;</li>';

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
	protected function _link($page, $title)
	{
		$url   = clone KRequest::url();
		$query = $url->getQuery(true);

		$query['limit']  = $page->limit;
		$query['offset'] = $page->offset;
		
		$url->setQuery($query);

		$class = $page->current ? 'class="active"' : '';

		if($page->active && !$page->current) {
			$html = '<a href="'.JRoute::_('index.php?'.$url->getQuery()).'" '.$class.'>'.JText::_($title).'</a>';
		} else {
			$html = '<span '.$class.'>'.JText::_($title).'</span>';
		}

		return $html;
	}
}
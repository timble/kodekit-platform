<?php
/**
 * @version		$Id$
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Template Paginator Helper
 *
 * @author		Johan Janssens <johan@nooku.org>
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
	    $config = new KConfigPaginator($config);
		$config->append(array(
		    'total'      => 0,
            'display'    => 4,
            'offset'     => 0,
            'limit'      => 0,
		    'attribs'	 => array(),
		    'show_limit' => true,
		    'show_count' => true
		));
	
		$html = '';
		$html .= '<style src="media://lib_koowa/css/koowa.css" />';

		$html .= '<div class="-koowa-pagination pagination">';
		if($config->show_limit) {
		    $html .= '<div class="limit">'.JText::_('Display NUM').' '.$this->limit($config).'</div>';
		}
		$html .=  $this->pages($config);
		if($config->show_count) {
		    $html .= '<div class="count"> '.JText::_('Page').' '.$config->current.' '.JText::_('of').' '.$config->count.'</div>';
		}
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
		foreach(array(10 => 10, 20 => 20, 50 => 50, 100 => 100) as $value => $text)
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
	 * @param   array   An optional array with configuration options
	 * @return	string	Html
	 */
	public function pages($config = array())
	{
	    $config = new KConfigPaginator($config);
		$config->append(array(
			'total'      => 0,
			'display'    => 4,
			'offset'     => 0,
			'limit'	     => 0,
			'attribs'	=> array(),
		));

		$html .= $this->link($config->pages->first);
		$html .= $this->link($config->pages->prev);

		foreach($config->pages->offsets as $offset) {
			$html .= $this->link($offset);
		}

		$html .= $this->link($config->pages->next);
		$html .= $this->link($config->pages->last);

		return $html;
	}

	/**
	 * Render a page link
	 *
	 * @param	object The page data
	 * @param	string The link title
	 * @return	string	Html
	 */
    public function link($config)
    {
        $config = new KConfig($config);
		$config->append(array(
			'title'   => '',
			'current' => false,
		    'active'  => false,
			'offset'  => 0,
			'limit'	  => 0,
		    'rel'	  => '',
			'attribs'  => array(),
		));
		
        $route = $this->getTemplate()->getView()->getRoute('limit='.$config->limit.'&offset='.$config->offset);
        $rel   = !empty($config->rel) ? 'rel="'.$config->rel.'"' : ''; 
        
        if(!$config->active && $config->current) {
            $html = '<a class="btn active" href="#">'.JText::_($config->title).'</a>';
        } elseif (!$config->active && !$config->current) {
            $html = '<a class="btn disabled" href="#">'.JText::_($config->title).'</a>';
        } else {
            $html = '<a class="btn" href="'.$route.'" '.$rel.'>'.JText::_($config->title).'</a>';
        }

        return $html;
    }
}

<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Paginator Template Helper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Template
 */
class TemplateHelperPaginator extends TemplateHelperSelect
{
	/**
	 * Render item pagination
	 * 
	 * @param 	array 	$config An optional array with configuration options
	 * @return	string	Html
	 * @see  	http://developer.yahoo.com/ypatterns/navigation/pagination/
	 */
    public function pagination($config = array())
    {
        $config = new ModelPaginator($config);
        $config->append(array(
            'total'      => 0,
            'display'    => 4,
            'offset'     => 0,
            'limit'      => 0,
            'attribs'	 => array(),
            'show_limit' => true,
            'show_count' => true,
            'page_rows'  => array(10, 20, 50, 100)
        ));

        // Do not show pagination when $config->limit is lower then $config->total
        if($config->total > $config->limit)
        {
            $html = '';
            $html .= '<style src="assets://css/koowa.css" />';

            $html .= '<div class="pagination">';
            if($config->show_limit) {
                $html .= '<div class="pagination__limit">'.$this->translate('Display NUM').' '.$this->limit($config).'</div>';
            }
            $html .=  $this->pages($config);
            if($config->show_count) {
                $html .= '<div class="pagination__count"> '.$this->translate('Page').' '.$config->current.' '.$this->translate('of').' '.$config->count.'</div>';
            }
            $html .= '</div>';

            return $html;
        }

        return false;
    }
	
	/**
	 * Render a select box with limit values
	 *
	 * @param 	array 	$config An optional array with configuration options
	 * @return 	string	Html select box
	 */
	public function limit($config = array())
	{
		$config = new ObjectConfig($config);
		$config->append(array(
			'limit'	  	=> 0,
            'page_rows' => array(10, 20, 50, 100),
			'attribs'	=> array(),
		));
		
		$html = '';
		
		$selected = '';
		foreach($config->page_rows as $limit)
		{
			if($limit == $config->limit) {
				$selected = $limit;
			}

			$options[] = $this->option(array('text' => $limit, 'value' => $limit));
		}

		$html .= $this->optionlist(array('options' => $options, 'name' => 'limit', 'attribs' => $config->attribs, 'selected' => $selected));
		return $html;
	}

	/**
	 * Render a list of pages links
	 *
	 * @param 	array 	$config An optional array with configuration options
	 * @return	string	Html
	 */
	public function pages($config = array())
	{
	    $config = new ModelPaginator($config);
		$config->append(array(
			'total'      => 0,
			'display'    => 4,
			'offset'     => 0,
			'limit'	     => 0,
			'attribs'	=> array(),
		));

        $html = '<ul>';

		if($config->offset) {
            $html .= $this->link($config->pages->prev);
        }
		foreach($config->pages->offsets as $offset) {
			$html .= $this->link($offset);
		}

        if($config->total > ($config->offset + $config->limit)) {
		    $html .= $this->link($config->pages->next);
        }

        $html .= '</ul>';

		return $html;
	}

	/**
	 * Render a page link
	 *
	 * @param 	array 	$config An optional array with configuration options
	 * @return	string	Html
	 */
    public function link($config)
    {
        $config = new ObjectConfig($config);
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

        $html = '<li '.$this->buildAttributes($config->attribs).'><a href="'.$route.'" '.$rel.'>'.$this->translate($config->title).'</a></li>';

        return $html;
    }
}

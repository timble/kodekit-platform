<?php
/**
 * @version     $Id$
 * @package     Nooku_Components
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

class ComFilesTemplateHelperPaginator extends ComDefaultTemplateHelperPaginator
{
    /**
     * Render item pagination
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     * @see     http://developer.yahoo.com/ypatterns/navigation/pagination/
     */
    public function pagination($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'limit'   => 0,
        ));

        $html .= '<div class="pagination">';
        $html .= '<div class="limit">'.JText::_('Display NUM').' '.$this->limit($config).'</div>';
        $html .=  $this->pages($config);
        $html .= '<div class="limit"> '.JText::_('Page').' <span class="page-current">1</span>';
        $html .= ' '.JText::_('of').' <span class="page-total">1</span></div>';
        $html .= '</div>';

        return $html;
    }

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
		
		$html   = '<div class="btn-group">'.$this->link($config->pages->first).'</div>';
		$html  .= '<div class="btn-group">';
		$html  .= $this->link($config->pages->prev);
		$html  .= '</div>';
		$html  .= '<div class="btn-group pagelist"></div>';
		$html  .= '<div class="btn-group">';
		$html  .= $this->link($config->pages->next);
		$html  .= '</div>';
		$html  .= '<div class="btn-group">'.$this->link($config->pages->last).'</div>';
		
		return $html;
    }
    
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
    
        $html = '<a class="btn '.$config->rel.'" href="#">'.JText::_($config->title).'</a>';
    
        return $html;
    }    
}
<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Paginator Template Helper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Application
 */
class ApplicationTemplateHelperPaginator extends Library\TemplateHelperPaginator
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
        $config = new Library\ModelPaginator($config);
        $config->append(array(
            'total'      => 0,
            'display'    => 4,
            'offset'     => 0,
            'limit'      => 0,
            'show_limit' => true,
		    'show_count' => true
        ));

        $html  = '<div class="pagination">';
        if($config->show_limit) {
            $html .= '<div class="limit">'.$this->translate('Display NUM').' '.$this->limit($config).'</div>';
        }
        $html .=  $this->pages($config);
        if($config->show_count) {
            $html .= '<div class="limit"> '.$this->translate('Page').' '.$config->current.' '.$this->translate('of').' '.$config->count.'</div>';
        }
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Render a list of pages links
     * 
     * This function is overriddes the default behavior to render the links in the khepri template
     * backend style.
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function pages($config = array())
    {
        $config = new Library\ModelPaginator($config);
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

        foreach($config->pages->offsets as $offset) {
            $html .= $this->link($offset);
        }

        $html  .= $this->link($config->pages->next);
        $html  .= '</div>';
        $html  .= '<div class="btn-group">'.$this->link($config->pages->last).'</div>';

        return $html;
    }

    /**
     * Render a page link
     *
     * @param   array   An optional array with configuration options
     * @return	string	Html
     */
   public function link($config)
   {
        $config = new Library\ObjectConfig($config);
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

        $html = '<a '.$this->buildAttributes($config->attribs).' href="'.$route.'" '.$rel.'>'.$this->translate($config->title).'</a>';


       return $html;
   }
}
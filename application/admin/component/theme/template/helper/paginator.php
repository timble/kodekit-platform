<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Theme;

use Kodekit\Library;

/**
 * Paginator Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Theme
 */
class TemplateHelperPaginator extends Library\TemplateHelperPaginator
{
    /**
     * Render item pagination
     *
     * @param   array   $config An optional array with configuration options
     * @return  string  Html
     * @see     http://developer.yahoo.com/ypatterns/navigation/pagination/
     */
    public function pagination($config = array(), Library\TemplateInterface $template)
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

        $translator = $this->getObject('translator');

        $html  = '<div class="pagination">';
        if($config->show_limit) {
            $html .= '<div class="limit">'.$translator('Display NUM').' '.$this->limit($config).'</div>';
        }
        $html .=  $this->pages($config, $template);
        if($config->show_count) {
            $html .= '<div class="limit"> '.$translator('Page').' '.$config->current.' '.$translator('of').' '.$config->count.'</div>';
        }
        $html .= '</div>';

        return $html;
    }

    /**
     * Render a list of pages links
     *
     * @param   array   $config An optional array with configuration options
     * @return  string  Html
     */
    public function pages($config = array(), Library\TemplateInterface $template)
    {
        $config = new Library\ModelPaginator($config);
        $config->append(array(
            'total'      => 0,
            'display'    => 4,
            'offset'     => 0,
            'limit'	     => 0,
            'attribs'	=> array(),
        ));

        $html   = '<div class="button__group">'.$this->link($config->pages->first, $template).'</div>';
        $html  .= '<div class="button__group">';
        $html  .= $this->link($config->pages->prev, $template);

        foreach($config->pages->offsets as $offset) {
            $html .= $this->link($offset, $template);
        }

        $html  .= $this->link($config->pages->next, $template);
        $html  .= '</div>';
        $html  .= '<div class="button__group">'.$this->link($config->pages->last, $template).'</div>';

        return $html;
    }

    /**
     * Render a page link
     *
     * @param   array   $config An optional array with configuration options
     * @return	string	Html
     */
   public function link($config, Library\TemplateInterface $template)
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

        $translator = $this->getObject('translator');

        $route = $template->route('limit='.$config->limit.'&offset='.$config->offset);
        $rel   = !empty($config->rel) ? 'rel="'.$config->rel.'"' : '';

        $html = '<a '.$this->buildAttributes($config->attribs).' href="'.$route.'" '.$rel.'>'.$translator($config->title).'</a>';


       return $html;
   }
}
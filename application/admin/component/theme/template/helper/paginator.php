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
    public function pagination($config = array())
    {
        $config = new Library\ModelPaginator($config);
        $config->append(array(
            'url'        => null,
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
        $html .=  $this->pages($config);
        if($config->show_count) {
            $html .= '<div class="limit"> '.$translator('Page').' '.$config->current.' '.$translator('of').' '.$config->count.'</div>';
        }
        $html .= '</div>';

        return $html;
    }

    public function pages($config = array())
    {
        $config = new Library\ModelPaginator($config);
        $config->append(array(
            'url'      => null,
            'total'   => 0,
            'display' => 4,
            'offset'  => 0,
            'limit'   => 0,
            'attribs' => array(),
        ));

        $html   = '<div class="button__group">'.$this->page($config->pages->first, $config->url).'</div>';
        $html  .= '<div class="button__group">';
        $html  .= $this->page($config->pages->prev, $config->url);

        foreach($config->pages->offsets as $offset) {
            $html .= $this->page($offset, $config->url);
        }

        $html  .= $this->page($config->pages->next, $config->url);
        $html  .= '</div>';
        $html  .= '<div class="button__group">'.$this->page($config->pages->last, $config->url).'</div>';

        return $html;
    }

    public function page(Library\ObjectConfigInterface $page, Library\HttpUrlInterface $url)
    {
        $page->append(array(
            'title'   => '',
            'current' => false,
            'active'  => false,
            'offset'  => 0,
            'limit'   => 0,
            'rel'     => '',
            'attribs' => array(),
        ));

        $translator = $this->getObject('translator');

        //Set the offset and limit
        $url->query['limit']  = $page->limit;
        $url->query['offset'] = $page->offset;

        $rel  = !empty($page->rel) ? 'rel="'.$page->rel.'"' : '';
        $html = '<a '.$this->buildAttributes($page->attribs).' href="'.$url.'" '.$rel.'>'.$translator($page->title).'</a>';

       return $html;
   }
}
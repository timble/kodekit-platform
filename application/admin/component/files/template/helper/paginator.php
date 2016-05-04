<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Files;

use Kodekit\Library;
use Kodekit\Platform\Theme;

/**
 * Paginator Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Files
 */
class TemplateHelperPaginator extends Theme\TemplateHelperPaginator
{
    public function pagination($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'url'   => null,
            'limit' => 0,
        ));

        $translator = $this->getObject('translator');

        $html = '';
        $html .= '<div class="pagination">';
        $html .= '<div class="limit">'.$translator('Display NUM').' '.$this->limit($config).'</div>';
        $html .=  $this->pages($config);
        $html .= '<div class="limit"> '.$translator('Page').' <span class="page-current">1</span>';
        $html .= ' '.$translator('of').' <span class="page-total">1</span></div>';
        $html .= '</div>';

        return $html;
    }

    public function pages($config = array())
    {
        $config = new Library\ModelPaginator($config);
        $config->append(array(
            'url'      => null,
            'total'    => 0,
            'display'  => 4,
            'offset'   => 0,
            'limit'    => 0,
            'attribs'  => array(),
        ));

        $html   = '<div class="button__group">'.$this->page($config->pages->first, $config->url).'</div>';
        $html  .= '<div class="button__group">';
        $html  .= $this->page($config->pages->prev, $config->url);
        $html  .= '</div>';
        $html  .= '<div class="button__group page-list"></div>';
        $html  .= '<div class="button__group">';
        $html  .= $this->page($config->pages->next, $config->url);
        $html  .= '</div>';
        $html  .= '<div class="button__group">'.$this->page($config->pages->last, $config->url).'</div>';

        return $html;
    }
}
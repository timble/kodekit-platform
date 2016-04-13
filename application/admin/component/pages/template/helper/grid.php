<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Pages;

use Kodekit\Library;

/**
 * Grid Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Pages
 */
class TemplateHelperGrid extends Library\TemplateHelperGrid
{
    /**
     * Render an order field
     *
     * @param   array   $config An optional array with configuration options
     * @return  string  Html
     */
    public function order($config = array())
    {
        $config = new Library\ObjectConfigJson($config);
        $config->append(array(
            'entity'   => null,
            'total'	=> null,
            'field'	=> 'ordering',
            'data'	=> array('order' => 0)
        ));

        $config->data->order = -1;
        $updata   = str_replace('"', '&quot;', $config->data);

        $config->data->order = +1;
        $downdata = str_replace('"', '&quot;', $config->data);

        $html = '';

        if ($config->entity->{$config->field} > 1) {
            $html .= '<i class="icon-chevron-up" data-action="edit" data-data="'.$updata.'"></i>';
        }

        $html .= '<span class="data-order">'.$config->entity->{$config->field}.'</span>';

        if($config->entity->{$config->field} != $config->total) {
            $html .= '<i class="icon-chevron-down" data-action="edit" data-data="'.$downdata.'"></i>';
        }

        return $html;
    }
}

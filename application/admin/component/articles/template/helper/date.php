<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Articles;

use Kodekit\Library;

/**
 * Date Template Helper
 *
 * @author  Tom Janssens <http://github.com/tomjanssens>
 * @package Kodekit\Platform\Articles
 */
class TemplateHelperDate extends Library\TemplateHelperDate
{
    /**
     * Render a HTML5 date type field
     *
     * @param 	array 	$config An optional array with configuration options
     * @return	string	Html
     */
    public function datetime($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'name'   => 'date',
            'type'   => 'datetime-local'
        ));

        $value = null;
        if($value = $config->entity->{$config->name}) {
            switch($config->type) {
                case 'date':
                    $value = gmdate('Y-m-d', strtotime($value));
                    break;
                case 'datetime':
                case 'datetime-local':
                    $value = gmdate('Y-m-d\TH:i:s', strtotime($value));
                    break;
            }
        }

        $html = '<input type="'.$config->type.'" name="'.$config->name.'" value="'.$value.'" />';

        return $html;
    }
}
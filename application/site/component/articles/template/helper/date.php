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
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Kodekit\Platform\Articles
 */
class TemplateHelperDate extends Library\TemplateHelperDate
{
    public function timestamp($config = array())
    {
        $config = new Library\ObjectConfig($config);

        $params     = $this->getObject('pages')->getActive()->getParams('page');
        $translator = $this->getObject('translator');

        $config->append(array('params' => $params))
               ->append(array(
                    'show_create_date' => $config->params->get('show_create_date', false),
                    'show_modify_date' => $config->params->get('show_modify_date', false)
                ));

        $article = $config->entity;

        $html = array();

        if ($config->show_create_date)
        {
            $html[] = '<span class="timestamp">';
            $html[] = $this->format(array('date'=> $article->ordering_date, 'format' => $translator('Timestamp Date Format')));
        }

        if ($config->get('show_modify_date') && $config->show_create_date && ($modified_on = $article->modified_on) && (intval($modified_on) != 0))
        {
            $html[] = $translator('Last Updated on {date}', array(
                'date' => $this->format(array(
                    'date'   => $article->modified_on,
                    'format' => $translator('Timestamp Date Format')
                    ))
                )
            );
        }

        if ($config->show_create_date) {
            $html[] = '</span>';
        }

        return implode(' ', $html);
    }
}
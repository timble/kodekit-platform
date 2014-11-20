<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Date Template Helper
 *
 * @author  Arunas Mazeika <http://github.com/amazeika>
 * @package Component\Articles
 */
class ArticlesTemplateHelperDate extends Library\TemplateHelperDate
{
    public function timestamp($config = array())
    {
        $config = new Library\ObjectConfig($config);

        $params     = $this->getObject('application.pages')->getActive()->getParams('page');
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
<?php
/**
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

use Nooku\Library;

/**
 * Article Template Helper Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ArticlesTemplateHelperDate extends Library\TemplateHelperDate
{
    public function timestamp($config = array())
    {
        $config = new Library\ObjectConfig($config);

        $config->append(array('parameters' => $this->getObject('application.components')->articles->params))
               ->append(array(
                    'show_create_date' => $config->parameters->get('show_create_date'),
                    'show_modify_date' => $config->parameters->get('show_modify_date')
                ));

        $article = $config->row;

        $html = array();

        if ($config->show_create_date) {
            $html[] = '<span class="timestamp">';
            $html[] = $this->format(array('date'=> $article->ordering_date, 'format' => JText::_('DATE_FORMAT_LC5')));
        }

        if ($config->get('show_modify_date') && $config->show_create_date && ($modified_on = $article->modified_on) && (intval($modified_on) != 0))
        {
            $html[] = JText::sprintf('LAST_UPDATED2',
                $this->format(array('date' => $article->modified_on, 'format' => JText::_('DATE_FORMAT_LC5'))));
        }
        
        if ($config->show_create_date) {
            $html[] = '</span>';
        }

        return implode(' ', $html);
    }
}
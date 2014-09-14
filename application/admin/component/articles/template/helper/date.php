<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;

/**
 * Date Template Helper
 *
 * @author  Tom Janssens <http://github.com/tomjanssens>
 * @package Component\Articles
 */
class ArticlesTemplateHelperDate extends Library\TemplateHelperDate
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
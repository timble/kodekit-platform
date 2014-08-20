<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Languages;

/**
 * Grid Template Helper
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Component\Languages
 */
class LanguagesTemplateHelperGrid extends Library\TemplateHelperGrid
{
    public function status($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'status'   => '',
            'original' => 0,
            'deleted'  => 0
        ));

        $translator = $this->getObject('translator');
        $statuses   = array(
            Languages\ModelEntityTranslation::STATUS_COMPLETED => 'Completed',
            Languages\ModelEntityTranslation::STATUS_MISSING   => 'Missing',
            Languages\ModelEntityTranslation::STATUS_OUTDATED  => 'Outdated'
        );
        
        $text  = $config->original ? 'Original' : $statuses[$config->status];
        $class = $config->original ? 'original' : strtolower($statuses[$config->status]);
        $class = $config->deleted  ? 'deleted'  : $class;
    
        return '<span class="label label-'.$class.'">'.$translator($text).'</span>';
    }
}
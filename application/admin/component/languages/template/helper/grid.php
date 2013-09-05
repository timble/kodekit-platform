<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Languages;

/**
 * Grid Template Helper
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
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
    
        $statuses = array(
            DatabaseRowTranslation::STATUS_COMPLETED => 'Completed',
            DatabaseRowTranslation::STATUS_MISSING   => 'Missing',
            DatabaseRowTranslation::STATUS_OUTDATED  => 'Outdated'
        );
        
        $text  = $config->original ? 'Original' : $statuses[$config->status];
        $class = $config->original ? 'original' : strtolower($statuses[$config->status]);
        $class = $config->deleted  ? 'deleted'  : $class;
    
        return '<span class="label label-'.$class.'">'.$this->translate($text).'</span>';
    }
}
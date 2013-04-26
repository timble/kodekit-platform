<?php
/**
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;
use Nooku\Component\Languages;

/**
 * Languages Template Helper Grid Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Languages
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
    
        return '<span class="label label-'.$class.'">'.JText::_($text).'</span>';
    }
}
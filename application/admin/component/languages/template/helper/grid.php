<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Languages;

use Kodekit\Library;
use Kodekit\Component\Languages;

/**
 * Grid Template Helper
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Platform\Languages
 */
class TemplateHelperGrid extends Library\TemplateHelperGrid
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
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
 * Modules Html View
 *
 * @author  Stian Didriksen <http://github.com/stipsan>
 * @package Kodekit\Platform\Pages
 */
class ViewModulesHtml extends Library\ViewHtml
{
    protected function _actionRender(Library\ViewContext $context)
    {
        $context->data->positions = $this->getModel()->fetch()->getPositions();

        //Load language files for each module
        if($context->getLayout() == 'list')
        {
            foreach($this->getModel()->fetch() as $module)
            {
                $package = $module->getIdentifier()->package;
                $domain  = $module->getIdentifier()->domain;

                if($domain) {
                    $url = 'com://'.$domain.'/'.$package;
                } else {
                    $url = 'com:'.$package;
                }

                $this->getObject('translator')->load($url);
            }
        }

        return parent::_actionRender($context);
    }
}
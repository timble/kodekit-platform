<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-application for the canonical source repository
 */

namespace Kodekit\Component\Application;

use Kodekit\Library;

/**
 * Listbox Template Helper
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Component\Application
 */
class TemplateHelperListbox extends Library\TemplateHelperListbox
{
    public function applications($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'name'     => 'application',
            'deselect' => true,
            'prompt'   => '- Select -',
        ));

        $options = array();
        if($config->deselect) {
            $options[] = $this->option(array('label' => $this->getObject('translator')->translate($config->prompt)));
        }

        $applications = array('admin', 'site');
        foreach($applications as $name) {
            $options[] = $this->option(array('label' => $name, 'value' => $name));
        }

        $config->options = $options;

        return $this->optionlist($config);
    }

    public function sites( $config = array() )
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'model'    => 'sites',
            'name'     => 'site',
            'value'    => 'name',
            'label'    => 'name',
            'deselect' => false
        ));

        return parent::_listbox($config);
    }
}
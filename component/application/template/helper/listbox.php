<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Application;

use Nooku\Library;

/**
 * Listbox Template Helper
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Nooku\Component\Application
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

        $applications = $this->getObject('object.bootstrapper')->getApplications();
        foreach($applications as $name) {
            $options[] = $this->option(array('label' => $name, 'value' => $name));
        }
        
        $config->options = $options;
        
        return $this->optionlist($config);
    }
}
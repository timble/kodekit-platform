<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
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

        $basepaths = $this->getObject('manager')->getClassLoader()->getBasepaths();
        foreach($basepaths as $application => $path) {
            $options[] = $this->option(array('label' => $application, 'value' => $application));
        }
        
        $config->options = $options;
        
        return $this->optionlist($config);
    }
}
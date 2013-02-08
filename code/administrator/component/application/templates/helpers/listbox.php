<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Listbox Template Helper Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationTemplateHelperListbox extends ComDefaultTemplateHelperListbox
{
    public function applications($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
            'name'     => 'application',
            'deselect' => true,
            'prompt'   => '- Select -',
        ));
        
        $options = array();
        if($config->deselect) {
            $options[] = $this->option(array('text' => JText::_($config->prompt)));
        }
        
        foreach($this->getIdentifier()->getApplications() as $application => $path) {
            $options[] = $this->option(array('text' => $application, 'value' => $application));
        }
        
        $config->options = $options;
        
        return $this->optionlist($config);
    }
}
<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Template Toolbar Helper
 *
 * @author    	Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationTemplateHelperToolbar extends KTemplateHelperAbstract
{
    /**
     * Render the toolbar
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function render($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
        	'toolbar' => null,
            'attribs' => array()
        ));

        $html  = '<ul '.$this->_buildAttributes($config->attribs).'>';
	    foreach ($config->toolbar->getCommands() as $command)
	    {
            $name = $command->getName();

            $html .= '<li>';
	        if(method_exists($this, $name)) {
                $html .= $this->$name(array('command' => $command));
            } else {
                $html .= $this->command(array('command' => $command));
            }
            $html .= '</li>';
       	}
		$html .= '</ul>';

		return $html;
    }

    /**
     * Render a toolbar command
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function command($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
        	'command' => array()
        ));

        $command = $config->command;

        //Create the id
        $command->attribs['id'] = 'command-'.$command->id;

        //Add a disabled class if the command is disabled
        if($command->disabled) {
            $command->attribs->class->append(array('disabled'));
        }

        //Create the href
        if(!empty($command->href)) {
            $command->attribs['href'] = $this->getTemplate()->getView()->getRoute($command->href);
        }

        $html  = '<a '.$this->_buildAttributes($command->attribs).'>';
       	$html .= JText::_($command->label);
       	$html .= '</a>';

    	return $html;
    }
}
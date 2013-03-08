<?php
/**
 * @package     Nooku_Components
 * @subpackage  Default
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Template Toolbar Helper
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Components
 * @subpackage  Default
 */
class ComDefaultTemplateHelperToolbar extends Framework\TemplateHelperAbstract
{
    /**
     * Render the toolbar
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function render($config = array())
    {
        $config = new Framework\Config($config);
        $config->append(array(
            'toolbar' => null,
            'attribs' => array('class' => array('toolbar'))
        ));

        //Force the id
        $config->attribs['id'] = 'toolbar-'.$config->toolbar->getName();

        $html  = '<div '.$this->_buildAttributes($config->attribs).'>';
        $html .= '<div class="btn-group">';
	    foreach ($config->toolbar->getCommands() as $command)
	    {
            $name = $command->getName();

	        if(method_exists($this, $name)) {
                $html .= $this->$name(array('command' => $command));
            } else {
                $html .= $this->command(array('command' => $command));
            }
       	}
        $html .= '</div>';
		$html .= '</div>';

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
        $config = new Framework\Config($config);
        $config->append(array(
        	'command' => array('attribs' => array('class' => array('btn', 'toolbar')))
        ));

        $command = $config->command;

        //Force the id
        $command->attribs['id'] = 'command-'.$command->id;

        //Add a disabled class if the command is disabled
        if($command->disabled) {
            $command->attribs->class->append(array('nolink'));
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

	/**
     * Render a separator
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function separator($config = array())
    {
        $config = new Framework\Config($config);
        $config->append(array(
            'command' => array('attribs' => array('class' => array('btn-group')))
        ));

        $command = $config->command;

        $html = '</div><div '.$this->_buildAttributes($command->attribs).'>';

    	return $html;
    }

	/**
     * Render a dialog button
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function dialog($config = array())
    {
        $config = new Framework\Config($config);
        $config->append(array(
        	'command' => NULL
        ));

        $html  = $this->getTemplate()->renderHelper('behavior.modal');
        $html .= $this->command($config);

    	return $html;
    }
}
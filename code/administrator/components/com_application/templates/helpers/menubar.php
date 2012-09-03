<?php
/**
 * @version     $Id: menubar.php 4813 2012-08-21 02:25:31Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Template Menubar Helper
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationTemplateHelperMenubar extends KTemplateHelperAbstract
{
 	/**
     * Render the menubar
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function render($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
        	'menubar' => null,
            'attribs' => array('class' => array())
        ));

        if($this->getService('component')->getController()->getView()->getLayout() == 'form') {
            $config->attribs->class->append(array('disabled'));
        }

        $html = '<ul '.$this->_buildAttributes($config->attribs).'>';
	    foreach ($config->menubar as $command)
	    {
            $name = $command->getName();

            if(method_exists($this, $name)) {
                $html .= $this->$name(array('command' => $command));
            } else {
                $html .= $this->command(array('command' => $command));
            }
        }
        $html .= '</ul>';

		return $html;
    }

    /**
     * Render a menubar command
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function command($config = array())
    {
        $config = new KConfig($config);
        $config->append(array(
        	'command' => null
        ));

        $command = $config->command;

        if($this->getService('component')->getController()->getView()->getLayout() == 'form') {
            $command->disabled = true;
        }

        //Add a nolink class if the command is disabled
        if($command->disabled) {
            $command->attribs->class->append(array('nolink'));
        }

        if($command->active) {
             $command->attribs->class->append(array('active'));
        }

        //Create the href
        if(!empty($command->href) && !$command->disabled) {
            $command->attribs['href'] = $this->getTemplate()->getView()->getRoute($command->href);
        }

        $html = count($command) ? '<li class="node">' : '<li>';
        if ($command->disabled) {
			$html .= '<span '.$this->_buildAttributes($command->attribs).'>'.JText::_($command->label).'</span>';
		} else {
			$html .= '<a '.$this->_buildAttributes($command->attribs).'>'.JText::_($command->label).'</a>';
		}

        if(count($command)) {
            $html .= $this->render(array('menubar' => $command));
        }
        $html .= '</li>';

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
        $config = new KConfig($config);
        $config->append(array(
            'command' => NULL
        ));

        $command = $config->command;

        $html = '<li class="separator"><span></span></li>';

        return $html;
    }
}
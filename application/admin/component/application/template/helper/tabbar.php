<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
use Nooku\Library;

/**
 * Tabbar Template Helper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Application
 */
class ApplicationTemplateHelperTabbar extends Library\TemplateHelperAbstract
{
 	/**
     * Render the menubar
     *
     * @param   array   An optional array with configuration options
     * @return  string  Html
     */
    public function render($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
        	'toolbar' => null,
            'attribs' => array(),
        ));

        $html = '';
        if(isset($config->toolbar))
        {
            $commands = $config->toolbar->getCommands();

            if(count($commands)) {
                $html = '<div id="panel-tabbar">';
                foreach ($commands as $command)
                {
                    $name = $command->getName();

                    if(method_exists($this, $name)) {
                        $html .= $this->$name(array('command' => $command));
                    } else {
                        $html .= $this->command(array('command' => $command));
                    }
                }
                $html .= '</div>';
            }
        }

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
        $config = new Library\ObjectConfig($config);
        $config->append(array(
        	'command' => null
        ));

        $command = $config->command;

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

        if ($command->disabled) {
			$html = '<span '.$this->buildAttributes($command->attribs).'>'.$this->translate($command->label).'</span>';
		} else {
			$html = '<a '.$this->buildAttributes($command->attribs).'>'.$this->translate($command->label).'</a>';
		}

    	return $html;
    }
}
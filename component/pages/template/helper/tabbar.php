<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-pages for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Tabbar Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Application
 */
class TemplateHelperTabbar extends Library\TemplateHelperAbstract
{
 	/**
     * Render the tabbar
     *
     * @param   array   $config An optional array with configuration options
     * @return  string  Html
     */
    public function render($config = array(), Library\TemplateInterface $template)
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
        	'toolbar'     => null,
            'attribs'     => array(),
            'show_hidden' => true,
        ));

        $html = '';
        if(isset($config->toolbar))
        {
            foreach ($config->toolbar as $command)
            {
                //Do not show hidden commands
                if($command->hidden && !$config->show_hidden) {
                    continue;
                }

                $name = $command->getName();

                if(method_exists($this, $name)) {
                    $html .= $this->$name(array('command' => $command), $template);
                } else {
                    $html .= $this->command(array('command' => $command), $template);
                }
            }
        }

		return $html;
    }

    /**
     * Render a tabbar command
     *
     * @param   array   $config An optional array with configuration options
     * @return  string  Html
     */
    public function command($config = array(), Library\TemplateInterface $template)
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
        	'command' => null
        ));

        $command    = $config->command;
        $translator = $this->getObject('translator');

        //Add a nolink class if the command is disabled
        if($command->disabled) {
            $command->attribs->class->append(array('nolink'));
        }

        if($command->active) {
             $command->attribs->class->append(array('active'));
        }

        //Create the href
        if($command->href instanceof Library\HttpUrl && !$command->disabled) {
            $command->attribs->href = (string) $template->route($command->href->getQuery());
        }

        if ($command->disabled || empty($command->href)) {
			$html = '<span '.$this->buildAttributes($command->attribs).'>'.$translator($command->label).'</span>';
		} else {
			$html = '<a '.$this->buildAttributes($command->attribs).'>'.$translator($command->label).'</a>';
		}

    	return $html;
    }
}
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
 * Statusbar Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Application
 */
class TemplateHelperStatusbar extends Library\TemplateHelperAbstract
{
    /**
     * Render the toolbar
     *
     * @param   array   $config An optional array with configuration options
     * @return  string  Html
     */
    public function render($config = array(), Library\TemplateInterface $template)
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'toolbar' => null,
            'attribs' => array()
        ));

        $html  = '<ul '.$this->buildAttributes($config->attribs).'>';
        foreach ($config->toolbar as $command)
        {
            $name = $command->getName();

            $html .= '<li>';
            if(method_exists($this, $name)) {
                $html .= $this->$name(array('command' => $command), $template);
            } else {
                $html .= $this->command(array('command' => $command), $template);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';

        return $html;
    }

    /**
     * Render a actionbar command
     *
     * @param   array   $config An optional array with configuration options
     * @return  string  Html
     */
    public function command($config = array(), Library\TemplateInterface $template)
    {
        $config = new Library\ObjectConfig($config);
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
            $command->attribs['href'] = $template->route($command->href);
        }

        $html  = '<a '.$this->buildAttributes($command->attribs).'>';
       	$html .= $this->getObject('translator')->translate($command->label);
       	$html .= '</a>';

    	return $html;
    }
}
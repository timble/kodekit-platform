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
 * Menubar Template Helper
 *
 * @author   Tom Janssens <http://github.com/tomjanssens>
 * @package  Kodekit\Component\Pages
 */
class TemplateHelperMenubar extends Library\TemplateHelperAbstract
{
    public function render($config = array(), Library\TemplateInterface $template)
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
            'toolbar'     => null,
            'attribs'     => array('class' => array('nav')),
            'max_level'   => 9,
            'show_hidden' => false,
        ));

        $html     = '';
        $level    = 0;
        $iterator = new Library\ControllerToolbarIteratorRecursive($config->toolbar, $config->max_level);

        foreach ($iterator as $command)
        {
            //Do not show hidden commands
            if($command->hidden && !$config->show_hidden) {
                continue;
            }

            if($iterator->getLevel() > $level)
            {
                $attributes = $iterator->getLevel() == 1 ? ' '.$this->buildAttributes($config->attribs) : '';
                $html .= "<ul$attributes>";

                // Add the title to the menu
                if($iterator->getLevel() == 1 && $config->toolbar->getTitle()) {
                    $html .= '<li class="nav-header">'.$config->toolbar->getTitle()."</li>";
                }
            }

            //Add a 'nolink' class if the command is disabled
            if($command->disabled) {
                $command->attribs->class->append(array('nolink'));
            }

            //Add a 'current' class if the commnad is active
            if($command->active) {
                $command->attribs->class->append(array('current'));
            }

            //Add a 'active' class if the command is in the active tree
            if(in_array($command->id, (array) $command->path)) {
                $command->attribs->class->append(array('active'));
            }

            //Add a 'parent' class if the command has sub commands
            if(count($command)) {
                $command->attribs->class->append(array('parent'));
            }

            $html .= '<li '.$this->buildAttributes($command->attribs).'>';

            //Render the menubar command
            $name = $command->getName();

            if(method_exists($this, $name)) {
                $html .= $this->$name(array('command' => $command), $template);
            } else {
                $html .= $this->command(array('command' => $command), $template);
            }

            if(!count($command))
            {
                $html .= "</li>";

                if(!$iterator->hasNext()) {
                    $html .= "</ul>";
                }
            }

            $level = $iterator->getLevel();
        }

        return $html;
    }

    /**
     * Render a menubar command
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

        //Create the href
        if($command->href instanceof Library\HttpUrl && !$command->disabled) {
            $command->href = (string) $template->route($command->href->getQuery());
        }

        if ($command->disabled || empty($command->href)) {
            $html = '<span class="separator '.($config->disabled ? 'nolink' : '').'">'.$translator($command->label).'</span>';
        } else {
            $html = '<a href="'.$command->href.'">'.$translator($command->label).'</a>';
        }

        return $html;
    }
}
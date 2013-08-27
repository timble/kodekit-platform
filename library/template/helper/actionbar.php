<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Actionbar Template Helper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Template
 */
class TemplateHelperActionbar extends TemplateHelperAbstract
{
    /**
     * Render the action bar
     *
     * @param 	array 	$config An optional array with configuration options
     * @return  string  Html
     */
    public function render($config = array())
    {
        $config = new ObjectConfig($config);
        $config->append(array(
            'toolbar' => null,
            'attribs' => array('class' => array('toolbar'))
        ));

        $html = '';
        if(isset($config->toolbar))
        {
            //Force the id
            $config->attribs['id'] = 'toolbar-'.$config->toolbar->getType();

            $html  = '<div '.$this->buildAttributes($config->attribs).'>';
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
        }

		return $html;
    }

    /**
     * Render a action bar command
     *
     * @param 	array 	$config An optional array with configuration options
     * @return  string  Html
     */
    public function command($config = array())
    {
        $config = new ObjectConfig($config);
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

        $html  = '<a '.$this->buildAttributes($command->attribs).'>';
       	$html .= $this->translate($command->label);
       	$html .= '</a>';

    	return $html;
    }

	/**
     * Render a separator
     *
     * @param 	array 	$config An optional array with configuration options
     * @return  string  Html
     */
    public function separator($config = array())
    {
        $config = new ObjectConfig($config);
        $config->append(array(
            'command' => array('attribs' => array('class' => array('btn-group')))
        ));

        $command = $config->command;

        $html = '</div><div '.$this->buildAttributes($command->attribs).'>';

    	return $html;
    }

	/**
     * Render a dialog button
     *
     * @param 	array 	$config An optional array with configuration options
     * @return  string  Html
     */
    public function dialog($config = array())
    {
        $config = new ObjectConfig($config);
        $config->append(array(
        	'command' => NULL
        ));

        $html  = $this->getTemplate()->renderHelper('behavior.modal');
        $html .= $this->command($config);

    	return $html;
    }
}
<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Actionbar Template Helper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\Template
 */
class ArticlesTemplateHelperActionbar extends Library\TemplateHelperActionbar
{
    /**
     * Render a action bar command
     *
     * @param 	array 	$config An optional array with configuration options
     * @return  string  Html
     */
    public function edit($config = array())
    {
        $config = new Library\ObjectConfig($config);
        $config->append(array(
        	'command' => array('attribs' => array(
                'class' => array('btn', 'btn-mini', 'toolbar'),
                'style' => array('float: right')
            ))
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
            $command->attribs['href'] = $command->href;
        }

        $html  = '<a '.$this->buildAttributes($command->attribs).'>';
       	$html .= '<i class="icon-edit"></i>';
       	$html .= '</a>';

    	return $html;
    }
}
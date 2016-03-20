<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Platform\Articles;

use Kodekit\Library;

/**
 * Actionbar Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Platform\Articles
 */
class TemplateHelperActionbar extends Library\TemplateHelperActionbar
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
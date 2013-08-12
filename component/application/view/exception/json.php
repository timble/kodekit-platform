<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Application;

use Nooku\Library;

/**
 * Exception Json View
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Application
 */
class ViewExceptionJson extends Library\ViewJson
{
    public function render()
    {
        $properties = array(
            'message' => $this->message,
            'code'    => $this->code
        );

        if(ini_get('display_errors'))
        {
            $properties['data'] = array(
                'file'	   => $this->file,
                'line'     => $this->line,
                'function' => $this->function,
                'class'	   => $this->class,
                'args'	   => $this->args,
                'info'	   => $this->info
            );
        }

        $content = json_encode(array(
            'version'  => '1.0',
            'errors' => array($properties)
        ));

        $this->setContent($content);

        return parent::render();
    }
}
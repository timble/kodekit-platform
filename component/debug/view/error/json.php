<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-deugger for the canonical source repository
 */

namespace Kodekit\Component\Debug;

use Kodekit\Library;

/**
 * Error Json View
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Debug
 */
class ViewErrorJson extends Library\ViewJson
{
    protected function _actionRender(Library\ViewContext $context)
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

        return parent::_actionRender($context);
    }
}
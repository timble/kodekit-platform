<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Error Json View Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Articles
 */

class ComApplicationViewExceptionJson extends KViewJson
{
    public function display()
    {
        if(ini_get('display_errors')) {
            $message = (string) $this->exception;
        } else {
            $message = KHttpResponse::getMessage($this->exception->getCode());
        }

        $properties = array(
            'message' => $message,
            'code'    => $this->exception->getCode()
        );

        if(ini_get('display_errors'))
        {
            $properties['data'] = array(
                'file'	    => $this->exception->getFile(),
                'line'      => $this->exception->getLine(),
                'function'  => $this->exception->getFunction(),
                'class'		=> $this->exception->getClass(),
                'args'		=> $this->error->getArgs(),
                'info'		=> $this->exception->getInfo()
            );
        }

        $this->output = json_encode(array(
            'version'  => '1.0',
            'errors' => array($properties)
        ));

        return parent::display();
    }
}
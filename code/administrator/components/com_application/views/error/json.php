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

class ComApplicationViewErrorJson extends KViewJson
{
    public function display()
    {
        if(KDEBUG) {
            $message = (string) $this->error;
        } else {
            $message = KHttpResponse::getMessage($this->error->getCode());
        }

        $properties = array(
            'message' => $message,
            'code'    => $this->error->getCode()
        );

        if(KDEBUG)
        {
            $properties['data'] = array(
                'file'	    => $this->error->getFile(),
                'line'      => $this->error->getLine(),
                'function'  => $this->error->getFunction(),
                'class'		=> $this->error->getClass(),
                'args'		=> $this->error->getArgs(),
                'info'		=> $this->error->getInfo()
            );
        }

        $this->output = json_encode(array(
            'version'  => '1.0',
            'errors' => array($properties)
        ));

        return parent::display();
    }
}
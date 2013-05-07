<?php
/**
 * @package		Koowa_Controller
 * @subpackage  User
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Controller User Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage  User
 */
class ControllerUser extends User implements ControllerUserInterface
{
    /**
     * Default messages types
     *
     * Note : Messages types are based on the Bootstrap class names for messages
     */
    const FLASH_INFO    = 'info';
    const FLASH_SUCCESS = 'success';
    const FLASH_WARNING = 'warning';
    const FLASH_ERROR   = 'error';

    /**
     * Add a user flash message
     *
     * Flash messages are self-expiring messages that are meant to live for exactly one request. They're designed to be
     * used across redirects.
     *
     * @param  string   $message   The flash message
     * @param  string   $type      Message category type. Default is 'success'.
     * @return ControllerUser
     */
    public function addFlashMessage($message, $type = self::FLASH_SUCCESS)
    {
        if (!is_string($message) && !is_callable(array($message, '__toString')))
        {
            throw new \UnexpectedValueException(
                'The flash message must be a string or object implementing __toString(), "'.gettype($message).'" given.'
            );
        }

        //Auto start the session if it's not active.
        if(!$this->getSession()->isActive()) {
            $this->getSession()->start();
        }

        $this->getSession()->getContainer('message')->add($message, $type);
        return $this;
    }

    /**
     * Get a user attribute
     *
     * Implements a virtual 'session' class property to return the session object.
     *
     * @param   string $name  The attribute name.
     * @return  string $value The attribute value.
     */
    public function __get($name)
    {
        if($name == 'session') {
            return $this->getSession();
        }

        return parent::__get($name);
    }
}
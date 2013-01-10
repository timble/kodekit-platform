<?php
/**
 * @version     $Id: interface.php 1366 2009-11-28 01:34:00Z johan $
 * @package     Koowa_Command
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Command Context
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Command
 */
class KCommandContext extends KConfig
{
    /**
     * Error
     *
     * @var string
     */
    protected $_error;

    /**
     * Set the error
     *
     * @return  KCommandContext
     */
    function setError($error)
    {
        $this->_error = $error;
        return $this;
    }

    /**
     * Get the error
     *
     * @return  string  The error
     */
    function getError()
    {
        return $this->_error;
    }
}

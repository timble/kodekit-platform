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
 * Stream Wrapper Registry
 *
 * @author  Johan Janssens <https://github.com/johanjanssens>
 * @package Nooku\Library\FileSystem
 */
class FilesystemStreamWrapperRegistry extends Object implements ObjectSingleton
{
    /**
     * Register the stream wrapper
     *
     * Function prevents from registering the wrapper twice
     *
     * @param string $identifier A wrapper identifier string
     * @throws \UnexpectedValueException
     * @return bool Returns TRUE on success, FALSE on failure.
     */
    public function register($identifier)
    {
        $result = false;

        $identifier = $this->getIdentifier($identifier);
        $class      = $this->getObject('manager')->getClass($identifier);
        $name       = $class::getName();

        if (!empty($name) && !$this->isRegistered($name)) {
            $result = stream_wrapper_register($name, $class);
        }

        return $result;
    }

    /**
     * Un Register a stream wrapper
     *
     * @param string $identifier A wrapper object identifier string or wrapper name
     * @throws \UnexpectedValueException
     * @return bool Returns TRUE on success, FALSE on failure.
     */
    public function unregister($identifier)
    {
        $result = false;

        if(strpos($identifier, '.') !== false )
        {
            $identifier = $this->getIdentifier($identifier);
            $class      = $this->getObject('manager')->getClass($identifier);
            $name       = $class::getName();
        }
        else $name = $identifier;

        if (!empty($name) && $this->isRegistered($name)){
            $result = stream_wrapper_unregister($name);
        }

        return $result;
    }

    /**
     * Get a list of all the registered stream wrappers
     *
     * @return array
     */
    public function toArray()
    {
        return stream_get_wrappers();
    }

    /**
     * Check if the stream wrapper is registered
     *
     * @param string $identifier A wrapper object identifier string or wrapper name
     * @return bool TRUE if the wrapper is a registered stream wrapper, FALSE otherwise.
     */
    public function isRegistered($identifier)
    {
        if(strpos($identifier, '.') !== false )
        {
            $identifier = $this->getIdentifier($identifier);
            $class      = $this->getObject('manager')->getClass($identifier);
            $name       = $class::getName();
        }
        else $name = $identifier;

        $result = in_array($name, $this->toArray());
        return $result;
    }

    /**
     * Check if the stream wrapper for a registered protocol is supported
     *
     * @param string $identifier A wrapper object identifier string or wrapper name
     * @return bool TRUE if the wrapper is a registered stream wrapper and is supported, FALSE otherwise.
     */
    public function isSupported($identifier)
    {
        if(strpos($identifier, '.') !== false )
        {
            $identifier = $this->getIdentifier($identifier);
            $class      = $this->getObject('manager')->getClass($identifier);
            $name       = $class::getName();
        }
        else $name = $identifier;

        //Check if the wrapper is registered
        $result = $this->isRegistered($name);

        //Check if the wrapper is supported
        if(!ini_get('allow_url_fopen'))
        {
            if(in_array(array('ftp', 'sftp', 'http', 'https'), $name)) {
                $result = false;
            }
        }

        return $result;
    }
}
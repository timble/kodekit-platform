<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Object Config Format
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Object
 */
abstract class ObjectConfigFormat extends ObjectConfig implements ObjectConfigSerializable
{
    /**
     * Read from a file and create a config object
     *
     * @param  string $filename
     * @param  bool    $object  If TRUE return a ConfigObject, if FALSE return an array. Default TRUE.
     * @throws \RuntimeException
     * @return ObjectConfigFormat|array
     */
    public function fromFile($filename, $object = true)
    {
        if (!is_file($filename) || !is_readable($filename)) {
            throw new \RuntimeException(sprintf("File '%s' doesn't exist or not readable", $filename));
        }

        $string = file_get_contents($filename);
        return $this->fromString($string, $object);
    }

    /**
     * Write a config object to a file.
     *
     * @param  string  $filename
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @return void
     */
    public function toFile($filename)
    {
        $directory = dirname($filename);

        if(empty($filename)) {
            throw new \InvalidArgumentException('No file name specified');
        }

        if (!is_dir($directory)) {
            throw new \RuntimeException(sprintf('Directory : %s does not exists!', $directory));
        }

        if (!is_writable($directory)) {
            throw new \RuntimeException(sprintf("Cannot write in directory : %s", $directory));
        }

        $result = file_put_contents($filename, $this->toString(), LOCK_EX);

        if($result === false) {
            throw new \RuntimeException(sprintf("Error writing to %s", $filename));
        }
    }

    /**
     * Allow PHP casting of this object
     *
     * @return string
     */
    final public function __toString()
    {
        $result = '';

        //Not allowed to throw exceptions in __toString() See : https://bugs.php.net/bug.php?id=53648
        try {
            $result = $this->toString();
        } catch (\Exception $e) {
            trigger_error(__NAMESPACE__.'\ObjectConfigFormat::__toString exception: '. (string) $e, E_USER_ERROR);
        }

        return $result;
    }
}
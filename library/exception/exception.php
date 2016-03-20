<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright   Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link        https://github.com/timble/kodekit-platform for the canonical source repository
 */

namespace Kodekit\Library;

/**
 * Exception Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Library\Exception
 */
interface Exception
{
    /**
     * Return the exception message
     *
     * @return string
     */
    public function getMessage();

    /**
     * Return the user defined exception code
     *
     * @return integer
     */
    public function getCode();

    /**
     * Return the source filename
     *
     * @return string
     */
    public function getFile();

    /**
     * Return the source line number
     *
     * @return integer
     */
    public function getLine();

    /**
     * Return the backtrace information
     *
     * @return array
     */
    public function getTrace();

    /**
     * Return the backtrace as a string
     *
     * @return string
     */
    public function getTraceAsString();

    /**
     * Returns previous Exception
     *
     * @return \Exception Returns the previous \Exception if available or NULL otherwise.
     */
    public function getPrevious();
}
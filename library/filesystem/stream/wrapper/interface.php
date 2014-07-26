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
 * FileSystem Stream Wrapper Interface
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Library\FileSystem
 */
interface FilesystemStreamWrapperInterface extends ObjectInterface, ObjectMultiton
{
    /**
     * Get the stream wrapper name used to register the stream with
     *
     * @return string The stream protocol
     */
    public static function getName();

    /**
     * Get the stream type
     *
     * @return string The stream type
     */
    public function getType();

    /**
     * Get the stream path
     *
     * @return string The stream protocol
     */
    public function getPath();

    /**
     * Set the stream options
     *
     * @return string The stream options
     */
    public function getOptions();

    /**
     * Set the stream options
     *
     * @param string $options Set the stream options
     */
    public function setOptions($options);

    /**
     * Set the stream mode
     *
     * @return string The stream mode
     */
    public function getMode();

    /**
     * Set the stream mode
     *
     * @param $mode
     */
    public function setMode($mode);
}
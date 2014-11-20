<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2007 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

 /**
  * Template Engine Interface
  *
  * @author  Johan Janssens <http://github.com/johanjanssens>
  * @package Nooku\Library\Template\Compiler\Interface
  */
interface TemplateEngineInterface extends TemplateInterface
{
    /**
     * Cache the template source to a file
     *
     * Write the template source to a file cache. Requires cache to be enabled. This method will throw exceptions if
     * caching fails and debug is enabled. If debug is disabled FALSE will be returned.
     *
     * @param  string $name   The file name
     * @param  string $source  The template source
     * @throws \RuntimeException If the file path does not exist
     * @throws \RuntimeException If the file path is not writable
     * @throws \RuntimeException If template cannot be written to the cache
     * @return bool TRUE on success. FALSE on failure
     */
    public function cache($name, $source);

    /**
     * Get the template object
     *
     * @return  TemplateInterface	The template object
     */
    public function getTemplate();

    /**
     * Get the engine supported file types
     *
     * @return array
     */
    public static function getFileTypes();

    /**
     * Enable or disable engine debugging
     *
     * If debug is enabled the engine will throw an exception if caching fails.
     *
     * @param bool $debug True or false.
     * @return TemplateEngineInterface
     */
    public function setDebug($debug);

    /**
     * Check if the engine is running in debug mode
     *
     * @return bool
     */
    public function isDebug();

    /**
     * Check if a file exists in the cache
     *
     * @param string $file The file name
     * @return string|false The cache file path. FALSE if the file cannot be found in the cache
     */
    public function isCached($file);
}

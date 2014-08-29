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
     * Get the engine supported file types
     *
     * @return array
     */
    public static function getFileTypes();

    /**
     * Get the template object
     *
     * @return  TemplateInterface	The template object
     */
    public function getTemplate();

    /**
     * Set the template object
     *
     * @return  TemplateInterface $template	The template object
     */
    public function setTemplate(TemplateInterface $template);

    /**
     * Cache the template to a file
     *
     * Write the template content to a file cache. If cache is enabled the file will be buffer in the cache path.
     * If caching is not enabled the file will be written to the temp path using a buffer://temp stream
     *
     * @param  string $file     The file name
     * @param  string $content  The template content to cache
     * @throws \RuntimeException If the file path does not exist
     * @throws \RuntimeException If the file path is not writable
     * @throws \RuntimeException If template cannot be written to the cache
     * @return bool TRUE on success. FALSE on failure
     */
    public function cache($file, $content);

    /**
     * Check if a file exists in the cache
     *
     * @param string $file The file name
     * @return string|false The cache file path. FALSE if the file cannot be found in the cache
     */
    public function isCached($file);
}

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
 * Template Locator Interface
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\TemplateLoaderComponent
 */
interface TemplateLocatorInterface
{
    /**
     * Locate the template based on a virtual path
     *
     * @param  string $path  Stream path or resource
     * @param  string $base  The base path or resource (used to resolved partials).
     * @throws \RuntimeException If the no base path was passed while trying to locate a partial.
     * @return string   The physical stream path for the template
     */
    public function locate($path, $base = null);

    /**
     * Find a template path
     *
     * @param array  $info The path information
     * @return bool|mixed
     */
    public function find(array $info);

    /**
     * Get the loader type
     *
     * @return string
     */
    public function getType();

    /**
     * Get a path from an file
     *
     * Function will check if the path is an alias and return the real file path
     *
     * @param  string $file The file path
     * @return string The real file path
     */
    public function realPath($file);
}
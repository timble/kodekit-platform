<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright      Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Application;

use Nooku\Library;

/**
 * Translator Catalogue Interface
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Koowa
 */
interface TranslatorCatalogueInterface extends Library\TranslatorCatalogueInterface
{
    /**
     * Import a file into the catalogue.
     *
     * @param string $file     The path of the file to import.
     * @param bool   $override Whether or not existing translations can be overridden during import.
     *
     * @return bool True if imported, false otherwise.
     */
    public function import($file, $override = false);

    /**
     * Tells if a translation key is found in the catalogue.
     *
     * @param string $key The translation key.
     *
     * @return bool True if found, false otherwise.
     */
    public function hasKey($key);
}
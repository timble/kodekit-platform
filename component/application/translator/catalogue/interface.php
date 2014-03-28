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
     * Import a translations into the catalogue.
     *
     * @param array $translations     Associative array containing translations.
     * @param bool  $override         Whether or not existing translations can be overridden during import.
     */
    public function import($translations, $override = false);

    /**
     * Tells if the catalogue contains a given string.
     *
     * @param string $string The string.
     *
     * @return bool True if found, false otherwise.
     */
    public function hasString($string);
}
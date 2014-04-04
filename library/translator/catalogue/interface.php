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
 * Translation Catalogue Interface
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Nooku\Library\Translator
 */
interface TranslatorCatalogueInterface extends \IteratorAggregate, \ArrayAccess, \Serializable
{
    /**
     * Load translations into the catalogue.
     *
     * @param array  $translations Associative array containing translations.
     * @param bool   $override     Whether or not existing translations can be overridden during import.
     *
     * @return bool True on success, false otherwise.
     */
    public function load($translations, $override = false);

    /**
     * Tells if the catalogue contains a given string.
     *
     * @param string $string The string.
     *
     * @return bool True if found, false otherwise.
     */
    public function hasString($string);
}

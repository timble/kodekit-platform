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
     * Sets a source as loaded.
     *
     * @param string $source The source.
     *
     * @return TranslatorCatalogueInterface
     */
    public function setLoaded($source);

    /**
     * Tells if translations from a given source are already loaded.
     *
     * @param string $bundle The source to check against.
     *
     * @return bool True if loaded, false otherwise.
     */
    public function isLoaded($source);
}
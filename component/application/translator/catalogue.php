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
 * Translator Catalogue
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Koowa
 */
class TranslatorCatalogue extends Library\TranslatorCatalogue implements TranslatorCatalogueInterface
{
    /**
     * @see TranslatorCatalogueInterface::import()
     */
    public function import($file, $override = false)
    {
        $result = false;

        if ($translations = $this->_loadFile($file)) {
            if ($override) {
                $this->_data = array_merge($this->_data, $translations);
            } else {
                $this->_data = array_merge($translations, $this->_data);
            }

            $result = true;
        }

        return $result;
    }

    /**
     * File loader.
     *
     * @param string $file The file path.
     *
     * @return array|bool The file content, false if not loaded.
     */
    protected function _loadFile($file)
    {
        $result = false;

        if ($content = @file_get_contents($file))
        {
            //Take off BOM if present in the ini file
            if ($content[0] == "\xEF" && $content[1] == "\xBB" && $content[2] == "\xBF")
            {
                $content = substr($content, 3);
            }

            // TODO: Review other formats for language files and get rid of the JRegistry dependency afterwards.
            $registry = new \JRegistry();
            $registry->loadINI($content);
            $result = $registry->toArray();
        }

        return $result;
    }

    /**
     * @see TranslatorCatalogueInterface::hasKey()
     */
    public function hasKey($key)
    {
        return isset($this->_data[$key]);
    }
}
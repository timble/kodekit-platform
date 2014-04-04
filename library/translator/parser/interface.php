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
 * Translator Parser Interface
 *
 * @author  Arunas Mazeika <https://github.com/arunasmazeika>
 * @package Nooku\Library\Translator
 */
interface TranslatorParserInterface
{
    /**
     * Parses a string containing data.
     *
     * @return array The parsed data.
     */
    public function parse($string);

    /**
     * Casts data into a string.
     *
     * @param array $data The data.
     *
     * @return string The string representation of the data.
     */
    public function toString($data);

    /**
     * File extension getter.
     *
     * @return string The file extension for the format supported by the parser.
     */
    public function getFileExtension();
}
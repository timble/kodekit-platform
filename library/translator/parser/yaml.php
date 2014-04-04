<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright      Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Translator YAML Parser
 *
 * @author  Arunas Mazeika <https://github.com/arunasmazeika>
 * @package Nooku\Library\Translator
 */
class TranslatorParserYaml extends Object implements TranslatorParserInterface
{
    protected $_file_extension = 'yaml';

    public function parse($string)
    {
        return yaml_parse($string);
    }

    public function toString($data)
    {
        return yaml_emit($data);
    }

    public function getFileExtension()
    {
        return $this->_file_extension;
    }
}
<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright   Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Translator Inflector Interface
 *
 * @author  Ercan Ozkaya <https://github.com/ercanozkaya>
 * @package Nooku\Library\Translator\Inflector\Interface
 */
interface TranslatorInflectorInterface
{
    /**
     * Returns the plural position to use for the given language and number.
     *
     * @param integer $number The number
     * @param string  $language The lnaguage
     * @return integer The plural position
     */
    public static function getPluralPosition($number, $language);

    /**
     * Overrides the default plural rule for a given language.
     *
     * @param callable $rule   A PHP callable
     * @param string $language   The language
     * @throws \LogicException
     * @return void
     */
    public static function setPluralRule(callable $rule, $language);
}

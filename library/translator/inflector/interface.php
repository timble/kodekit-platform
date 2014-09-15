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
     * Returns the plural position to use for the given locale and number.
     *
     * @param integer $number The number
     * @param string  $locale The locale
     * @return integer The plural position
     */
    public static function getPluralPosition($number, $locale);

    /**
     * Overrides the default plural rule for a given locale.
     *
     * @param callable $rule   A PHP callable
     * @param string $locale   The locale
     * @throws \LogicException
     * @return void
     */
    public static function setPluralRule(callable $rule, $locale);
}

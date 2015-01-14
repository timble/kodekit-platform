<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Activities;

use Nooku\Library;

/**
 * Activity Translator Interface.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Nooku\Component\Activities
 */
interface ActivityTranslatorInterface
{
    /**
     * Translates an activity format.
     *
     * @param string $string The activity format to translate.
     * @param array  $tokens An array of format tokens.
     * @return string The translated activity format.
     */
    public function translate($format, array $tokens = array());
}
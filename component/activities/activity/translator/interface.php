<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-activities for the canonical source repository
 */

namespace Kodekit\Component\Activities;

use Kodekit\Library;

/**
 * Activity Translator Interface.
 *
 * @author  Arunas Mazeika <https://github.com/amazeika>
 * @package Kodekit\Component\Activities
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
<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-languages for the canonical source repository
 */

namespace Kodekit\Component\Languages;

use Kodekit\Library;

/**
 * Iso Code Filter
 *
 * @author  Gergo Erdosi <http://github.com/gergoerdosi>
 * @package Kodekit\Component\Languages
 */
class LanguagesFilterIso extends Library\FilterCmd
{
    protected function _validate($value)
    {
        $value = trim($value);
        $pattern = '#^[a-z]{2,3}\-[a-z]{2,3}$#i';

        return (is_string($value) && (preg_match($pattern, $value)) == 1);
    }

    protected function _sanitize($value)
    {
        $value = trim($value);
        $pattern  = '#[^a-z\-]*#i';

        return preg_replace($pattern, '', $value);
    }
}
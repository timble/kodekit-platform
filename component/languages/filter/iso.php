<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Languages;

use Nooku\Library;

/**
 * Iso Code Filter
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Languages
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
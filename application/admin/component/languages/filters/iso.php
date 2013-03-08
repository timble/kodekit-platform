<?php
/**
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Iso Code Filter Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Languages
 */

class ComLanguagesFilterIso extends Framework\FilterCmd
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
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
 * Date Interface
 *
 * @author  Gergo Erodsi <http://nooku.assembla.com/profile/gergoerdosis>
 * @package Nooku\Library\Date
 */
interface DateInterface extends ObjectHandlable
{
    /**
     * Returns date formatted according to given format.
     *
     * @param  string $format The format to use
     * @return string The formatted data
     */
    public function format($format);

    /**
     * Returns human readable date.
     *
     * @param  string $period The smallest period to use. Default is 'second'.
     * @return string Formatted date.
     */
    public function humanize($period = 'second');
}